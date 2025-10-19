<?php

namespace App\Livewire;

use App\Models\ActOfWork;
use App\Models\ActOfWorkService;
use App\Models\CommonService;
use App\Models\MatchEntity;
use App\Models\MatchJudge;
use App\Models\Operation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Управление АВР')]
class AvrProcessing extends Component
{
    use WithPagination;

    public $activeTab = 'processing';
    public $search = '';

    // Modal states
    public $showAvrModal = false;
    public $showViewModal = false;
    public $selectedMatch = null;
    public $selectedAvr = null;
    public $isEditing = false;

    // AVR Form data
    public $avrForm = [
        'match_id' => null,
        'judge_id' => null,
        'customer_info' => '',
        'dogovor_number' => '',
        'dogovor_date' => '',
        'act_number' => '',
        'act_date' => '',
    ];

    // Services array
    public $services = [];
    public $availableServices = [];
    public $availableJudges = [];

    protected $queryString = ['activeTab', 'search' => ['except' => '']];

    public function mount()
    {
        $this->authorize('avr-processing');
        $this->availableServices = CommonService::all();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // Open modal for creating new AVR
    public function openCreateModal($matchId)
    {
        $this->resetForm();
        $this->selectedMatch = MatchEntity::with([
            'ownerClub', 'guestClub', 'stadium', 'league', 'season'
        ])->findOrFail($matchId);

        $this->avrForm['match_id'] = $matchId;
        $this->avrForm['act_date'] = now()->format('Y-m-d');
        $this->avrForm['dogovor_date'] = now()->format('Y-m-d');

        // Get available judges for this match
        $this->availableJudges = MatchJudge::with(['user', 'judge_type'])
            ->where('match_id', $matchId)
            ->where('judge_response', 1)
            ->where('final_status', 1)
            ->get();

        $this->isEditing = false;
        $this->showAvrModal = true;
    }

    // Open modal for editing existing AVR
    public function openEditModal($avrId)
    {
        $this->resetForm();
        $avr = ActOfWork::with([
            'match.ownerClub', 'match.guestClub', 'match.stadium', 'match.league', 'match.season',
            'user', 'act_of_work_services.common_service'
        ])->findOrFail($avrId);

        $this->selectedAvr = $avr;
        $this->selectedMatch = $avr->match;

        $this->avrForm = [
            'match_id' => $avr->match_id,
            'judge_id' => $avr->judge_id,
            'customer_info' => $avr->customer_info,
            'dogovor_number' => $avr->dogovor_number,
            'dogovor_date' => $avr->dogovor_date,
            'act_number' => $avr->act_number,
            'act_date' => $avr->act_date->format('Y-m-d'),
        ];

        // Load existing services
        $this->services = $avr->act_of_work_services->map(function($service) {
            return [
                'id' => $service->id,
                'service_id' => $service->service_id,
                'price_per' => $service->price_per,
                'qty' => $service->qty,
                'price' => $service->price,
                'total_price' => $service->total_price,
                'executed_date' => $service->executed_date->format('Y-m-d'),
            ];
        })->toArray();

        // Get available judges
        $this->availableJudges = MatchJudge::with(['user', 'judge_type'])
            ->where('match_id', $avr->match_id)
            ->where('judge_response', 1)
            ->where('final_status', 1)
            ->get();

        $this->isEditing = true;
        $this->showAvrModal = true;
    }

    public function closeAvrModal()
    {
        $this->showAvrModal = false;
        $this->resetForm();
    }

    // Open view modal for viewing AVR details
    public function openViewModal($avrId)
    {
        $this->selectedAvr = ActOfWork::with([
            'match.ownerClub', 'match.guestClub', 'match.stadium', 'match.league', 'match.season',
            'user', 'act_of_work_services.common_service', 'operation'
        ])->findOrFail($avrId);

        $this->selectedMatch = $this->selectedAvr->match;
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedAvr = null;
        $this->selectedMatch = null;
    }

    public function resetForm()
    {
        $this->selectedMatch = null;
        $this->selectedAvr = null;
        $this->avrForm = [
            'match_id' => null,
            'judge_id' => null,
            'customer_info' => '',
            'dogovor_number' => '',
            'dogovor_date' => '',
            'act_number' => '',
            'act_date' => '',
        ];
        $this->services = [];
        $this->availableJudges = [];
        $this->isEditing = false;
    }

    // Add new service to the list
    public function addService()
    {
        $this->services[] = [
            'id' => null,
            'service_id' => '',
            'price_per' => 'KZT',
            'qty' => 1,
            'price' => 0,
            'total_price' => 0,
            'executed_date' => now()->format('Y-m-d'),
        ];
    }

    // Remove service from list
    public function removeService($index)
    {
        unset($this->services[$index]);
        $this->services = array_values($this->services);
    }

    // Update service total price
    public function updateServiceTotal($index)
    {
        if (isset($this->services[$index])) {
            $qty = floatval($this->services[$index]['qty'] ?? 0);
            $price = floatval($this->services[$index]['price'] ?? 0);
            $this->services[$index]['total_price'] = $qty * $price;
        }
    }

    // Save AVR
    public function saveAvr()
    {
        $this->validate([
            'avrForm.match_id' => 'required|exists:matches,id',
            'avrForm.judge_id' => 'required|exists:users,id',
            'avrForm.customer_info' => 'required|string',
            'avrForm.dogovor_number' => 'required|string',
            'avrForm.dogovor_date' => 'required|date',
            'avrForm.act_number' => 'required|string',
            'avrForm.act_date' => 'required|date',
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:common_services,id',
            'services.*.price_per' => 'required|string',
            'services.*.qty' => 'required|numeric|min:0.01',
            'services.*.price' => 'required|numeric|min:0',
            'services.*.executed_date' => 'required|date',
        ], [
            'services.required' => 'Необходимо добавить хотя бы одну услугу',
            'services.min' => 'Необходимо добавить хотя бы одну услугу',
        ]);

        // Check if judge already has AVR for this match
        if (!$this->isEditing) {
            $existingAvr = ActOfWork::where('match_id', $this->avrForm['match_id'])
                ->where('judge_id', $this->avrForm['judge_id'])
                ->first();

            if ($existingAvr) {
                session()->flash('error', 'У данного судьи уже есть АВР для этого матча');
                return;
            }
        }

        $processingOperation = Operation::where('value', 'avr_processing')->first();

        if ($this->isEditing && $this->selectedAvr) {
            // Update existing AVR
            $this->selectedAvr->update([
                'judge_id' => $this->avrForm['judge_id'],
                'customer_info' => $this->avrForm['customer_info'],
                'dogovor_number' => $this->avrForm['dogovor_number'],
                'dogovor_date' => $this->avrForm['dogovor_date'],
                'act_number' => $this->avrForm['act_number'],
                'act_date' => $this->avrForm['act_date'],
            ]);

            $avr = $this->selectedAvr;
        } else {
            // Create new AVR
            $avr = ActOfWork::create([
                'match_id' => $this->avrForm['match_id'],
                'operation_id' => $processingOperation->id,
                'judge_id' => $this->avrForm['judge_id'],
                'customer_info' => $this->avrForm['customer_info'],
                'first_status' => 1,
                'judge_status' => 0,
                'control_status' => 0,
                'first_financial_status' => 0,
                'last_financial_status' => 0,
                'final_status' => 0,
                'dogovor_number' => $this->avrForm['dogovor_number'],
                'dogovor_date' => $this->avrForm['dogovor_date'],
                'act_number' => $this->avrForm['act_number'],
                'act_date' => $this->avrForm['act_date'],
                'is_ready' => true,
            ]);
        }

        // Delete old services if editing
        if ($this->isEditing) {
            ActOfWorkService::where('act_of_work_id', $avr->id)->delete();
        }

        // Save services
        foreach ($this->services as $service) {
            ActOfWorkService::create([
                'act_of_work_id' => $avr->id,
                'service_id' => $service['service_id'],
                'price_per' => $service['price_per'],
                'qty' => $service['qty'],
                'price' => $service['price'],
                'total_price' => $service['total_price'],
                'executed_date' => $service['executed_date'],
            ]);
        }

        session()->flash('message', $this->isEditing ? 'АВР успешно обновлен' : 'АВР успешно создан');
        $this->closeAvrModal();
    }

    // Send for review (from Tab1)
    public function sendForReview($avrId)
    {
        $avr = ActOfWork::findOrFail($avrId);

        // Send to judge confirmation
        $operation = Operation::where('value', 'referee_confirmation')->firstOrFail();
        $avr->update([
            'operation_id' => $operation->id,
        ]);

        session()->flash('message', 'АВР отправлен на рассмотрение судье');
    }

    // Resend for review (from Tab2)
    public function resendForReview($avrId)
    {
        $avr = ActOfWork::findOrFail($avrId);

        if ($avr->judge_status == -1) {
            // Rejected by judge - send back to judge confirmation
            $operation = Operation::where('value', 'referee_confirmation')->firstOrFail();
            $avr->update([
                'operation_id' => $operation->id,
                'judge_status' => 0,
            ]);
        } elseif ($avr->judge_status == 1 && $avr->control_status == -1) {
            // Rejected by committee - send to committee approval
            $operation = Operation::where('value', 'avr_approval_by_committee')->firstOrFail();
            $avr->update([
                'operation_id' => $operation->id,
                'control_status' => 0,
            ]);
        } elseif ($avr->judge_status == 1 && $avr->control_status == 1 && $avr->first_financial_status == -1) {
            // Rejected by primary financial check
            $operation = Operation::where('value', 'primary_financial_check')->firstOrFail();
            $avr->update([
                'operation_id' => $operation->id,
                'first_financial_status' => 0,
            ]);
        } elseif ($avr->judge_status == 1 && $avr->control_status == 1 && $avr->first_financial_status == 1 && $avr->last_financial_status == -1) {
            // Rejected by control financial check
            $operation = Operation::where('value', 'control_financial_check')->firstOrFail();
            $avr->update([
                'operation_id' => $operation->id,
                'last_financial_status' => 0,
            ]);
        }

        session()->flash('message', 'АВР отправлен на повторное рассмотрение');
    }

    public function render()
    {
        $data = [];

        switch ($this->activeTab) {
            case 'processing':
                // Tab1: Оформление АВР
                $createdWaitingOp = Operation::where('value', 'avr_created_waiting_processing')->first();
                $processingOp = Operation::where('value', 'avr_processing')->first();

                $data['matches'] = MatchEntity::with(['ownerClub', 'guestClub', 'stadium', 'league', 'season', 'operation'])
                    ->whereIn('current_operation_id', [$createdWaitingOp?->id, $processingOp?->id])
                    ->when($this->search, function($query) {
                        $query->where(function($q) {
                            $q->where('id', 'like', "%{$this->search}%")
                              ->orWhereHas('ownerClub', function($club) {
                                  $club->where('short_name_ru', 'like', "%{$this->search}%");
                              })
                              ->orWhereHas('guestClub', function($club) {
                                  $club->where('short_name_ru', 'like', "%{$this->search}%");
                              });
                        });
                    })
                    ->orderBy('start_at', 'desc')
                    ->paginate(12);
                break;

            case 'rework':
                // Tab2: Возвращенные на доработку
                $reworkOp = Operation::where('value', 'avr_reprocessing')->first();

                $data['avrs'] = ActOfWork::with(['match.ownerClub', 'match.guestClub', 'match.stadium', 'user', 'operation'])
                    ->where('operation_id', $reworkOp?->id)
                    ->when($this->search, function($query) {
                        $query->whereHas('match', function($q) {
                            $q->where('id', 'like', "%{$this->search}%");
                        });
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);
                break;

            case 'judge_confirmation':
                // Tab3: Ожидание подтверждения судьи
                $judgeConfirmOp = Operation::where('value', 'referee_confirmation')->first();

                $data['avrs'] = ActOfWork::with(['match.ownerClub', 'match.guestClub', 'match.stadium', 'user', 'operation'])
                    ->where('operation_id', $judgeConfirmOp?->id)
                    ->where('judge_status', 0)
                    ->when($this->search, function($query) {
                        $query->whereHas('match', function($q) {
                            $q->where('id', 'like', "%{$this->search}%");
                        });
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);
                break;

            case 'committee_approval':
                // Tab4: Утверждение судейским комитетом
                $committeeOp = Operation::where('value', 'avr_approval_by_committee')->first();

                $data['avrs'] = ActOfWork::with(['match.ownerClub', 'match.guestClub', 'match.stadium', 'user', 'operation'])
                    ->where('operation_id', $committeeOp?->id)
                    ->where('control_status', 0)
                    ->when($this->search, function($query) {
                        $query->whereHas('match', function($q) {
                            $q->where('id', 'like', "%{$this->search}%");
                        });
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);
                break;

            case 'primary_financial':
                // Tab5: Первичная финансовая проверка
                $primaryFinOp = Operation::where('value', 'primary_financial_check')->first();

                $data['avrs'] = ActOfWork::with(['match.ownerClub', 'match.guestClub', 'match.stadium', 'user', 'operation'])
                    ->where('operation_id', $primaryFinOp?->id)
                    ->where('first_financial_status', 0)
                    ->when($this->search, function($query) {
                        $query->whereHas('match', function($q) {
                            $q->where('id', 'like', "%{$this->search}%");
                        });
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);
                break;

            case 'control_financial':
                // Tab6: Контрольная финансовая проверка
                $controlFinOp = Operation::where('value', 'control_financial_check')->first();

                $data['avrs'] = ActOfWork::with(['match.ownerClub', 'match.guestClub', 'match.stadium', 'user', 'operation'])
                    ->where('operation_id', $controlFinOp?->id)
                    ->where('last_financial_status', 0)
                    ->when($this->search, function($query) {
                        $query->whereHas('match', function($q) {
                            $q->where('id', 'like', "%{$this->search}%");
                        });
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);
                break;

            case 'completed':
                // Tab7: Завершенные АВР
                $confirmedOp = Operation::where('value', 'avr_confirmed_waiting_payment')->first();

                $data['avrs'] = ActOfWork::with(['match.ownerClub', 'match.guestClub', 'match.stadium', 'user', 'operation'])
                    ->where('operation_id', $confirmedOp?->id)
                    ->where('final_status', 1)
                    ->when($this->search, function($query) {
                        $query->whereHas('match', function($q) {
                            $q->where('id', 'like', "%{$this->search}%")
                              ->orWhereHas('ownerClub', function($club) {
                                  $club->where('short_name_ru', 'like', "%{$this->search}%");
                              })
                              ->orWhereHas('guestClub', function($club) {
                                  $club->where('short_name_ru', 'like', "%{$this->search}%");
                              });
                        })
                        ->orWhereHas('user', function($q) {
                            $q->where('last_name', 'like', "%{$this->search}%")
                              ->orWhere('first_name', 'like', "%{$this->search}%");
                        });
                    })
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
                break;
        }

        return view('livewire.avr-processing', $data)->layout(get_user_layout());
    }
}
