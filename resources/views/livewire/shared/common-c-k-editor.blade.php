@props([
    'label' => 'Текст',
    'model' => 'content',
    'placeholder' => 'Введите текст...',
    'height' => '200px',
    'required' => false,
    'darkMode' => false
])

<div class="w-full">
    @if($label)
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
    </label>
    @endif

    <div class="relative">
        <textarea
            wire:model="{{ $model }}"
            placeholder="{{ $placeholder }}"
            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
            style="height: {{ $height }}"
            id="editor-{{ uniqid() }}"
        ></textarea>
    </div>

    @error($model)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация CKEditor для всех текстовых полей
    const textareas = document.querySelectorAll('textarea[wire\\:model]');

    textareas.forEach(function(textarea) {
        // Проверяем, что это поле для описания (содержит 'description')
        if (textarea.getAttribute('wire:model') && textarea.getAttribute('wire:model').includes('description')) {

            // Проверяем, не инициализирован ли уже редактор
            if (!textarea.hasAttribute('data-ckeditor-initialized')) {
                textarea.setAttribute('data-ckeditor-initialized', 'true');

                ClassicEditor
                    .create(textarea, {
                        toolbar: {
                            items: [
                                'heading', '|',
                                'bold', 'italic', 'underline', 'strikethrough', '|',
                                'link', 'bulletedList', 'numberedList', '|',
                                'outdent', 'indent', '|',
                                'imageUpload', 'blockQuote', 'insertTable', '|',
                                'undo', 'redo'
                            ]
                        },
                        language: 'ru',
                        image: {
                            toolbar: [
                                'imageTextAlternative',
                                'imageStyle:full',
                                'imageStyle:side'
                            ]
                        },
                        table: {
                            contentToolbar: [
                                'tableColumn',
                                'tableRow',
                                'mergeTableCells'
                            ]
                        },
                        placeholder: textarea.placeholder,
                        height: textarea.style.height || '200px'
                    })
                    .then(editor => {
                        // Сохраняем экземпляр редактора
                        textarea.editorInstance = editor;

                        // Обновляем Livewire модель при изменении контента
                        editor.model.document.on('change:data', () => {
                            const content = editor.getData();
                            textarea.value = content;

                            // Триггерим событие для Livewire
                            textarea.dispatchEvent(new Event('input'));

                            // Для Livewire 3.x
                            if (window.Livewire) {
                                window.Livewire.find(textarea.closest('[wire\\:id]').getAttribute('wire:id')).set(
                                    textarea.getAttribute('wire:model'),
                                    content
                                );
                            }
                        });

                        // Устанавливаем начальное значение
                        if (textarea.value) {
                            editor.setData(textarea.value);
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка инициализации CKEditor:', error);
                    });
            }
        }
    });
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
@endpush

@push('scripts')
<script type="importmap">
{
    "imports": {
        "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.js",
        "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.2.0/"
    }
}
</script>
<script type="module">
import {
    ClassicEditor,
    Essentials,
    Bold,
    Italic,
    Underline,
    Strikethrough,
    Heading,
    Link,
    List,
    ListItem,
    ListProperties,
    Indent,
    IndentBlock,
    BlockQuote,
    Image,
    ImageCaption,
    ImageStyle,
    ImageToolbar,
    Table,
    TableToolbar,
    TableProperties,
    TableCellProperties,
    MediaEmbed,
    Undo,
    Alignment
} from 'ckeditor5';

// Делаем редактор доступным глобально
window.ClassicEditor = ClassicEditor;
window.CKEditor5 = {
    Essentials,
    Bold,
    Italic,
    Underline,
    Strikethrough,
    Heading,
    Link,
    List,
    ListItem,
    ListProperties,
    Indent,
    IndentBlock,
    BlockQuote,
    Image,
    ImageCaption,
    ImageStyle,
    ImageToolbar,
    Table,
    TableToolbar,
    TableProperties,
    TableCellProperties,
    MediaEmbed,
    Undo,
    Alignment
};
</script>
@endpush