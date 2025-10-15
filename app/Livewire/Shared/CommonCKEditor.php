<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class CommonCKEditor extends Component
{
    public $model;
    public $label = 'Текст';
    public $placeholder = 'Введите текст...';
    public $height = '200px';
    public $required = false;
    public $darkMode = false;

    public function mount($model, $label = 'Текст', $placeholder = 'Введите текст...', $height = '200px', $required = false, $darkMode = false)
    {
        $this->model = $model;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->height = $height;
        $this->required = $required;
        $this->darkMode = $darkMode;
    }

    public function render()
    {
        return view('livewire.shared.common-c-k-editor');
    }
}