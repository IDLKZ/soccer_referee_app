@props([
    'content' => '',
    'darkMode' => false,
    'maxHeight' => '200px',
    'showFullContent' => false
])

@if($content)
    <div class="editor-reader prose prose-sm max-w-none {{ $darkMode ? 'dark:prose-invert' : '' }}"
         style="max-height: {{ $maxHeight }}; overflow-y: {{ $showFullContent ? 'visible' : 'auto' }};">

        {{-- Безопасный вывод HTML контента --}}
        {!! $content !!}

        {{-- Кнопка "Читать далее" если контент обрезан --}}
        @if(!$showFullContent && str($content)->length() > 500)
        <div class="text-center mt-2">
            <button
                wire:click="$toggle('showFullContent')"
                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium transition-colors"
            >
                Читать далее...
            </button>
        </div>
        @endif
    </div>
@else
    <div class="text-gray-500 dark:text-gray-400 italic text-sm">
        Нет описания
    </div>
@endif

<style>
.editor-reader {
    line-height: 1.6;
}

.editor-reader h1, .editor-reader h2, .editor-reader h3, .editor-reader h4, .editor-reader h5, .editor-reader h6 {
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    font-weight: 600;
}

.editor-reader h1 { font-size: 1.5em; }
.editor-reader h2 { font-size: 1.3em; }
.editor-reader h3 { font-size: 1.2em; }
.editor-reader h4 { font-size: 1.1em; }
.editor-reader h5 { font-size: 1em; }
.editor-reader h6 { font-size: 0.9em; }

.editor-reader p {
    margin-bottom: 1em;
}

.editor-reader ul, .editor-reader ol {
    margin-bottom: 1em;
    padding-left: 2em;
}

.editor-reader li {
    margin-bottom: 0.5em;
}

.editor-reader blockquote {
    border-left: 4px solid #e5e7eb;
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    color: #6b7280;
}

.editor-reader table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1em;
}

.editor-reader th, .editor-reader td {
    border: 1px solid #e5e7eb;
    padding: 0.5rem;
    text-align: left;
}

.editor-reader th {
    background-color: #f9fafb;
    font-weight: 600;
}

.editor-reader img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1rem 0;
}

.editor-reader a {
    color: #3b82f6;
    text-decoration: underline;
}

.editor-reader a:hover {
    color: #2563eb;
}

/* Dark mode styles */
.dark .editor-reader {
    color: #e5e7eb;
}

.dark .editor-reader h1,
.dark .editor-reader h2,
.dark .editor-reader h3,
.dark .editor-reader h4,
.dark .editor-reader h5,
.dark .editor-reader h6 {
    color: #f3f4f6;
}

.dark .editor-reader blockquote {
    border-left-color: #4b5563;
    color: #9ca3af;
}

.dark .editor-reader th,
.dark .editor-reader td {
    border-color: #4b5563;
}

.dark .editor-reader th {
    background-color: #374151;
}

.dark .editor-reader a {
    color: #60a5fa;
}

.dark .editor-reader a:hover {
    color: #93c5fd;
}
</style>