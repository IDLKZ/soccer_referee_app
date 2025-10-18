<?php

namespace App\Livewire;

use App\Constants\RoleConstants;
use App\Models\MatchEntity;
use App\Models\League;
use App\Models\Season;
use App\Models\Stadium;
use App\Models\City;
use App\Models\Club;
use App\Models\Operation;
use App\Models\JudgeType;
use App\Models\User;
use App\Models\JudgeRequirement;
use App\Models\MatchDeadline;
use App\Models\MatchLogist;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;

#[Title('Управление матчами')]
class MatchEntityManagement extends Component
{
    use WithPagination;

    public $leagues = [];
    public $seasons = [];
    public $stadiums = [];
    public $cities = [];
    public $clubs = [];
    public $operations = [];
    public $judgeTypes = [];
    public $logists = [];

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingMatchId = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterLeague = '';
    public $filterSeason = '';
    public $filterStatus = '';

    protected $paginationTheme = 'tailwind';

    #[Validate('required|exists:leagues,id')]
    public $leagueId = '';

    #[Validate('required|exists:seasons,id')]
    public $seasonId = '';

    #[Validate('required|exists:stadiums,id')]
    public $stadiumId = '';

    #[Validate('required|exists:operations,id')]
    public $currentOperationId = '';

    #[Validate('required|exists:cities,id')]
    public $cityId = '';

    #[Validate('required|exists:clubs,id')]
    public $ownerClubId = '';

    #[Validate('required|exists:clubs,id')]
    public $guestClubId = '';

    #[Validate('nullable|exists:clubs,id')]
    public $winnerId = '';

    #[Validate('nullable|integer|min:0')]
    public $ownerPoint = '';

    #[Validate('nullable|integer|min:0')]
    public $guestPoint = '';

    #[Validate('required|date')]
    public $startAt = '';

    #[Validate('required|date|after:startAt')]
    public $endAt = '';

    #[Validate('boolean')]
    public $isActive = true;

    #[Validate('boolean')]
    public $isFinished = false;

    #[Validate('boolean')]
    public $isCanceled = false;

    #[Validate('nullable|string|max:500')]
    public $cancelReason = '';

    // Массивы для множественных связей
    public $judgeRequirements = [];
    public $matchDeadlines = [];
    public $matchLogists = [];

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-matches');

        $user = auth()->user();
        $this->canCreate = $user->can('manage-matches');
        $this->canEdit = $user->can('manage-matches');
        $this->canDelete = $user->role->value === RoleConstants::ADMINISTRATOR;

        $this->loadRelatedData();

        // Инициализация с одним пустым элементом
        $this->addJudgeRequirement();
        $this->addMatchLogist();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterLeague()
    {
        $this->resetPage();
    }

    public function updatedFilterSeason()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    // Методы для управления Judge Requirements
    public function addJudgeRequirement()
    {
        $this->judgeRequirements[] = [
            'judge_type_id' => '',
            'qty' => 1,
            'is_required' => true,
        ];
    }

    public function removeJudgeRequirement($index)
    {
        unset($this->judgeRequirements[$index]);
        $this->judgeRequirements = array_values($this->judgeRequirements);
    }

    // Методы для управления Match Deadlines
    public function addMatchDeadline()
    {
        $this->matchDeadlines[] = [
            'operation_id' => '',
            'start_at' => '',
            'end_at' => '',
        ];
    }

    public function removeMatchDeadline($index)
    {
        unset($this->matchDeadlines[$index]);
        $this->matchDeadlines = array_values($this->matchDeadlines);
    }

    // Методы для управления Match Logists
    public function addMatchLogist()
    {
        $this->matchLogists[] = [
            'logist_id' => '',
        ];
    }

    public function removeMatchLogist($index)
    {
        unset($this->matchLogists[$index]);
        $this->matchLogists = array_values($this->matchLogists);
    }

    public function getMatches()
    {
        $query = MatchEntity::with(['league', 'season', 'stadium', 'city', 'operation'])
            ->with(['club' => function($q) {
                $q->select('id', 'title_ru', 'title_kk', 'title_en', 'short_name_ru');
            }])
            ->with(['judge_requirements.judge_type', 'match_deadlines.operation', 'match_logists.user']);

        // Поиск
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('league', function($lq) {
                    $lq->where('title_ru', 'like', '%' . $this->search . '%')
                      ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                      ->orWhere('title_en', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('stadium', function($sq) {
                    $sq->where('title_ru', 'like', '%' . $this->search . '%')
                      ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                      ->orWhere('title_en', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('city', function($cq) {
                    $cq->where('title_ru', 'like', '%' . $this->search . '%')
                      ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                      ->orWhere('title_en', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Фильтр по лиге
        if (!empty($this->filterLeague)) {
            $query->where('league_id', $this->filterLeague);
        }

        // Фильтр по сезону
        if (!empty($this->filterSeason)) {
            $query->where('season_id', $this->filterSeason);
        }

        // Фильтр по статусу
        if ($this->filterStatus !== '' && $this->filterStatus !== null) {
            switch ($this->filterStatus) {
                case 'active':
                    $query->where('is_active', true)
                          ->where('is_finished', false)
                          ->where('is_canceled', false);
                    break;
                case 'finished':
                    $query->where('is_finished', true);
                    break;
                case 'canceled':
                    $query->where('is_canceled', true);
                    break;
            }
        }

        return $query->orderBy('start_at', 'desc')->paginate(10);
    }

    public function loadRelatedData()
    {
        $this->leagues = League::where('is_active', true)->get();
        $this->seasons = Season::where('is_active', true)->get();
        $this->stadiums = Stadium::where('is_active', true)->get();
        $this->cities = City::where('is_active', true)->get();
        $this->clubs = Club::where('is_active', true)->get();
        $this->operations = Operation::where('is_active', true)->get();
        $this->judgeTypes = JudgeType::where('is_active', true)->get();

        // Загрузка логистов (пользователей с ролью логиста)
        $this->logists = User::whereHas('role', function($q) {
            $q->where('value', RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN);
        })->where('is_active', true)->get();
    }

    public function createMatch()
    {
        $this->authorize('manage-matches');

        $this->validate();

        // Валидация: команды не могут играть сами с собой
        if ($this->ownerClubId == $this->guestClubId) {
            $this->addError('guestClubId', 'Команда не может играть сама с собой');
            return;
        }

        // Валидация Judge Requirements (обязательные)
        if (empty($this->judgeRequirements)) {
            $this->addError('judgeRequirements', 'Необходимо добавить хотя бы одно требование к судьям');
            return;
        }

        foreach ($this->judgeRequirements as $index => $requirement) {
            if (empty($requirement['judge_type_id'])) {
                $this->addError("judgeRequirements.{$index}.judge_type_id", 'Выберите тип судьи');
                return;
            }
            if (empty($requirement['qty']) || $requirement['qty'] < 1) {
                $this->addError("judgeRequirements.{$index}.qty", 'Количество должно быть не менее 1');
                return;
            }
        }

        // Валидация Match Logists (обязательные)
        if (empty($this->matchLogists)) {
            $this->addError('matchLogists', 'Необходимо добавить хотя бы одного логиста');
            return;
        }

        foreach ($this->matchLogists as $index => $logist) {
            if (empty($logist['logist_id'])) {
                $this->addError("matchLogists.{$index}.logist_id", 'Выберите логиста');
                return;
            }
        }

        // Валидация Match Deadlines (если добавлены)
        foreach ($this->matchDeadlines as $index => $deadline) {
            if (!empty($deadline['operation_id'])) {
                if (empty($deadline['start_at']) || empty($deadline['end_at'])) {
                    $this->addError("matchDeadlines.{$index}", 'Укажите даты начала и окончания');
                    return;
                }
            }
        }

        DB::transaction(function() {
            // Создание матча
            $match = MatchEntity::create([
                'league_id' => $this->leagueId,
                'season_id' => $this->seasonId,
                'stadium_id' => $this->stadiumId,
                'current_operation_id' => $this->currentOperationId,
                'city_id' => $this->cityId,
                'owner_club_id' => $this->ownerClubId,
                'guest_club_id' => $this->guestClubId,
                'winner_id' => $this->winnerId ?: null,
                'owner_point' => $this->ownerPoint ?: null,
                'guest_point' => $this->guestPoint ?: null,
                'start_at' => $this->startAt,
                'end_at' => $this->endAt,
                'is_active' => $this->isActive,
                'is_finished' => $this->isFinished,
                'is_canceled' => $this->isCanceled,
                'cancel_reason' => $this->cancelReason ?: null,
            ]);

            // Создание Judge Requirements
            foreach ($this->judgeRequirements as $requirement) {
                if (!empty($requirement['judge_type_id'])) {
                    JudgeRequirement::create([
                        'match_id' => $match->id,
                        'judge_type_id' => $requirement['judge_type_id'],
                        'qty' => $requirement['qty'],
                        'is_required' => $requirement['is_required'] ?? true,
                    ]);
                }
            }

            // Создание Match Deadlines
            foreach ($this->matchDeadlines as $deadline) {
                if (!empty($deadline['operation_id']) && !empty($deadline['start_at']) && !empty($deadline['end_at'])) {
                    MatchDeadline::create([
                        'match_id' => $match->id,
                        'operation_id' => $deadline['operation_id'],
                        'start_at' => $deadline['start_at'],
                        'end_at' => $deadline['end_at'],
                    ]);
                }
            }

            // Создание Match Logists
            foreach ($this->matchLogists as $logist) {
                if (!empty($logist['logist_id'])) {
                    MatchLogist::create([
                        'match_id' => $match->id,
                        'logist_id' => $logist['logist_id'],
                    ]);
                }
            }
        });

        $this->resetForm();
        $this->showCreateModal = false;

        session()->flash('message', 'Матч успешно создан со всеми связанными данными');

        $this->render();
    }

    public function editMatch($matchId)
    {
        $match = MatchEntity::with(['judge_requirements', 'match_deadlines', 'match_logists'])
            ->findOrFail($matchId);
        $this->authorize('manage-matches');

        $this->editingMatchId = $match->id;
        $this->leagueId = $match->league_id;
        $this->seasonId = $match->season_id;
        $this->stadiumId = $match->stadium_id;
        $this->currentOperationId = $match->current_operation_id;
        $this->cityId = $match->city_id;
        $this->ownerClubId = $match->owner_club_id;
        $this->guestClubId = $match->guest_club_id;
        $this->winnerId = $match->winner_id;
        $this->ownerPoint = $match->owner_point;
        $this->guestPoint = $match->guest_point;
        $this->startAt = $match->start_at->format('Y-m-d\TH:i');
        $this->endAt = $match->end_at->format('Y-m-d\TH:i');
        $this->isActive = $match->is_active;
        $this->isFinished = $match->is_finished;
        $this->isCanceled = $match->is_canceled;
        $this->cancelReason = $match->cancel_reason;

        // Загрузка существующих Judge Requirements
        $this->judgeRequirements = [];
        foreach ($match->judge_requirements as $requirement) {
            $this->judgeRequirements[] = [
                'id' => $requirement->id,
                'judge_type_id' => $requirement->judge_type_id,
                'qty' => $requirement->qty,
                'is_required' => $requirement->is_required,
            ];
        }

        // Загрузка существующих Match Deadlines
        $this->matchDeadlines = [];
        foreach ($match->match_deadlines as $deadline) {
            $this->matchDeadlines[] = [
                'id' => $deadline->id,
                'operation_id' => $deadline->operation_id,
                'start_at' => $deadline->start_at->format('Y-m-d\TH:i'),
                'end_at' => $deadline->end_at->format('Y-m-d\TH:i'),
            ];
        }

        // Загрузка существующих Match Logists
        $this->matchLogists = [];
        foreach ($match->match_logists as $logist) {
            $this->matchLogists[] = [
                'id' => $logist->id,
                'logist_id' => $logist->logist_id,
            ];
        }

        // Если массивы пусты, добавить хотя бы один элемент
        if (empty($this->judgeRequirements)) {
            $this->addJudgeRequirement();
        }
        if (empty($this->matchLogists)) {
            $this->addMatchLogist();
        }

        $this->showEditModal = true;
    }

    public function updateMatch()
    {
        $this->authorize('manage-matches');

        $match = MatchEntity::findOrFail($this->editingMatchId);

        $this->validate();

        // Валидация: команды не могут играть сами с собой
        if ($this->ownerClubId == $this->guestClubId) {
            $this->addError('guestClubId', 'Команда не может играть сама с собой');
            return;
        }

        // Валидация Judge Requirements (обязательные)
        if (empty($this->judgeRequirements)) {
            $this->addError('judgeRequirements', 'Необходимо добавить хотя бы одно требование к судьям');
            return;
        }

        foreach ($this->judgeRequirements as $index => $requirement) {
            if (empty($requirement['judge_type_id'])) {
                $this->addError("judgeRequirements.{$index}.judge_type_id", 'Выберите тип судьи');
                return;
            }
        }

        // Валидация Match Logists (обязательные)
        if (empty($this->matchLogists)) {
            $this->addError('matchLogists', 'Необходимо добавить хотя бы одного логиста');
            return;
        }

        foreach ($this->matchLogists as $index => $logist) {
            if (empty($logist['logist_id'])) {
                $this->addError("matchLogists.{$index}.logist_id", 'Выберите логиста');
                return;
            }
        }

        DB::transaction(function() use ($match) {
            // Обновление матча
            $match->update([
                'league_id' => $this->leagueId,
                'season_id' => $this->seasonId,
                'stadium_id' => $this->stadiumId,
                'current_operation_id' => $this->currentOperationId,
                'city_id' => $this->cityId,
                'owner_club_id' => $this->ownerClubId,
                'guest_club_id' => $this->guestClubId,
                'winner_id' => $this->winnerId ?: null,
                'owner_point' => $this->ownerPoint ?: null,
                'guest_point' => $this->guestPoint ?: null,
                'start_at' => $this->startAt,
                'end_at' => $this->endAt,
                'is_active' => $this->isActive,
                'is_finished' => $this->isFinished,
                'is_canceled' => $this->isCanceled,
                'cancel_reason' => $this->cancelReason ?: null,
            ]);

            // Удаление и пересоздание Judge Requirements
            $match->judge_requirements()->delete();
            foreach ($this->judgeRequirements as $requirement) {
                if (!empty($requirement['judge_type_id'])) {
                    JudgeRequirement::create([
                        'match_id' => $match->id,
                        'judge_type_id' => $requirement['judge_type_id'],
                        'qty' => $requirement['qty'],
                        'is_required' => $requirement['is_required'] ?? true,
                    ]);
                }
            }

            // Удаление и пересоздание Match Deadlines
            $match->match_deadlines()->delete();
            foreach ($this->matchDeadlines as $deadline) {
                if (!empty($deadline['operation_id']) && !empty($deadline['start_at']) && !empty($deadline['end_at'])) {
                    MatchDeadline::create([
                        'match_id' => $match->id,
                        'operation_id' => $deadline['operation_id'],
                        'start_at' => $deadline['start_at'],
                        'end_at' => $deadline['end_at'],
                    ]);
                }
            }

            // Удаление и пересоздание Match Logists
            $match->match_logists()->delete();
            foreach ($this->matchLogists as $logist) {
                if (!empty($logist['logist_id'])) {
                    MatchLogist::create([
                        'match_id' => $match->id,
                        'logist_id' => $logist['logist_id'],
                    ]);
                }
            }
        });

        $this->resetForm();
        $this->showEditModal = false;
        $this->editingMatchId = null;

        session()->flash('message', 'Матч успешно обновлен');

        $this->render();
    }

    public function deleteMatch($matchId)
    {
        $this->authorize('manage-matches');

        $match = MatchEntity::findOrFail($matchId);

        DB::transaction(function() use ($match) {
            // Связанные данные удалятся автоматически благодаря каскадному удалению
            $match->delete();
        });

        session()->flash('message', 'Матч успешно удален');
    }

    public function toggleMatchStatus($matchId)
    {
        $this->authorize('manage-matches');

        $match = MatchEntity::findOrFail($matchId);
        $match->is_active = !$match->is_active;
        $match->save();

        session()->flash('message', 'Статус матча изменен');
    }

    public function resetForm()
    {
        $this->reset([
            'leagueId', 'seasonId', 'stadiumId', 'currentOperationId',
            'cityId', 'ownerClubId', 'guestClubId', 'winnerId',
            'ownerPoint', 'guestPoint', 'startAt', 'endAt',
            'isActive', 'isFinished', 'isCanceled', 'cancelReason',
            'judgeRequirements', 'matchDeadlines', 'matchLogists'
        ]);
        $this->isActive = true;
        $this->isFinished = false;
        $this->isCanceled = false;

        // Реинициализация массивов
        $this->addJudgeRequirement();
        $this->addMatchLogist();
    }

    public function render()
    {
        return view('livewire.match-entity-management', [
            'matches' => $this->getMatches(),
        ])->layout(get_user_layout());
    }
}
