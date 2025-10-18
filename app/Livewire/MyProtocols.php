<?php

namespace App\Livewire;

use App\Models\MatchEntity;
use App\Models\Protocol;
use App\Models\ProtocolRequirement;
use App\Models\Operation;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;

#[Title('Мои протоколы')]
class MyProtocols extends Component
{
    use WithFileUploads;

    public $activeTab = 'create'; // 'create', 'primary', 'final', 'all'

    // Protocol Form
    public $protocolForm = [
        'id' => null,
        'match_id' => null,
        'requirement_id' => null,
        'info' => '',
        'file' => null,
    ];

    public $file = null;
    public $currentProtocol = null;
    public $showProtocolModal = false;

    // View Protocol Modal
    public $viewProtocol = null;
    public $showViewModal = false;

    public function mount()
    {
        $this->authorize('view-own-protocols');
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->reset('protocolForm', 'file', 'currentProtocol', 'showProtocolModal');
    }

    public function openProtocolModal($matchId)
    {
        $match = MatchEntity::with(['match_judges' => function($query) {
            $query->where('judge_id', auth()->id());
        }])->findOrFail($matchId);

        // Get protocol requirement for this match
        $requirement = ProtocolRequirement::where('match_id', $matchId)->first();

        if (!$requirement) {
            // Get requirement by league
            $requirement = ProtocolRequirement::where('league_id', $match->league_id)
                ->orderBy('id', 'desc')
                ->first();
        }

        // Check if protocol already exists
        $existingProtocol = Protocol::where('match_id', $matchId)
            ->where('judge_id', auth()->id())
            ->first();

        if ($existingProtocol) {
            $this->currentProtocol = $existingProtocol;
            $this->protocolForm = [
                'id' => $existingProtocol->id,
                'match_id' => $existingProtocol->match_id,
                'requirement_id' => $existingProtocol->requirement_id,
                'info' => $existingProtocol->info ?? '',
                'file' => null,
            ];
        } else {
            $this->protocolForm = [
                'id' => null,
                'match_id' => $matchId,
                'requirement_id' => $requirement?->id,
                'info' => '',
                'file' => null,
            ];
        }

        $this->showProtocolModal = true;
    }

    public function closeProtocolModal()
    {
        $this->showProtocolModal = false;
        $this->reset('protocolForm', 'file', 'currentProtocol');
    }

    public function saveProtocol()
    {
        $this->validate([
            'protocolForm.info' => 'nullable|string|max:1000',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ], [
            'file.max' => 'Размер файла не должен превышать 10 МБ',
            'file.mimes' => 'Файл должен быть в формате: pdf, doc, docx, jpg, jpeg, png',
        ]);

        if ($this->protocolForm['id']) {
            // Update existing protocol
            $protocol = Protocol::findOrFail($this->protocolForm['id']);

            $data = [
                'info' => $this->protocolForm['info'],
            ];

            if ($this->file) {
                // Delete old file if exists
                if ($protocol->file_url && \Storage::exists('public/' . $protocol->file_url)) {
                    \Storage::delete('public/' . $protocol->file_url);
                }

                $path = $this->file->store('protocols', 'public');
                $data['file_url'] = $path;
            }

            $protocol->update($data);
            session()->flash('message', 'Протокол успешно обновлен');
        } else {
            // Create new protocol
            $data = [
                'match_id' => $this->protocolForm['match_id'],
                'requirement_id' => $this->protocolForm['requirement_id'],
                'judge_id' => auth()->id(),
                'info' => $this->protocolForm['info'],
                'first_status' => 0,
                'final_status' => 0,
                'is_ready' => false,
            ];

            // Get operation for protocol_reprocessing (initial state)
            $reprocessingOperation = Operation::where('value', 'protocol_reprocessing')->first();
            if ($reprocessingOperation) {
                $data['operation_id'] = $reprocessingOperation->id;
            }

            if ($this->file) {
                $path = $this->file->store('protocols', 'public');
                $data['file_url'] = $path;
            }

            Protocol::create($data);
            session()->flash('message', 'Протокол успешно создан');
        }

        $this->closeProtocolModal();
    }

    public function deleteProtocol($protocolId)
    {
        $protocol = Protocol::where('id', $protocolId)
            ->where('judge_id', auth()->id())
            ->whereHas('operation', function($q) {
                $q->where('value', 'protocol_reprocessing');
            })
            ->firstOrFail();

        // Delete file if exists
        if ($protocol->file_url && \Storage::exists('public/' . $protocol->file_url)) {
            \Storage::delete('public/' . $protocol->file_url);
        }

        $protocol->delete();
        session()->flash('message', 'Протокол успешно удален');
    }

    public function submitForApproval($protocolId)
    {
        $protocol = Protocol::where('id', $protocolId)
            ->where('judge_id', auth()->id())
            ->firstOrFail();

        // Determine next operation based on first_status
        if ($protocol->first_status == 0) {
            $operationValue = 'primary_protocol_approval';
        } elseif ($protocol->first_status == 1) {
            $operationValue = 'control_protocol_approval';
        } else {
            session()->flash('error', 'Невозможно отправить протокол на проверку');
            return;
        }

        $operation = Operation::where('value', $operationValue)->firstOrFail();

        $protocol->update([
            'operation_id' => $operation->id,
            'is_ready' => true,
        ]);

        session()->flash('message', 'Протокол отправлен на проверку');
    }

    public function openViewModal($protocolId)
    {
        $this->viewProtocol = Protocol::with([
            'match.ownerClub',
            'match.guestClub',
            'match.stadium',
            'match.league',
            'match.season',
            'requirement',
            'operation',
            'user'
        ])->findOrFail($protocolId);

        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->viewProtocol = null;
    }

    public function render()
    {
        $createMatches = collect();
        $primaryProtocols = collect();
        $finalProtocols = collect();
        $allProtocols = collect();

        if ($this->activeTab === 'create') {
            // All assigned matches where protocol can be created
            $createMatches = MatchEntity::with([
                'ownerClub', 'guestClub', 'stadium', 'league', 'season', 'operation',
                'match_judges' => function($query) {
                    $query->where('judge_id', auth()->id())->with('judge_type');
                },
                'protocols' => function($query) {
                    $query->where('judge_id', auth()->id());
                }
            ])
            ->whereHas('operation', function($q) {
                $q->whereIn('value', ['waiting_for_protocol', 'protocol_reprocessing']);
            })
            ->whereHas('match_judges', function($q) {
                $q->where('judge_id', auth()->id());
            })
            ->orderBy('start_at', 'desc')
            ->get();

        } elseif ($this->activeTab === 'primary') {
            // Primary approval protocols (view only)
            $primaryProtocols = Protocol::with([
                'match.ownerClub', 'match.guestClub', 'match.stadium', 'match.league', 'operation'
            ])
            ->where('judge_id', auth()->id())
            ->where('first_status', 0)
            ->whereHas('operation', function($q) {
                $q->where('value', 'primary_protocol_approval');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        } elseif ($this->activeTab === 'final') {
            // Final approval protocols (view only)
            $finalProtocols = Protocol::with([
                'match.ownerClub', 'match.guestClub', 'match.stadium', 'match.league', 'operation'
            ])
            ->where('judge_id', auth()->id())
            ->where('final_status', 0)
            ->whereHas('operation', function($q) {
                $q->where('value', 'control_protocol_approval');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        } elseif ($this->activeTab === 'all') {
            // All protocols
            $allProtocols = Protocol::with([
                'match.ownerClub', 'match.guestClub', 'match.stadium', 'match.league', 'operation'
            ])
            ->where('judge_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        }

        return view('livewire.referee.my-protocols', [
            'createMatches' => $createMatches,
            'primaryProtocols' => $primaryProtocols,
            'finalProtocols' => $finalProtocols,
            'allProtocols' => $allProtocols,
        ])->layout('layouts.referee');
    }
}
