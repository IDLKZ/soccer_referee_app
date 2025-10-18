<?php

namespace App\Livewire;

use App\Models\JudgeType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Title('Управление типами судей')]
class JudgeTypeManagement extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingJudgeTypeId = null;

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

    #[Validate('required|string|max:50|unique:judge_types,value')]
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
        $this->authorize('manage-judge-types');

        $user = auth()->user();
        $this->canCreate = $user->can('manage-judge-types');
        $this->canEdit = $user->can('manage-judge-types');
        $this->canDelete = $user->can('manage-judge-types');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function getJudgeTypes()
    {
        $query = JudgeType::query();

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

        return $query->orderBy('title_ru')->paginate(10);
    }

    public function createJudgeType()
    {
        $this->authorize('manage-judge-types');

        $this->validate();

        JudgeType::create([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'value' => $this->value,
            'is_active' => (bool) $this->isActive,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'value', 'isActive', 'showCreateModal']);

        session()->flash('message', 'Тип судьи успешно создан');

        // Перерисовываем компонент
        $this->render();
    }

    public function editJudgeType($judgeTypeId)
    {
        $judgeType = JudgeType::findOrFail($judgeTypeId);
        $this->authorize('manage-judge-types');

        $this->editingJudgeTypeId = $judgeType->id;
        $this->titleRu = $judgeType->title_ru;
        $this->titleKk = $judgeType->title_kk;
        $this->titleEn = $judgeType->title_en;
        $this->value = $judgeType->value;
        $this->isActive = $judgeType->is_active;

        $this->showEditModal = true;
    }

    public function updateJudgeType()
    {
        $this->authorize('manage-judge-types');

        $judgeType = JudgeType::findOrFail($this->editingJudgeTypeId);

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'titleEn' => 'required|string|max:255',
            'value' => 'required|string|max:50|unique:judge_types,value,' . $this->editingJudgeTypeId,
        ]);

        $judgeType->update([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'value' => $this->value,
            'is_active' => (bool) $this->isActive,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'value', 'isActive', 'showEditModal', 'editingJudgeTypeId']);

        session()->flash('message', 'Тип судьи успешно обновлен');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteJudgeType($judgeTypeId)
    {
        $this->authorize('manage-judge-types');

        $judgeType = JudgeType::findOrFail($judgeTypeId);

        // Проверяем, есть ли связанные записи
        if ($judgeType->match_judges()->count() > 0) {
            session()->flash('error', 'Нельзя удалить тип судьи, так как с ним связаны матчи');
            return;
        }

        $judgeType->delete();

        session()->flash('message', 'Тип судьи успешно удален');
    }

    public function toggleJudgeTypeStatus($judgeTypeId)
    {
        $this->authorize('manage-judge-types');

        $judgeType = JudgeType::findOrFail($judgeTypeId);
        $judgeType->is_active = !$judgeType->is_active;
        $judgeType->save();

        session()->flash('message', 'Статус типа судьи изменен');
    }

    public function render()
    {
        return view('livewire.judge-type-management', [
            'judgeTypes' => $this->getJudgeTypes(),
        ])->layout(get_user_layout());
    }
}
