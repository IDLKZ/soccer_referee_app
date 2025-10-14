<?php

namespace App\Livewire;

use App\Constants\RoleConstants;
use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Title('Управление пользователями')]
class UserManagement extends Component
{
    public $users = [];
    public $roles = [];
    public $showCreateModal = false;
    public $selectedUser = null;

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

    #[Validate('required|exists:roles,id')]
    public $roleId = '';

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
        $this->authorize('manage-users');

        $user = auth()->user();
        $this->canCreate = $user->can('create-users');
        $this->canEdit = $user->can('manage-users');
        $this->canDelete = $user->role->value === RoleConstants::ADMINISTRATOR;

        $this->loadUsers();
        $this->loadRoles();
    }

    public function loadUsers()
    {
        $this->users = User::with('role')->get();
    }

    public function loadRoles()
    {
        $this->roles = Role::where('is_active', true)->get();
    }

    public function createUser()
    {
        $this->authorize('create-users');

        $this->validate();

        User::create([
            'role_id' => $this->roleId,
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
            'password_hash' => bcrypt('admin123'), // Пароль по умолчанию
        ]);

        $this->reset(['lastName', 'firstName', 'patronomic', 'iin', 'phone', 'email', 'roleId', 'sex', 'birthDate', 'showCreateModal']);
        $this->loadUsers();

        session()->flash('message', 'Пользователь успешно создан');
    }

    public function editUser($userId)
    {
        $this->selectedUser = User::findOrFail($userId);
        $this->authorize('edit-own-profile', $this->selectedUser);
    }

    public function deleteUser($userId)
    {
        $this->authorize('manage-users');

        $user = User::findOrFail($userId);

        // Нельзя удалить самого себя
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Нельзя удалить свой аккаунт');
            return;
        }

        $user->delete();
        $this->loadUsers();

        session()->flash('message', 'Пользователь успешно удален');
    }

    public function toggleUserStatus($userId)
    {
        $this->authorize('manage-users');

        $user = User::findOrFail($userId);
        $user->is_active = !$user->is_active;
        $user->save();

        $this->loadUsers();
        session()->flash('message', 'Статус пользователя изменен');
    }

    public function render()
    {
        return view('livewire.user-management')
            ->layout('layouts.app');
    }
}