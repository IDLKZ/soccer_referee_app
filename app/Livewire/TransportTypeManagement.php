<?php

namespace App\Livewire;

use App\Models\TransportType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление типами транспорта')]
#[Layout('layouts.admin')]
class TransportTypeManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingTransportTypeId = null;

    // Поиск
    public $search = '';

    protected $paginationTheme = 'tailwind';

    public $selectedMedia = [];

    // Для создания нового типа транспорта
    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('nullable|string|max:255')]
    public $titleKk = '';

    #[Validate('nullable|string|max:255')]
    public $titleEn = '';

    #[Validate('nullable|string')]
    public $descriptionRu = '';

    #[Validate('nullable|string')]
    public $descriptionKk = '';

    #[Validate('nullable|string')]
    public $descriptionEn = '';

    #[Validate('nullable|image|max:5120')] // max 5MB
    public $image = null;

    // Для редактирования
    #[Validate('required|string|max:255')]
    public $editTitleRu = '';

    #[Validate('nullable|string|max:255')]
    public $editTitleKk = '';

    #[Validate('nullable|string|max:255')]
    public $editTitleEn = '';

    #[Validate('nullable|string')]
    public $editDescriptionRu = '';

    #[Validate('nullable|string')]
    public $editDescriptionKk = '';

    #[Validate('nullable|string')]
    public $editDescriptionEn = '';

    #[Validate('nullable|image|max:5120')] // max 5MB
    public $editImage = null;

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-transport-types');

        $user = auth()->user();
        $this->canCreate = $user->can('create-transport-types');
        $this->canEdit = $user->can('manage-transport-types');
        $this->canDelete = $user->can('delete-transport-types');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getTransportTypes()
    {
        $query = TransportType::query();

        // Поиск по названию
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('description_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('description_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('description_en', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function createTransportType()
    {
        $this->authorize('create-transport-types');

        $this->validateCreateRules();

        $transportType = TransportType::create([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
        ]);

        // Handle image upload
        if ($this->image) {
            $media = $transportType->addMedia($this->image->getRealPath())
                  ->usingName($this->image->getClientOriginalName())
                  ->usingFileName($this->image->getClientOriginalName())
                  ->toMediaCollection('image');

            // Update image_url field with relative path
            $transportType->update(['image_url' => $media->getUrl()]);
        }

        // Add selected media if any
        if (!empty($this->selectedMedia)) {
            foreach ($this->selectedMedia as $mediaId) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
                if ($media) {
                    $transportType->addMediaFromMedia($media)
                          ->toMediaCollection('image');
                }
            }
        }

        $this->reset(['selectedMedia', 'titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'showCreateModal', 'image']);

        session()->flash('message', 'Тип транспорта успешно создан');

        // Перерисовываем компонент
        $this->render();
    }

    public function editTransportType($transportTypeId)
    {
        $transportType = TransportType::findOrFail($transportTypeId);
        $this->authorize('manage-transport-types');

        $this->editingTransportTypeId = $transportType->id;
        $this->selectedMedia = []; // For media selection
        $this->editTitleRu = $transportType->title_ru;
        $this->editTitleKk = $transportType->title_kk;
        $this->editTitleEn = $transportType->title_en;
        $this->editDescriptionRu = $transportType->description_ru;
        $this->editDescriptionKk = $transportType->description_kk;
        $this->editDescriptionEn = $transportType->description_en;

        $this->showEditModal = true;
    }

    public function updateTransportType()
    {
        $this->authorize('manage-transport-types');

        $transportType = TransportType::findOrFail($this->editingTransportTypeId);

        $this->validateEditRules();

        $transportType->update([
            'title_ru' => $this->editTitleRu,
            'title_kk' => $this->editTitleKk,
            'title_en' => $this->editTitleEn,
            'description_ru' => $this->editDescriptionRu,
            'description_kk' => $this->editDescriptionKk,
            'description_en' => $this->editDescriptionEn,
        ]);

        // Handle image upload
        if ($this->editImage) {
            // Clear existing images and add new one
            $transportType->clearMediaCollection('image');
            $media = $transportType->addMedia($this->editImage->getRealPath())
                  ->usingName($this->editImage->getClientOriginalName())
                  ->usingFileName($this->editImage->getClientOriginalName())
                  ->toMediaCollection('image');

            // Update image_url field with relative path
            $transportType->update(['image_url' => $media->getUrl()]);
        }

        // Update image if new media is selected
        if (!empty($this->selectedMedia)) {
            $transportType->clearMediaCollection('image');
            foreach ($this->selectedMedia as $mediaId) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
                if ($media) {
                    $transportType->addMediaFromMedia($media)
                          ->toMediaCollection('image');
                }
            }
        }

        $this->reset(['selectedMedia', 'editTitleRu', 'editTitleKk', 'editTitleEn', 'editDescriptionRu', 'editDescriptionKk', 'editDescriptionEn', 'showEditModal', 'editingTransportTypeId', 'editImage']);

        session()->flash('message', 'Тип транспорта успешно обновлен');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteTransportType($transportTypeId)
    {
        $this->authorize('delete-transport-types');

        $transportType = TransportType::findOrFail($transportTypeId);

        // Delete associated media
        $transportType->media()->delete();

        $transportType->delete();

        session()->flash('message', 'Тип транспорта успешно удален');
    }

    public function removeCurrentImage()
    {
        $this->authorize('manage-transport-types');

        $transportType = TransportType::findOrFail($this->editingTransportTypeId);
        $transportType->clearMediaCollection('image');

        // Clear image_url field
        $transportType->update(['image_url' => null]);

        session()->flash('message', 'Изображение типа транспорта удалено');
    }

    protected function validateCreateRules()
    {
        return $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'nullable|string|max:255',
            'titleEn' => 'nullable|string|max:255',
            'descriptionRu' => 'nullable|string',
            'descriptionKk' => 'nullable|string',
            'descriptionEn' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
        ]);
    }

    protected function validateEditRules()
    {
        return $this->validate([
            'editTitleRu' => 'required|string|max:255',
            'editTitleKk' => 'nullable|string|max:255',
            'editTitleEn' => 'nullable|string|max:255',
            'editDescriptionRu' => 'nullable|string',
            'editDescriptionKk' => 'nullable|string',
            'editDescriptionEn' => 'nullable|string',
            'editImage' => 'nullable|image|max:5120',
        ]);
    }

    public function render()
    {
        return view('livewire.transport-type-management', [
            'transportTypes' => $this->getTransportTypes(),
        ]);
    }
}