<?php

namespace App\Livewire;

use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление странами')]
class CountryManagement extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingCountryId = null;
    public $selectedCountry = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterStatus = '';

    protected $paginationTheme = 'tailwind';

    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('required|string|max:255')]
    public $titleKk = '';

    #[Validate('required|string|max:255')]
    public $titleEn = '';

    #[Validate('required|string|max:10|unique:countries,value')]
    public $value = '';

    public $isActive = true;

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-countries');

        $user = auth()->user();
        $this->canCreate = $user->can('create-countries');
        $this->canEdit = $user->can('manage-countries');
        $this->canDelete = $user->can('delete-countries');

    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function getCountries()
    {
        $query = Country::query();

        // Поиск
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%');
            });
        }

        // Фильтр по статусу
        if ($this->filterStatus !== '' && $this->filterStatus !== null) {
            $query->where('is_active', $this->filterStatus === '1');
        }

        return $query->paginate(10);
    }

    public function createCountry()
    {
        $this->authorize('create-countries');

        $this->validate();

        Country::create([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'value' => $this->value,
            'is_active' => (bool) $this->isActive,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'value', 'isActive', 'showCreateModal']);

        session()->flash('message', 'Страна успешно создана');

        // Перерисовываем компонент
        $this->render();
    }

    public function editCountry($countryId)
    {
        $country = Country::findOrFail($countryId);
        $this->authorize('manage-countries');

        $this->editingCountryId = $country->id;
        $this->titleRu = $country->title_ru;
        $this->titleKk = $country->title_kk;
        $this->titleEn = $country->title_en;
        $this->value = $country->value;
        $this->isActive = $country->is_active;

        $this->showEditModal = true;
    }

    public function updateCountry()
    {
        $this->authorize('manage-countries');

        $country = Country::findOrFail($this->editingCountryId);

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'titleEn' => 'required|string|max:255',
            'value' => 'required|string|max:10|unique:countries,value,' . $this->editingCountryId,
        ]);

        $country->update([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'value' => $this->value,
            'is_active' => (bool) $this->isActive,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'value', 'isActive', 'showEditModal', 'editingCountryId']);

        session()->flash('message', 'Страна успешно обновлена');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteCountry($countryId)
    {
        $this->authorize('delete-countries');

        $country = Country::findOrFail($countryId);

        // Проверяем, есть ли связанные города
        if ($country->cities()->count() > 0) {
            session()->flash('error', 'Нельзя удалить страну, так как с ней связаны города');
            return;
        }

        // Проверяем, есть ли связанные лиги
        if ($country->leagues()->count() > 0) {
            session()->flash('error', 'Нельзя удалить страну, так как с ней связаны лиги');
            return;
        }

        $country->delete();

        session()->flash('message', 'Страна успешно удалена');
    }

    public function toggleCountryStatus($countryId)
    {
        $this->authorize('manage-countries');

        $country = Country::findOrFail($countryId);
        $country->is_active = !$country->is_active;
        $country->save();

        session()->flash('message', 'Статус страны изменен');
    }

    public function render()
    {
        return view('livewire.country-management', [
            'countries' => $this->getCountries(),
        ])->layout(get_user_layout());
    }
}
