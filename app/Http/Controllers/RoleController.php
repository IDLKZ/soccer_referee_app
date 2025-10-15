<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ru' => 'required|string|max:255',
            'title_kk' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ru' => 'nullable|string',
            'description_kk' => 'nullable|string',
            'description_en' => 'nullable|string',
            'value' => 'required|string|max:255|unique:roles,value',
            'is_administrative' => 'boolean',
            'is_active' => 'boolean',
            'can_register' => 'boolean',
            'is_system' => 'boolean',
        ]);

        // Auto-generate value from title_ru if not provided
        if (empty($validated['value'])) {
            $validated['value'] = Str::slug($validated['title_ru'], '_');
        }

        Role::create($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Роль успешно создана');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->loadCount('users');
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'title_ru' => 'required|string|max:255',
            'title_kk' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ru' => 'nullable|string',
            'description_kk' => 'nullable|string',
            'description_en' => 'nullable|string',
            'value' => 'required|string|max:255|unique:roles,value,' . $role->id,
            'is_administrative' => 'boolean',
            'is_active' => 'boolean',
            'can_register' => 'boolean',
            'is_system' => 'boolean',
        ]);

        $role->update($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Роль успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Prevent deleting system roles
        if ($role->is_system) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Невозможно удалить системную роль');
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Невозможно удалить роль, к которой привязаны пользователи');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Роль успешно удалена');
    }
}
