<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use Livewire\WithFileUploads;

class FileUploader extends Component
{
    use WithFileUploads;

    public $model;
    public $label = 'Загрузить файл';
    public $accept = 'image/*';
    public $multiple = false;
    public $maxSize = 5120; // 5MB
    public $preview = true;
    public $required = false;
    public $darkMode = false;
    public $value = '';

    protected $listeners = ['fileRemoved' => 'removeFile'];

    public function mount($model, $label = 'Загрузить файл', $accept = 'image/*', $multiple = false, $maxSize = 5120, $preview = true, $required = false, $darkMode = false, $value = '')
    {
        $this->model = $model;
        $this->label = $label;
        $this->accept = $accept;
        $this->multiple = $multiple;
        $this->maxSize = $maxSize;
        $this->preview = $preview;
        $this->required = $required;
        $this->darkMode = $darkMode;
        $this->value = $value;
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => $this->multiple ? 'array' : ($this->required ? 'required|' : 'sometimes|') . 'file|max:' . $this->maxSize
        ]);
    }

    public function removeFile()
    {
        $this->file = null;
        $this->dispatch('fileRemoved');
    }

    public function render()
    {
        return view('livewire.shared.file-uploader');
    }
}