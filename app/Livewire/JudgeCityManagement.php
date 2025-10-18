<?php

namespace App\Livewire;

use App\Constants\RoleConstants;
use App\Models\JudgeCity;
use App\Models\User;
use App\Models\City;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Title('Управление связями судей и городов')]
class JudgeCityManagement extends Component
{
    use WithPagination;

    public $judges = [];
    public $cities = [];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingJudgeCityId = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterJudge = '';
    public $filterCity = '';

    protected $paginationTheme = 'tailwind';

    #[Validate('required|exists:users,id')]
    public $userId = '';

    #[Validate('required|exists:cities,id')]
    public $cityId = '';

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-judge-cities');

        $user = auth()->user();
        $this->canCreate = $user->can('manage-judge-cities');
        $this->canEdit = $user->can('manage-judge-cities');
        $this->canDelete = $user->can('manage-judge-cities');

        $this->loadJudges();
        $this->loadCities();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterJudge()
    {
        $this->resetPage();
    }

    public function updatedFilterCity()
    {
        $this->resetPage();
    }

    public function getJudgeCities()
    {
        $query = JudgeCity::with(['user', 'city']);

        // Поиск
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('user', function($userQuery) {
                    $userQuery->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('city', function($cityQuery) {
                    $cityQuery->where('title_ru', 'like', '%' . $this->search . '%')
                        ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                        ->orWhere('title_en', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Фильтр по судье
        if (!empty($this->filterJudge)) {
            $query->where('user_id', $this->filterJudge);
        }

        // Фильтр по городу
        if (!empty($this->filterCity)) {
            $query->where('city_id', $this->filterCity);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function loadJudges()
    {
        // Получаем всех пользователей с ролью SOCCER_REFEREE
        $this->judges = User::whereHas('role', function($query) {
            $query->where('value', RoleConstants::SOCCER_REFEREE);
        })->where('is_active', true)->get();
    }

    public function loadCities()
    {
        $this->cities = City::where('is_active', true)->get();
    }

    public function createJudgeCity()
    {
        $this->authorize('manage-judge-cities');

        $this->validate();

        // Проверяем, нет ли уже такой связи
        $exists = JudgeCity::where('user_id', $this->userId)
            ->where('city_id', $this->cityId)
            ->exists();

        if ($exists) {
            session()->flash('error', 'Такая связь уже существует');
            return;
        }

        JudgeCity::create([
            'user_id' => $this->userId,
            'city_id' => $this->cityId,
        ]);

        $this->reset(['userId', 'cityId', 'showCreateModal']);

        session()->flash('message', 'Связь судьи и города успешно создана');

        $this->render();
    }

    public function editJudgeCity($judgeCityId)
    {
        $judgeCity = JudgeCity::findOrFail($judgeCityId);
        $this->authorize('manage-judge-cities');

        $this->editingJudgeCityId = $judgeCity->id;
        $this->userId = $judgeCity->user_id;
        $this->cityId = $judgeCity->city_id;

        $this->showEditModal = true;
    }

    public function updateJudgeCity()
    {
        $this->authorize('manage-judge-cities');

        $judgeCity = JudgeCity::findOrFail($this->editingJudgeCityId);

        $this->validate([
            'userId' => 'required|exists:users,id',
            'cityId' => 'required|exists:cities,id',
        ]);

        // Проверяем, нет ли уже такой связи (кроме текущей)
        $exists = JudgeCity::where('user_id', $this->userId)
            ->where('city_id', $this->cityId)
            ->where('id', '!=', $this->editingJudgeCityId)
            ->exists();

        if ($exists) {
            session()->flash('error', 'Такая связь уже существует');
            return;
        }

        $judgeCity->update([
            'user_id' => $this->userId,
            'city_id' => $this->cityId,
        ]);

        $this->reset(['userId', 'cityId', 'showEditModal', 'editingJudgeCityId']);

        session()->flash('message', 'Связь судьи и города успешно обновлена');

        $this->render();
    }

    public function deleteJudgeCity($judgeCityId)
    {
        $this->authorize('manage-judge-cities');

        $judgeCity = JudgeCity::findOrFail($judgeCityId);
        $judgeCity->delete();

        session()->flash('message', 'Связь судьи и города успешно удалена');
    }

    public function render()
    {
        return view('livewire.judge-city-management', [
            'judgeCities' => $this->getJudgeCities(),
        ])->layout(get_user_layout());
    }
}
