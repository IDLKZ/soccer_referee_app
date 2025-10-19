<?php

namespace App\Livewire;

use App\Constants\RoleConstants;
use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление пользователями')]
class UserManagement extends Component
{
    use WithPagination;

    public $roles = [];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingUserId = null;
    public $selectedUser = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterRole = '';
    public $filterStatus = '';

    protected $paginationTheme = 'tailwind';

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

    #[Validate('required|string|max:255|unique:users,username')]
    public $username = '';

    #[Validate('required|exists:roles,id')]
    public $roleId = '';

    #[Validate('required|integer|min:1|max:2')]
    public $sex = 1;

    #[Validate('nullable|date')]
    public $birthDate = '';

    public $isVerified = false;
    public $password = '';
    public $repeatPassword = '';

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

        $this->loadRoles();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterRole()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function getUsers()
    {
        $query = User::with('role');

        // Поиск
        if ($this->search) {
            $query->where(function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        // Фильтр по роли
        if (!empty($this->filterRole)) {
            $query->where('role_id', $this->filterRole);
        }

        // Фильтр по статусу
        if ($this->filterStatus !== '' && $this->filterStatus !== null) {
            $query->where('is_active', $this->filterStatus === '1');
        }

        return $query->paginate(10);
    }

    public function loadRoles()
    {
        $this->roles = Role::where('is_active', true)->get();
    }

    public function createUser()
    {
        $this->authorize('create-users');

        $this->validate([
            'lastName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'patronomic' => 'nullable|string|max:255',
            'iin' => 'required|string|max:12|unique:users,iin',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'roleId' => 'required|exists:roles,id',
            'sex' => 'required|integer|min:1|max:2',
            'birthDate' => 'nullable|date',
            'password' => 'required|string|min:6',
            'repeatPassword' => 'required|same:password',
        ]);

        User::create([
            'role_id' => $this->roleId,
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'patronomic' => $this->patronomic,
            'iin' => $this->iin,
            'phone' => $this->phone,
            'email' => $this->email,
            'username' => $this->username,
            'sex' => $this->sex,
            'birth_date' => $this->birthDate ?: null,
            'is_active' => true,
            'is_verified' => $this->isVerified,
            'password_hash' => bcrypt($this->password),
        ]);

        $this->reset(['lastName', 'firstName', 'patronomic', 'iin', 'phone', 'email', 'username', 'roleId', 'sex', 'birthDate', 'isVerified', 'password', 'repeatPassword', 'showCreateModal']);

        session()->flash('message', 'Пользователь успешно создан');

        // Перерисовываем компонент
        $this->render();
    }

    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('manage-users');

        $this->editingUserId = $user->id;
        $this->lastName = $user->last_name;
        $this->firstName = $user->first_name;
        $this->patronomic = $user->patronomic;
        $this->iin = $user->iin;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->roleId = $user->role_id;
        $this->sex = $user->sex;
        $this->birthDate = $user->birth_date ? $user->birth_date->format('Y-m-d') : '';
        $this->isVerified = $user->is_verified;
        $this->password = '';
        $this->repeatPassword = '';

        $this->showEditModal = true;
    }

    public function updateUser()
    {
        $this->authorize('manage-users');

        $user = User::findOrFail($this->editingUserId);

        $validationRules = [
            'lastName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'patronomic' => 'nullable|string|max:255',
            'iin' => 'required|string|max:12|unique:users,iin,' . $this->editingUserId,
            'phone' => 'required|string|max:20|unique:users,phone,' . $this->editingUserId,
            'email' => 'required|email|unique:users,email,' . $this->editingUserId,
            'username' => 'required|string|max:255|unique:users,username,' . $this->editingUserId,
            'roleId' => 'required|exists:roles,id',
            'sex' => 'required|integer|min:1|max:2',
            'birthDate' => 'nullable|date',
        ];

        // Validate password only if provided
        if (!empty($this->password)) {
            $validationRules['password'] = 'required|string|min:6';
            $validationRules['repeatPassword'] = 'required|same:password';
        }

        $this->validate($validationRules);

        $updateData = [
            'role_id' => $this->roleId,
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'patronomic' => $this->patronomic,
            'iin' => $this->iin,
            'phone' => $this->phone,
            'email' => $this->email,
            'username' => $this->username,
            'sex' => $this->sex,
            'birth_date' => $this->birthDate ?: null,
            'is_verified' => $this->isVerified,
        ];

        // Update password if provided
        if (!empty($this->password)) {
            $updateData['password_hash'] = bcrypt($this->password);
        }

        $user->update($updateData);

        $this->reset(['lastName', 'firstName', 'patronomic', 'iin', 'phone', 'email', 'username', 'roleId', 'sex', 'birthDate', 'isVerified', 'password', 'repeatPassword', 'showEditModal', 'editingUserId']);

        session()->flash('message', 'Пользователь успешно обновлен');

        // Перерисовываем компонент
        $this->render();
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

        session()->flash('message', 'Пользователь успешно удален');
    }

    public function toggleUserStatus($userId)
    {
        $this->authorize('manage-users');

        $user = User::findOrFail($userId);
        $user->is_active = !$user->is_active;
        $user->save();

        session()->flash('message', 'Статус пользователя изменен');
    }

    public function render()
    {
        return view('livewire.user-management', [
            'users' => $this->getUsers(),
        ])->layout(get_user_layout());
    }
}