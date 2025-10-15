<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление городами')]
#[Layout('layouts.admin')]
class CityManagement extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingCityId = null;
    public $selectedCity = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterStatus = '';
    public $filterCountry = '';

    protected $paginationTheme = 'tailwind';

    public $countries = [];

    #[Validate('required|exists:countries,id')]
    public $countryId = '';

    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('required|string|max:255')]
    public $titleKk = '';

    #[Validate('required|string|max:255')]
    public $titleEn = '';

    #[Validate('required|string|max:10|unique:cities,value')]
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
        $this->authorize('manage-cities');

        $user = auth()->user();
        $this->canCreate = $user->can('create-cities');
        $this->canEdit = $user->can('manage-cities');
        $this->canDelete = $user->can('delete-cities');

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

    public function getCities()
    {
        $query = City::with('country');

        // Поиск
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%');
            });
        }

        // Фильтр по стране
        if (!empty($this->filterCountry)) {
            $query->where('country_id', $this->filterCountry);
        }

        // Фильтр по статусу
        if ($this->filterStatus !== '' && $this->filterStatus !== null) {
            $query->where('is_active', $this->filterStatus === '1');
        }

        return $query->paginate(10);
    }

    public function loadCountries()
    {
        $this->countries = Country::where('is_active', true)->get();
    }

    public function createCity()
    {
        $this->authorize('create-cities');

        $this->validate();

        City::create([
            'country_id' => $this->countryId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'value' => $this->value,
            'is_active' => (bool) $this->isActive,
        ]);

        $this->reset(['countryId', 'titleRu', 'titleKk', 'titleEn', 'value', 'isActive', 'showCreateModal']);

        session()->flash('message', 'Город успешно создан');

        // Перерисовываем компонент
        $this->render();
    }

    public function editCity($cityId)
    {
        $city = City::findOrFail($cityId);
        $this->authorize('manage-cities');

        $this->editingCityId = $city->id;
        $this->countryId = $city->country_id;
        $this->titleRu = $city->title_ru;
        $this->titleKk = $city->title_kk;
        $this->titleEn = $city->title_en;
        $this->value = $city->value;
        $this->isActive = $city->is_active;

        $this->showEditModal = true;
    }

    public function updateCity()
    {
        $this->authorize('manage-cities');

        $city = City::findOrFail($this->editingCityId);

        $this->validate([
            'countryId' => 'required|exists:countries,id',
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'titleEn' => 'required|string|max:255',
            'value' => 'required|string|max:10|unique:cities,value,' . $this->editingCityId,
        ]);

        $city->update([
            'country_id' => $this->countryId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'value' => $this->value,
            'is_active' => (bool) $this->isActive,
        ]);

        $this->reset(['countryId', 'titleRu', 'titleKk', 'titleEn', 'value', 'isActive', 'showEditModal', 'editingCityId']);

        session()->flash('message', 'Город успешно обновлен');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteCity($cityId)
    {
        $this->authorize('delete-cities');

        $city = City::findOrFail($cityId);

        // Проверяем, есть ли связанные клубы
        if ($city->clubs()->count() > 0) {
            session()->flash('error', 'Нельзя удалить город, так как с ним связаны клубы');
            return;
        }

        // Проверяем, есть ли связанные стадионы
        if ($city->stadiums()->count() > 0) {
            session()->flash('error', 'Нельзя удалить город, так как с ним связаны стадионы');
            return;
        }

        // Проверяем, есть ли связанные отели
        if ($city->hotels()->count() > 0) {
            session()->flash('error', 'Нельзя удалить город, так как с ним связаны отели');
            return;
        }

        // Проверяем, есть ли связанные матчи
        if ($city->matches()->count() > 0) {
            session()->flash('error', 'Нельзя удалить город, так как с ним связаны матчи');
            return;
        }

        $city->delete();

        session()->flash('message', 'Город успешно удален');
    }

    public function toggleCityStatus($cityId)
    {
        $this->authorize('manage-cities');

        $city = City::findOrFail($cityId);
        $city->is_active = !$city->is_active;
        $city->save();

        session()->flash('message', 'Статус города изменен');
    }

    public function render()
    {
        return view('livewire.city-management', [
            'cities' => $this->getCities(),
        ]);
    }
}