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

    #[Validate('required|string|max:255')]
    public $lastName = '';

    #[Validate('required|string|max:255')]
    public $firstName = '';

    #[Validate('nullable|string|max:255')]
    public $patronomic = '';

    #[Validate('required|string|max:12|unique:users,iin')]
    public $iin = '';

    #[Validate('required|string|max:20|unique:users,phone')]
    public $phone = '';

    #[Validate('required|email|unique:users,email')]
    public $email = '';

    #[Validate('required|integer|min:1|max:2')]
    public $sex = 1;

    #[Validate('nullable|date')]
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

        $this->validate();

        $refereeRole = \App\Models\Role::where('value', RoleConstants::SOCCER_REFEREE)->first();

        User::create([
            'role_id' => $refereeRole->id,
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
            ->layout('layouts.app');
    }
}