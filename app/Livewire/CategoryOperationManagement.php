<?php

namespace App\Livewire;

use App\Models\CategoryOperation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление категориями операций')]
#[Layout('layouts.admin')]
class CategoryOperationManagement extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingCategoryOperationId = null;

    // Поиск
    public $search = '';

    protected $paginationTheme = 'tailwind';

    // Для создания новой категории
    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('nullable|string|max:255')]
    public $titleKk = '';

    #[Validate('nullable|string|max:255')]
    public $titleEn = '';

    #[Validate('required|string|max:255')]
    public $value = '';

    #[Validate('required|integer|min:1|max:10')]
    public $level = 1;

    #[Validate('boolean')]
    public $isActive = true;

    // Для редактирования
    #[Validate('required|string|max:255')]
    public $editTitleRu = '';

    #[Validate('nullable|string|max:255')]
    public $editTitleKk = '';

    #[Validate('nullable|string|max:255')]
    public $editTitleEn = '';

    #[Validate('required|string|max:255')]
    public $editValue = '';

    #[Validate('required|integer|min:1|max:10')]
    public $editLevel = 1;

    #[Validate('boolean')]
    public $editIsActive = true;

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-category-operations');

        $user = auth()->user();
        $this->canCreate = $user->can('create-category-operations');
        $this->canEdit = $user->can('manage-category-operations');
        $this->canDelete = $user->can('delete-category-operations');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getCategoryOperations()
    {
        $query = CategoryOperation::query();

        // Поиск по названию и значению
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('level', 'asc')->orderBy('title_ru', 'asc')->paginate(10);
    }

    public function createCategoryOperation()
    {
        $this->authorize('create-category-operations');

        $this->validateCreateRules();

        CategoryOperation::create([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'value' => $this->value,
            'level' => $this->level,
            'is_active' => $this->isActive,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'value', 'level', 'isActive', 'showCreateModal']);

        session()->flash('message', 'Категория операции успешно создана');

        // Перерисовываем компонент
        $this->render();
    }

    public function editCategoryOperation($categoryOperationId)
    {
        $categoryOperation = CategoryOperation::findOrFail($categoryOperationId);
        $this->authorize('manage-category-operations');

        $this->editingCategoryOperationId = $categoryOperation->id;
        $this->editTitleRu = $categoryOperation->title_ru;
        $this->editTitleKk = $categoryOperation->title_kk;
        $this->editTitleEn = $categoryOperation->title_en;
        $this->editValue = $categoryOperation->value;
        $this->editLevel = $categoryOperation->level;
        $this->editIsActive = $categoryOperation->is_active;

        $this->showEditModal = true;
    }

    public function updateCategoryOperation()
    {
        $this->authorize('manage-category-operations');

        $categoryOperation = CategoryOperation::findOrFail($this->editingCategoryOperationId);

        $this->validateEditRules();

        $categoryOperation->update([
            'title_ru' => $this->editTitleRu,
            'title_kk' => $this->editTitleKk,
            'title_en' => $this->editTitleEn,
            'value' => $this->editValue,
            'level' => $this->editLevel,
            'is_active' => $this->editIsActive,
        ]);

        $this->reset(['editTitleRu', 'editTitleKk', 'editTitleEn', 'editValue', 'editLevel', 'editIsActive', 'showEditModal', 'editingCategoryOperationId']);

        session()->flash('message', 'Категория операции успешно обновлена');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteCategoryOperation($categoryOperationId)
    {
        $this->authorize('delete-category-operations');

        $categoryOperation = CategoryOperation::findOrFail($categoryOperationId);

        $categoryOperation->delete();

        session()->flash('message', 'Категория операции успешно удалена');
    }

    public function toggleActive($categoryOperationId)
    {
        $this->authorize('manage-category-operations');

        $categoryOperation = CategoryOperation::findOrFail($categoryOperationId);
        $categoryOperation->update(['is_active' => !$categoryOperation->is_active]);

        session()->flash('message', $categoryOperation->is_active ? 'Категория активирована' : 'Категория деактивирована');
    }

    protected function validateCreateRules()
    {
        return $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'nullable|string|max:255',
            'titleEn' => 'nullable|string|max:255',
            'value' => 'required|string|max:255|unique:category_operations,value',
            'level' => 'required|integer|min:1|max:10',
            'isActive' => 'boolean',
        ]);
    }

    protected function validateEditRules()
    {
        return $this->validate([
            'editTitleRu' => 'required|string|max:255',
            'editTitleKk' => 'nullable|string|max:255',
            'editTitleEn' => 'nullable|string|max:255',
            'editValue' => 'required|string|max:255|unique:category_operations,value,' . $this->editingCategoryOperationId,
            'editLevel' => 'required|integer|min:1|max:10',
            'editIsActive' => 'boolean',
        ]);
    }

    public function render()
    {
        return view('livewire.category-operation-management', [
            'categoryOperations' => $this->getCategoryOperations(),
        ]);
    }
}