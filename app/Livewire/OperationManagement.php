<?php

namespace App\Livewire;

use App\Models\Operation;
use App\Models\CategoryOperation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление операциями')]
class OperationManagement extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingOperationId = null;

    // Поиск
    public $search = '';

    protected $paginationTheme = 'tailwind';

    // Для создания новой операции
    #[Validate('required|integer|exists:category_operations,id')]
    public $categoryId = '';

    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('nullable|string|max:255')]
    public $titleKk = '';

    #[Validate('nullable|string|max:255')]
    public $titleEn = '';

    #[Validate('nullable|string')]
    public $descriptionRu = '';

    #[Validate('nullable|string')]
    public $descriptionKk = '';

    #[Validate('nullable|string')]
    public $descriptionEn = '';

    #[Validate('required|string|max:255|unique:operations,value')]
    public $value = '';

    #[Validate('array')]
    public $responsibleRoles = [];

    #[Validate('boolean')]
    public $isFirst = false;

    #[Validate('boolean')]
    public $isLast = false;

    #[Validate('boolean')]
    public $canReject = false;

    #[Validate('boolean')]
    public $isActive = true;

    #[Validate('required|integer|min:0')]
    public $result = 0;

    #[Validate('nullable|integer|exists:operations,id')]
    public $previousId = null;

    #[Validate('nullable|integer|exists:operations,id')]
    public $nextId = null;

    #[Validate('nullable|integer|exists:operations,id')]
    public $onRejectId = null;

    // Для редактирования
    #[Validate('required|integer|exists:category_operations,id')]
    public $editCategoryId = '';

    #[Validate('required|string|max:255')]
    public $editTitleRu = '';

    #[Validate('nullable|string|max:255')]
    public $editTitleKk = '';

    #[Validate('nullable|string|max:255')]
    public $editTitleEn = '';

    #[Validate('nullable|string')]
    public $editDescriptionRu = '';

    #[Validate('nullable|string')]
    public $editDescriptionKk = '';

    #[Validate('nullable|string')]
    public $editDescriptionEn = '';

    #[Validate('required|string|max:255')]
    public $editValue = '';

    #[Validate('array')]
    public $editResponsibleRoles = [];

    #[Validate('boolean')]
    public $editIsFirst = false;

    #[Validate('boolean')]
    public $editIsLast = false;

    #[Validate('boolean')]
    public $editCanReject = false;

    #[Validate('boolean')]
    public $editIsActive = true;

    #[Validate('required|integer|min:0')]
    public $editResult = 0;

    #[Validate('nullable|integer|exists:operations,id')]
    public $editPreviousId = null;

    #[Validate('nullable|integer|exists:operations,id')]
    public $editNextId = null;

    #[Validate('nullable|integer|exists:operations,id')]
    public $editOnRejectId = null;

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-operations');

        $user = auth()->user();
        $this->canCreate = $user->can('create-operations');
        $this->canEdit = $user->can('manage-operations');
        $this->canDelete = $user->can('delete-operations');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getOperations()
    {
        $query = Operation::with('category_operation');

        // Поиск по названию и значению
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%')
                  ->orWhereHas('category_operation', function($subQ) {
                      $subQ->where('title_ru', 'like', '%' . $this->search . '%')
                           ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                           ->orWhere('title_en', 'like', '%' . $this->search . '%');
                  });
            });
        }

        return $query->orderBy('category_id')->orderBy('title_ru')->paginate(10);
    }

    public function getCategories()
    {
        return CategoryOperation::where('is_active', true)->orderBy('level')->orderBy('title_ru')->get();
    }

    public function getAvailableOperations()
    {
        return Operation::where('is_active', true)->orderBy('title_ru')->get();
    }

    public function createOperation()
    {
        $this->authorize('create-operations');

        $this->validateCreateRules();

        Operation::create([
            'category_id' => $this->categoryId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'value' => $this->value,
            'responsible_roles' => $this->responsibleRoles,
            'is_first' => $this->isFirst,
            'is_last' => $this->isLast,
            'can_reject' => $this->canReject,
            'is_active' => $this->isActive,
            'result' => $this->result,
            'previous_id' => $this->previousId ?: null,
            'next_id' => $this->nextId ?: null,
            'on_reject_id' => $this->onRejectId ?: null,
        ]);

        $this->reset([
            'categoryId', 'titleRu', 'titleKk', 'titleEn',
            'descriptionRu', 'descriptionKk', 'descriptionEn',
            'value', 'responsibleRoles', 'isFirst', 'isLast',
            'canReject', 'isActive', 'result', 'previousId',
            'nextId', 'onRejectId', 'showCreateModal'
        ]);

        session()->flash('message', 'Операция успешно создана');

        // Перерисовываем компонент
        $this->render();
    }

    public function editOperation($operationId)
    {
        $operation = Operation::findOrFail($operationId);
        $this->authorize('manage-operations');

        $this->editingOperationId = $operation->id;
        $this->editCategoryId = $operation->category_id;
        $this->editTitleRu = $operation->title_ru;
        $this->editTitleKk = $operation->title_kk;
        $this->editTitleEn = $operation->title_en;
        $this->editDescriptionRu = $operation->description_ru;
        $this->editDescriptionKk = $operation->description_kk;
        $this->editDescriptionEn = $operation->description_en;
        $this->editValue = $operation->value;
        $this->editResponsibleRoles = $operation->responsible_roles ?? [];
        $this->editIsFirst = $operation->is_first;
        $this->editIsLast = $operation->is_last;
        $this->editCanReject = $operation->can_reject;
        $this->editIsActive = $operation->is_active;
        $this->editResult = $operation->result;
        $this->editPreviousId = $operation->previous_id;
        $this->editNextId = $operation->next_id;
        $this->editOnRejectId = $operation->on_reject_id;

        $this->showEditModal = true;
    }

    public function updateOperation()
    {
        $this->authorize('manage-operations');

        $operation = Operation::findOrFail($this->editingOperationId);

        $this->validateEditRules();

        $operation->update([
            'category_id' => $this->editCategoryId,
            'title_ru' => $this->editTitleRu,
            'title_kk' => $this->editTitleKk,
            'title_en' => $this->editTitleEn,
            'description_ru' => $this->editDescriptionRu,
            'description_kk' => $this->editDescriptionKk,
            'description_en' => $this->editDescriptionEn,
            'value' => $this->editValue,
            'responsible_roles' => $this->editResponsibleRoles,
            'is_first' => $this->editIsFirst,
            'is_last' => $this->editIsLast,
            'can_reject' => $this->editCanReject,
            'is_active' => $this->editIsActive,
            'result' => $this->editResult,
            'previous_id' => $this->editPreviousId ?: null,
            'next_id' => $this->editNextId ?: null,
            'on_reject_id' => $this->editOnRejectId ?: null,
        ]);

        $this->reset([
            'editCategoryId', 'editTitleRu', 'editTitleKk', 'editTitleEn',
            'editDescriptionRu', 'editDescriptionKk', 'editDescriptionEn',
            'editValue', 'editResponsibleRoles', 'editIsFirst', 'editIsLast',
            'editCanReject', 'editIsActive', 'editResult', 'editPreviousId',
            'editNextId', 'editOnRejectId', 'showEditModal', 'editingOperationId'
        ]);

        session()->flash('message', 'Операция успешно обновлена');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteOperation($operationId)
    {
        $this->authorize('delete-operations');

        $operation = Operation::findOrFail($operationId);

        $operation->delete();

        session()->flash('message', 'Операция успешно удалена');
    }

    public function toggleActive($operationId)
    {
        $this->authorize('manage-operations');

        $operation = Operation::findOrFail($operationId);
        $operation->update(['is_active' => !$operation->is_active]);

        session()->flash('message', $operation->is_active ? 'Операция активирована' : 'Операция деактивирована');
    }

    protected function validateCreateRules()
    {
        return $this->validate([
            'categoryId' => 'required|integer|exists:category_operations,id',
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'nullable|string|max:255',
            'titleEn' => 'nullable|string|max:255',
            'descriptionRu' => 'nullable|string',
            'descriptionKk' => 'nullable|string',
            'descriptionEn' => 'nullable|string',
            'value' => 'required|string|max:255|unique:operations,value',
            'responsibleRoles' => 'array',
            'isFirst' => 'boolean',
            'isLast' => 'boolean',
            'canReject' => 'boolean',
            'isActive' => 'boolean',
            'result' => 'required|integer|min:0',
            'previousId' => 'nullable|integer|exists:operations,id',
            'nextId' => 'nullable|integer|exists:operations,id',
            'onRejectId' => 'nullable|integer|exists:operations,id',
        ]);
    }

    protected function validateEditRules()
    {
        return $this->validate([
            'editCategoryId' => 'required|integer|exists:category_operations,id',
            'editTitleRu' => 'required|string|max:255',
            'editTitleKk' => 'nullable|string|max:255',
            'editTitleEn' => 'nullable|string|max:255',
            'editDescriptionRu' => 'nullable|string',
            'editDescriptionKk' => 'nullable|string',
            'editDescriptionEn' => 'nullable|string',
            'editValue' => 'required|string|max:255|unique:operations,value,' . $this->editingOperationId,
            'editResponsibleRoles' => 'array',
            'editIsFirst' => 'boolean',
            'editIsLast' => 'boolean',
            'editCanReject' => 'boolean',
            'editIsActive' => 'boolean',
            'editResult' => 'required|integer|min:0',
            'editPreviousId' => 'nullable|integer|exists:operations,id',
            'editNextId' => 'nullable|integer|exists:operations,id',
            'editOnRejectId' => 'nullable|integer|exists:operations,id',
        ]);
    }

    public function render()
    {
        return view('livewire.operation-management', [
            'operations' => $this->getOperations(),
            'categories' => $this->getCategories(),
            'availableOperations' => $this->getAvailableOperations(),
        ])->layout(get_user_layout());
    }
}