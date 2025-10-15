@props([
    'name' => 'content',
    'label' => 'Текст',
    'placeholder' => 'Введите текст...',
    'height' => '200px',
    'required' => false
])

<div class="w-full">
    @if($label)
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
    </label>
    @endif

    <textarea
        wire:model="{{ $name }}"
        placeholder="{{ $placeholder }}"
        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
        style="height: {{ $height }}"
        rows="8"
    ></textarea>

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>