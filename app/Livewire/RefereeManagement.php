<?php

namespace App\Livewire;

use App\Constants\RoleConstants;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Title('Управление судьями')]
class RefereeManagement extends Component
{
    public $referees = [];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingRefereeId = null;

    public $lastName = '';
    public $firstName = '';
    public $patronomic = '';
    public $iin = '';
    public $phone = '';
    public $email = '';
    public $sex = 1;
    public $birthDate = '';

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-referees');

        $user = auth()->user();
        $this->canCreate = $user->can('manage-referees');
        $this->canEdit = $user->can('manage-referees');
        $this->canDelete = $user->role->value === RoleConstants::ADMINISTRATOR ||
                          $user->role->value === RoleConstants::REFEREEING_DEPARTMENT_HEAD;

        $this->loadReferees();
    }

    public function loadReferees()
    {
        $this->referees = User::whereHas('role', function($query) {
            $query->where('value', RoleConstants::SOCCER_REFEREE);
        })->with('role')->get();
    }

    public function createReferee()
    {
        $this->authorize('manage-referees');

        $this->validate([
            'lastName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'patronomic' => 'nullable|string|max:255',
            'iin' => 'required|string|max:12|unique:users,iin',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'sex' => 'required|integer|min:1|max:2',
            'birthDate' => 'nullable|date',
        ]);

        $refereeRole = \App\Models\Role::where('value', RoleConstants::SOCCER_REFEREE)->first();

        User::create([
            'role_id' => $refereeRole->id,
            'username' => User::generateUniqueUsername($this->email),
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'patronomic' => $this->patronomic,
            'iin' => $this->iin,
            'phone' => $this->phone,
            'email' => $this->email,
            'sex' => $this->sex,
            'birth_date' => $this->birthDate,
            'is_active' => true,
            'is_verified' => false,
            'password_hash' => bcrypt('temp_password'), // Временный пароль
        ]);

        $this->reset(['lastName', 'firstName', 'patronomic', 'iin', 'phone', 'email', 'sex', 'birthDate', 'showCreateModal']);
        $this->loadReferees();

        session()->flash('message', 'Судья успешно создан');
    }

    public function editReferee($refereeId)
    {
        $this->authorize('manage-referees');

        $referee = User::findOrFail($refereeId);

        // Проверяем, что это действительно судья
        if ($referee->role->value !== RoleConstants::SOCCER_REFEREE) {
            abort(403, 'Вы можете редактировать только судей');
        }

        $this->editingRefereeId = $refereeId;
        $this->lastName = $referee->last_name;
        $this->firstName = $referee->first_name;
        $this->patronomic = $referee->patronomic ?? '';
        $this->iin = $referee->iin ?? '';
        $this->phone = $referee->phone;
        $this->email = $referee->email;
        $this->sex = $referee->sex;
        $this->birthDate = $referee->birth_date ? $referee->birth_date->format('Y-m-d') : '';

        $this->showEditModal = true;
    }

    public function updateReferee()
    {
        $this->authorize('manage-referees');

        $this->validate([
            'lastName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'patronomic' => 'nullable|string|max:255',
            'iin' => 'required|string|max:12|unique:users,iin,' . $this->editingRefereeId,
            'phone' => 'required|string|max:20|unique:users,phone,' . $this->editingRefereeId,
            'email' => 'required|email|unique:users,email,' . $this->editingRefereeId,
            'sex' => 'required|integer|min:1|max:2',
            'birthDate' => 'nullable|date',
        ]);

        $referee = User::findOrFail($this->editingRefereeId);

        $referee->update([
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'patronomic' => $this->patronomic,
            'iin' => $this->iin,
            'phone' => $this->phone,
            'email' => $this->email,
            'sex' => $this->sex,
            'birth_date' => $this->birthDate,
        ]);

        $this->reset(['lastName', 'firstName', 'patronomic', 'iin', 'phone', 'email', 'sex', 'birthDate', 'showEditModal', 'editingRefereeId']);
        $this->loadReferees();

        session()->flash('message', 'Судья успешно обновлен');
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['lastName', 'firstName', 'patronomic', 'iin', 'phone', 'email', 'sex', 'birthDate']);
        $this->resetValidation();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['lastName', 'firstName', 'patronomic', 'iin', 'phone', 'email', 'sex', 'birthDate', 'editingRefereeId']);
        $this->resetValidation();
    }

    public function deleteReferee($refereeId)
    {
        $this->authorize('manage-referees');

        $referee = User::findOrFail($refereeId);

        // Проверяем, что это действительно судья
        if ($referee->role->value !== RoleConstants::SOCCER_REFEREE) {
            abort(403, 'Вы можете удалять только судей');
        }

        $referee->delete();
        $this->loadReferees();

        session()->flash('message', 'Судья успешно удален');
    }

    public function render()
    {
        return view('livewire.referee-management')
            ->layout(get_user_layout());
    }
}