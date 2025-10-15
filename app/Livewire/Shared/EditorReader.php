<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class EditorReader extends Component
{
    public $content = '';
    public $darkMode = false;
    public $maxHeight = '200px';
    public $showFullContent = false;

    public function mount($content = '', $darkMode = false, $maxHeight = '200px', $showFullContent = false)
    {
        $this->content = $content;
        $this->darkMode = $darkMode;
        $this->maxHeight = $maxHeight;
        $this->showFullContent = $showFullContent;
    }

    public function render()
    {
        return view('livewire.shared.editor-reader');
    }
}