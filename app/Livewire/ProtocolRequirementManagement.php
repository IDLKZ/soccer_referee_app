<?php

namespace App\Livewire;

use App\Models\ProtocolRequirement;
use App\Models\League;
use App\Models\JudgeType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;

#[Title('Управление требованиями протоколов')]
class ProtocolRequirementManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $leagues = [];
    public $judgeTypes = [];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingRequirementId = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterLeague = '';
    public $filterJudgeType = '';
    public $filterRequired = '';

    protected $paginationTheme = 'tailwind';

    #[Validate('required|exists:leagues,id')]
    public $leagueId = '';

    #[Validate('nullable|exists:matches,id')]
    public $matchId = null;

    #[Validate('required|exists:judge_types,id')]
    public $judgeTypeId = '';

    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('nullable|string|max:255')]
    public $titleKk = '';

    #[Validate('nullable|string|max:255')]
    public $titleEn = '';

    #[Validate('required|string')]
    public $infoRu = '';

    #[Validate('nullable|string')]
    public $infoKk = '';

    #[Validate('nullable|string')]
    public $infoEn = '';

    public $isRequired = true;

    #[Validate('nullable|array')]
    public $exampleFiles = [];

    #[Validate('required|string')]
    public $extensions = 'pdf,doc,docx';

    public $existingFiles = [];

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-protocol-requirements');

        $user = auth()->user();
        $this->canCreate = $user->can('manage-protocol-requirements');
        $this->canEdit = $user->can('manage-protocol-requirements');
        $this->canDelete = $user->can('manage-protocol-requirements');

        $this->loadLeagues();
        $this->loadJudgeTypes();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterLeague()
    {
        $this->resetPage();
    }

    public function updatedFilterJudgeType()
    {
        $this->resetPage();
    }

    public function updatedFilterRequired()
    {
        $this->resetPage();
    }

    public function getRequirements()
    {
        $query = ProtocolRequirement::with(['league', 'judge_type']);

        // Поиск
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('info_ru', 'like', '%' . $this->search . '%');
            });
        }

        // Фильтр по лиге
        if (!empty($this->filterLeague)) {
            $query->where('league_id', $this->filterLeague);
        }

        // Фильтр по типу судьи
        if (!empty($this->filterJudgeType)) {
            $query->where('judge_type_id', $this->filterJudgeType);
        }

        // Фильтр по обязательности
        if ($this->filterRequired !== '' && $this->filterRequired !== null) {
            $query->where('is_required', $this->filterRequired === '1');
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function loadLeagues()
    {
        $this->leagues = League::where('is_active', true)->get();
    }

    public function loadJudgeTypes()
    {
        $this->judgeTypes = JudgeType::where('is_active', true)->get();
    }

    public function createRequirement()
    {
        $this->authorize('manage-protocol-requirements');

        $this->validate();

        // Обработка загруженных файлов
        $fileUrls = [];
        if (!empty($this->exampleFiles)) {
            foreach ($this->exampleFiles as $file) {
                $path = $file->store('protocol-requirements', 'public');
                $fileUrls[] = Storage::url($path);
            }
        }

        // Парсим расширения
        $extensionsArray = array_map('trim', explode(',', $this->extensions));

        ProtocolRequirement::create([
            'league_id' => $this->leagueId,
            'match_id' => $this->matchId,
            'judge_type_id' => $this->judgeTypeId,
            'example_file_url' => $fileUrls,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'info_ru' => $this->infoRu,
            'info_kk' => $this->infoKk,
            'info_en' => $this->infoEn,
            'is_required' => (bool) $this->isRequired,
            'extensions' => $extensionsArray,
        ]);

        $this->reset(['leagueId', 'matchId', 'judgeTypeId', 'titleRu', 'titleKk', 'titleEn', 'infoRu', 'infoKk', 'infoEn', 'isRequired', 'exampleFiles', 'extensions', 'showCreateModal']);

        session()->flash('message', 'Требование протокола успешно создано');

        $this->render();
    }

    public function editRequirement($requirementId)
    {
        $requirement = ProtocolRequirement::findOrFail($requirementId);
        $this->authorize('manage-protocol-requirements');

        $this->editingRequirementId = $requirement->id;
        $this->leagueId = $requirement->league_id;
        $this->matchId = $requirement->match_id;
        $this->judgeTypeId = $requirement->judge_type_id;
        $this->titleRu = $requirement->title_ru;
        $this->titleKk = $requirement->title_kk;
        $this->titleEn = $requirement->title_en;
        $this->infoRu = $requirement->info_ru;
        $this->infoKk = $requirement->info_kk;
        $this->infoEn = $requirement->info_en;
        $this->isRequired = $requirement->is_required;
        $this->extensions = is_array($requirement->extensions) ? implode(', ', $requirement->extensions) : $requirement->extensions;
        $this->existingFiles = $requirement->example_file_url ?? [];

        $this->showEditModal = true;
    }

    public function removeExistingFile($index)
    {
        unset($this->existingFiles[$index]);
        $this->existingFiles = array_values($this->existingFiles);
    }

    public function updateRequirement()
    {
        $this->authorize('manage-protocol-requirements');

        $requirement = ProtocolRequirement::findOrFail($this->editingRequirementId);

        $this->validate([
            'leagueId' => 'required|exists:leagues,id',
            'matchId' => 'nullable|exists:matches,id',
            'judgeTypeId' => 'required|exists:judge_types,id',
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'nullable|string|max:255',
            'titleEn' => 'nullable|string|max:255',
            'infoRu' => 'required|string',
            'infoKk' => 'nullable|string',
            'infoEn' => 'nullable|string',
            'extensions' => 'required|string',
        ]);

        // Обработка новых файлов
        $fileUrls = $this->existingFiles;
        if (!empty($this->exampleFiles)) {
            foreach ($this->exampleFiles as $file) {
                $path = $file->store('protocol-requirements', 'public');
                $fileUrls[] = Storage::url($path);
            }
        }

        // Парсим расширения
        $extensionsArray = array_map('trim', explode(',', $this->extensions));

        $requirement->update([
            'league_id' => $this->leagueId,
            'match_id' => $this->matchId,
            'judge_type_id' => $this->judgeTypeId,
            'example_file_url' => $fileUrls,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'info_ru' => $this->infoRu,
            'info_kk' => $this->infoKk,
            'info_en' => $this->infoEn,
            'is_required' => (bool) $this->isRequired,
            'extensions' => $extensionsArray,
        ]);

        $this->reset(['leagueId', 'matchId', 'judgeTypeId', 'titleRu', 'titleKk', 'titleEn', 'infoRu', 'infoKk', 'infoEn', 'isRequired', 'exampleFiles', 'extensions', 'existingFiles', 'showEditModal', 'editingRequirementId']);

        session()->flash('message', 'Требование протокола успешно обновлено');

        $this->render();
    }

    public function deleteRequirement($requirementId)
    {
        $this->authorize('manage-protocol-requirements');

        $requirement = ProtocolRequirement::findOrFail($requirementId);

        // Проверяем, есть ли связанные протоколы
        if ($requirement->protocols()->count() > 0) {
            session()->flash('error', 'Нельзя удалить требование, так как с ним связаны протоколы');
            return;
        }

        $requirement->delete();

        session()->flash('message', 'Требование протокола успешно удалено');
    }

    public function render()
    {
        return view('livewire.protocol-requirement-management', [
            'requirements' => $this->getRequirements(),
        ])->layout(get_user_layout());
    }
}
