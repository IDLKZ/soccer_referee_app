<?php

namespace App\Livewire;

use App\Models\League;
use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление лигами')]
class LeagueManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingLeagueId = null;
    public $selectedLeague = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterStatus = '';
    public $filterCountry = '';
    public $filterLevel = '';

    protected $paginationTheme = 'tailwind';

    public $countries = [];

    public $selectedMedia = [];

    #[Validate('required|exists:countries,id')]
    public $countryId = '';

    #[Validate('nullable|image|max:5120')] // max 5MB
    public $image = null;

    #[Validate('nullable|image|max:5120')] // max 5MB
    public $editImage = null;

    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('required|string|max:255')]
    public $titleKk = '';

    #[Validate('required|string|max:255')]
    public $titleEn = '';

    #[Validate('nullable|string')]
    public $descriptionRu = '';

    #[Validate('nullable|string')]
    public $descriptionKk = '';

    #[Validate('nullable|string')]
    public $descriptionEn = '';

    #[Validate('required|string|unique:leagues,value')]
    public $value = '';

    #[Validate('required|integer|min:1|max:10')]
    public $level = 1;

    public $isActive = true;

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-leagues');

        $user = auth()->user();
        $this->canCreate = $user->can('create-leagues');
        $this->canEdit = $user->can('manage-leagues');
        $this->canDelete = $user->can('delete-leagues');

        $this->loadCountries();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterCountry()
    {
        $this->resetPage();
    }

    public function updatedFilterLevel()
    {
        $this->resetPage();
    }

    public function getLeagues()
    {
        $query = League::with('country');

        // Поиск
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%')
                  ->orWhere('description_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('description_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('description_en', 'like', '%' . $this->search . '%');
            });
        }

        // Фильтр по стране
        if (!empty($this->filterCountry)) {
            $query->where('country_id', $this->filterCountry);
        }

        // Фильтр по уровню
        if (!empty($this->filterLevel)) {
            $query->where('level', $this->filterLevel);
        }

        // Фильтр по статусу
        if ($this->filterStatus !== '' && $this->filterStatus !== null) {
            $query->where('is_active', $this->filterStatus === '1');
        }

        return $query->orderBy('level')->orderBy('title_ru')->paginate(10);
    }

    public function loadCountries()
    {
        $this->countries = Country::where('is_active', true)->get();
    }

    public function createLeague()
    {
        $this->authorize('create-leagues');

        $this->validate([
            'countryId' => 'required|exists:countries,id',
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'titleEn' => 'required|string|max:255',
            'descriptionRu' => 'nullable|string',
            'descriptionKk' => 'nullable|string',
            'descriptionEn' => 'nullable|string',
            'value' => 'required|string|unique:leagues,value',
            'level' => 'required|integer|min:1|max:10',
            'image' => 'nullable|image|max:5120',
        ]);

        $league = League::create([
            'country_id' => $this->countryId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'value' => $this->value,
            'level' => $this->level,
            'is_active' => (bool) $this->isActive,
        ]);

        // Handle image upload
        if ($this->image) {
            $media = $league->addMedia($this->image->getRealPath())
                  ->usingName($this->image->getClientOriginalName())
                  ->usingFileName($this->image->getClientOriginalName())
                  ->toMediaCollection('image');

            // Update image_url field with relative path
            $league->update(['image_url' => $media->getUrl()]);
        }

        // Add selected media if any
        if (!empty($this->selectedMedia)) {
            foreach ($this->selectedMedia as $mediaId) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
                if ($media) {
                    $league->addMediaFromMedia($media)
                          ->toMediaCollection('image');
                }
            }
        }

        $this->reset(['selectedMedia', 'countryId', 'titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'value', 'level', 'isActive', 'showCreateModal', 'image']);

        session()->flash('message', 'Лига успешно создана');

        // Перерисовываем компонент
        $this->render();
    }

    public function editLeague($leagueId)
    {
        $league = League::findOrFail($leagueId);
        $this->authorize('manage-leagues');

        $this->editingLeagueId = $league->id;
        $this->selectedMedia = []; // For media selection
        $this->countryId = $league->country_id;
        $this->titleRu = $league->title_ru;
        $this->titleKk = $league->title_kk;
        $this->titleEn = $league->title_en;
        $this->descriptionRu = $league->description_ru;
        $this->descriptionKk = $league->description_kk;
        $this->descriptionEn = $league->description_en;
        $this->value = $league->value;
        $this->level = $league->level;
        $this->isActive = $league->is_active;

        $this->showEditModal = true;
    }

    public function updateLeague()
    {
        $this->authorize('manage-leagues');

        $league = League::findOrFail($this->editingLeagueId);

        $this->validate([
            'countryId' => 'required|exists:countries,id',
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'titleEn' => 'required|string|max:255',
            'descriptionRu' => 'nullable|string',
            'descriptionKk' => 'nullable|string',
            'descriptionEn' => 'nullable|string',
            'value' => 'required|string|unique:leagues,value,' . $this->editingLeagueId,
            'level' => 'required|integer|min:1|max:10',
            'editImage' => 'nullable|image|max:5120',
        ]);

        $league->update([
            'country_id' => $this->countryId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'value' => $this->value,
            'level' => $this->level,
            'is_active' => (bool) $this->isActive,
        ]);

        // Handle image upload
        if ($this->editImage) {
            // Clear existing images and add new one
            $league->clearMediaCollection('image');
            $media = $league->addMedia($this->editImage->getRealPath())
                  ->usingName($this->editImage->getClientOriginalName())
                  ->usingFileName($this->editImage->getClientOriginalName())
                  ->toMediaCollection('image');

            // Update image_url field with relative path
            $league->update(['image_url' => $media->getUrl()]);
        }

        // Update image if new media is selected
        if (!empty($this->selectedMedia)) {
            $league->clearMediaCollection('image');
            foreach ($this->selectedMedia as $mediaId) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
                if ($media) {
                    $league->addMediaFromMedia($media)
                          ->toMediaCollection('image');
                }
            }
        }

        $this->reset(['selectedMedia', 'countryId', 'titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'value', 'level', 'isActive', 'showEditModal', 'editingLeagueId', 'editImage']);

        session()->flash('message', 'Лига успешно обновлена');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteLeague($leagueId)
    {
        $this->authorize('delete-leagues');

        $league = League::findOrFail($leagueId);

        // Проверяем, есть ли связанные матчи
        if ($league->matches()->count() > 0) {
            session()->flash('error', 'Нельзя удалить лигу, так как с ней связаны матчи');
            return;
        }

        // Проверяем, есть ли связанные требования протоколов
        if ($league->protocol_requirements()->count() > 0) {
            session()->flash('error', 'Нельзя удалить лигу, так как с ней связаны требования протоколов');
            return;
        }

        // Delete associated media
        $league->media()->delete();

        $league->delete();

        session()->flash('message', 'Лига успешно удалена');
    }

    public function toggleLeagueStatus($leagueId)
    {
        $this->authorize('manage-leagues');

        $league = League::findOrFail($leagueId);
        $league->is_active = !$league->is_active;
        $league->save();

        session()->flash('message', 'Статус лиги изменен');
    }

    public function removeCurrentImage()
    {
        $this->authorize('manage-leagues');

        $league = League::findOrFail($this->editingLeagueId);
        $league->clearMediaCollection('image');

        // Clear image_url field
        $league->update(['image_url' => null]);

        session()->flash('message', 'Изображение лиги удалено');
    }

    public function render()
    {
        return view('livewire.league-management', [
            'leagues' => $this->getLeagues(),
        ])->layout(get_user_layout());
    }
}
