@props([
    'label' => 'Загрузить файл',
    'model' => 'file',
    'accept' => 'image/*',
    'multiple' => false,
    'maxSize' => 5120, // 5MB в KB
    'preview' => true,
    'required' => false,
    'darkMode' => false
])

<div class="w-full">
    @if($label)
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
    </label>
    @endif

    <!-- Текущее значение (для редактирования) -->
    @if(!empty($attributes->get('value')))
    <div class="mb-4">
        <div class="flex items-center space-x-4">
            @if(str($attributes->get('value'))->startsWith('http'))
                <!-- Внешний URL -->
                <img src="{{ $attributes->get('value') }}"
                     class="h-16 w-16 object-cover rounded-lg border border-gray-200 dark:border-gray-600"
                     alt="Preview">
            @else
                <!-- Локальный файл -->
                <img src="{{ Storage::url($attributes->get('value')) }}"
                     class="h-16 w-16 object-cover rounded-lg border border-gray-200 dark:border-gray-600"
                     alt="Preview">
            @endif

            <div class="flex-1">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Текущий файл: {{ basename($attributes->get('value')) }}
                </p>
                <button type="button"
                        wire:click="{{ $model }} = ''; $dispatch('fileRemoved')"
                        class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    Удалить файл
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Область загрузки -->
    <div class="relative">
        <input type="file"
               wire:model="{{ $model }}"
               accept="{{ $accept }}"
               {{ $multiple ? 'multiple' : '' }}
               class="hidden"
               id="file-input-{{ uniqid() }}">

        <label for="file-input-{{ uniqid() }}"
               class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">Нажмите для загрузки</span> или перетащите файл
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    @if($accept === 'image/*')
                    PNG, JPG, GIF до {{ round($maxSize / 1024, 1) }}MB
                    @else
                    Файлы до {{ round($maxSize / 1024, 1) }}MB
                    @endif
                </p>
            </div>
        </label>
    </div>

    <!-- Предпросмотр загруженных файлов -->
    @if($multiple && !empty($attributes->get('value')))
    <div class="mt-4 grid grid-cols-2 gap-4">
        @if(is_array($attributes->get('value')))
            @foreach($attributes->get('value') as $file)
            <div class="relative group">
                @if(str($file)->startsWith('http'))
                    <img src="{{ $file }}" class="w-full h-24 object-cover rounded-lg">
                @else
                    <img src="{{ Storage::url($file) }}" class="w-full h-24 object-cover rounded-lg">
                @endif

                <button type="button"
                        wire:click="removeFile('{{ $loop->index }}')"
                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            @endforeach
        @endif
    </div>
    @endif

    <!-- Прогресс загрузки -->
    @if($this->isUploading)
    <div class="mt-4">
        <div class="flex items-center justify-between mb-1">
            <span class="text-xs text-gray-600 dark:text-gray-400">Загрузка...</span>
            <span class="text-xs text-gray-600 dark:text-gray-400">{{ $uploadProgress }}%</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1">
            <div class="bg-blue-600 h-1 rounded-full transition-all duration-300" style="width: {{ $uploadProgress }}%"></div>
        </div>
    </div>
    @endif

    @error($model)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработка drag and drop
    const dropZones = document.querySelectorAll('[wire\\:model*="file"], [wire\\:model*="image"]');

    dropZones.forEach(zone => {
        const label = zone.previousElementSibling;
        if (label && label.tagName === 'LABEL') {
            const dropZone = label;

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                });
            });

            dropZone.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                const input = dropZone.querySelector('input[type="file"]');

                if (files.length > 0) {
                    input.files = files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }
    });
});
</script>
@endpush

<style>
/* Дополнительные стили для темной темы */
.dark .border-dashed {
    border-color: #4b5563;
}

.dark .border-dashed:hover {
    border-color: #6b7280;
}

/* Анимации для drag and drop */
.transition-all {
    transition: all 0.3s ease;
}
</style>