@props([
    'name' => 'file',
    'label' => 'Загрузить файл',
    'accept' => 'image/*',
    'multiple' => false,
    'maxSize' => 5120,
    'preview' => true,
    'required' => false,
    'value' => null
])

<div class="w-full">
    @if($label)
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
    </label>
    @endif

    <!-- Текущее значение -->
    @if($value)
    <div class="mb-4">
        <div class="flex items-center space-x-4">
            @if(str($value)->startsWith('http'))
                <img src="{{ $value }}" class="h-16 w-16 object-cover rounded-lg border border-gray-200 dark:border-gray-600" alt="Preview">
            @else
                <img src="{{ Storage::url($value) }}" class="h-16 w-16 object-cover rounded-lg border border-gray-200 dark:border-gray-600" alt="Preview">
            @endif
            <div class="flex-1">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Текущий файл: {{ basename($value) }}
                </p>
                <button type="button" wire:click="{{ $name }} = null" class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    Удалить файл
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Область загрузки -->
    <div class="relative">
        <input type="file"
               wire:model="{{ $name }}"
               accept="{{ $accept }}"
               {{ $multiple ? 'multiple' : '' }}
               class="hidden"
               id="file-{{ $name }}-{{ uniqid() }}">

        <label for="file-{{ $name }}-{{ uniqid() }}" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
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

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>