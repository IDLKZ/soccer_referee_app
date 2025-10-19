<?php

namespace App\Livewire;

use App\Models\ActOfWork;
use App\Models\ActOfPayment;
use App\Models\MatchEntity;
use App\Models\Operation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Title('Оплатные документы АВР')]
class ActOfPayments extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showPaymentModal = false;
    public $showViewModal = false;
    public $selectedAvr = null;
    public $selectedMatch = null;

    // Payment form data
    public $paymentForm = [
        'act_id' => null,
        'payment_number' => '',
        'payment_date' => '',
        'payment_amount' => 0,
        'payment_method' => '',
        'checked_by' => '',
        'notes' => '',
    ];

    public $uploadedFiles = [];
    public $existingFiles = [];
    public $isEditing = false;
    public $editingPaymentId = null;

    protected $queryString = ['search' => ['except' => '']];

    public function mount()
    {
        $this->authorize('manage-act-payments');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Open modal to create payment
    public function openPaymentModal($avrId)
    {
        $this->resetPaymentForm();

        $this->selectedAvr = ActOfWork::with([
            'match.ownerClub', 'match.guestClub', 'user'
        ])->findOrFail($avrId);

        $this->paymentForm['act_id'] = $avrId;
        $this->paymentForm['payment_date'] = now()->format('Y-m-d');
        $this->paymentForm['checked_by'] = auth()->id(); // Auto-fill with current user

        $this->isEditing = false;
        $this->showPaymentModal = true;
    }

    // Open modal to edit payment
    public function openEditPaymentModal($paymentId)
    {
        $this->resetPaymentForm();

        $payment = ActOfPayment::with(['act_of_work.match', 'act_of_work.user'])->findOrFail($paymentId);

        $this->selectedAvr = $payment->act_of_work;
        $this->editingPaymentId = $paymentId;

        $info = is_array($payment->info) ? $payment->info : (is_string($payment->info) ? json_decode($payment->info, true) : []);

        $this->paymentForm = [
            'act_id' => $payment->act_id,
            'payment_number' => $info['payment_number'] ?? '',
            'payment_date' => $info['payment_date'] ?? now()->format('Y-m-d'),
            'payment_amount' => $info['payment_amount'] ?? 0,
            'payment_method' => $info['payment_method'] ?? '',
            'checked_by' => $payment->checked_by,
            'notes' => $info['notes'] ?? '',
        ];

        // Load existing files - already decoded by Laravel cast
        $this->existingFiles = is_array($payment->file_urls) ? $payment->file_urls : [];

        $this->isEditing = true;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->resetPaymentForm();
    }

    // Open view modal
    public function openViewModal($avrId)
    {
        $this->selectedAvr = ActOfWork::with([
            'match.ownerClub', 'match.guestClub', 'match.stadium', 'match.league', 'match.season',
            'user', 'act_of_work_services.common_service', 'operation', 'act_of_payments'
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

    // Save payment
    public function savePayment()
    {
        $this->validate([
            'paymentForm.act_id' => 'required|exists:act_of_works,id',
            'paymentForm.payment_number' => 'required|string',
            'paymentForm.payment_date' => 'required|date',
            'paymentForm.payment_amount' => 'required|numeric|min:0',
            'paymentForm.payment_method' => 'required|string',
            'paymentForm.checked_by' => 'required|integer|exists:users,id',
            'paymentForm.notes' => 'nullable|string',
            'uploadedFiles.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        $fileUrls = $this->existingFiles ?? [];

        // Upload new files
        if (!empty($this->uploadedFiles)) {
            foreach ($this->uploadedFiles as $file) {
                $path = $file->store('act_of_payments', 'public');
                $fileUrls[] = $path;
            }
        }

        // Prepare info array - Laravel will auto-encode to JSON with cast
        $info = [
            'payment_number' => $this->paymentForm['payment_number'],
            'payment_date' => $this->paymentForm['payment_date'],
            'payment_amount' => $this->paymentForm['payment_amount'],
            'payment_method' => $this->paymentForm['payment_method'],
            'notes' => $this->paymentForm['notes'],
        ];

        if ($this->isEditing && $this->editingPaymentId) {
            // Update existing payment
            $payment = ActOfPayment::findOrFail($this->editingPaymentId);
            $payment->update([
                'act_id' => $this->paymentForm['act_id'],
                'checked_by' => $this->paymentForm['checked_by'],
                'info' => $info, // Laravel auto-encodes with JSON cast
                'file_urls' => !empty($fileUrls) ? $fileUrls : null,
                'status' => 'paid',
            ]);

            session()->flash('message', 'Платежный документ успешно обновлен');
        } else {
            // Create new payment
            ActOfPayment::create([
                'act_id' => $this->paymentForm['act_id'],
                'checked_by' => $this->paymentForm['checked_by'],
                'info' => $info, // Laravel auto-encodes with JSON cast
                'file_urls' => !empty($fileUrls) ? $fileUrls : null,
                'status' => 'paid',
            ]);

            session()->flash('message', 'Платежный документ успешно создан');
        }

        $this->closePaymentModal();
    }

    // Delete file from payment
    public function deleteFile($index)
    {
        if (isset($this->existingFiles[$index])) {
            $filePath = $this->existingFiles[$index];

            // Delete from storage
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            // Remove from array
            unset($this->existingFiles[$index]);
            $this->existingFiles = array_values($this->existingFiles);
        }
    }

    // Mark payment as completed for match
    public function markPaymentCompleted($matchId)
    {
        $match = MatchEntity::with('act_of_works')->findOrFail($matchId);

        // Check if all act_of_works have at least one payment
        $allHavePayments = $match->act_of_works->every(function($avr) {
            return $avr->act_of_payments()->exists();
        });

        if (!$allHavePayments) {
            session()->flash('error', 'Все АВР должны иметь хотя бы один платежный документ');
            return;
        }

        // Get current operation
        $currentOp = Operation::find($match->current_operation_id);

        if ($currentOp->value === 'avr_confirmed_waiting_payment') {
            // Move to payment_completed
            $newOp = Operation::where('value', 'payment_completed')->firstOrFail();
            $match->update(['current_operation_id' => $newOp->id]);
            session()->flash('message', 'Оплата произведена. Статус изменен на "Оплата завершена"');
        } elseif ($currentOp->value === 'payment_completed') {
            // Move to successfully_completed and mark as finished
            $newOp = Operation::where('value', 'successfully_completed')->firstOrFail();
            $match->update([
                'current_operation_id' => $newOp->id,
                'is_finished' => true,
            ]);
            session()->flash('message', 'Матч успешно завершен');
        }
    }

    private function resetPaymentForm()
    {
        $this->selectedAvr = null;
        $this->paymentForm = [
            'act_id' => null,
            'payment_number' => '',
            'payment_date' => '',
            'payment_amount' => 0,
            'payment_method' => '',
            'checked_by' => auth()->id(),
            'notes' => '',
        ];
        $this->uploadedFiles = [];
        $this->existingFiles = [];
        $this->isEditing = false;
        $this->editingPaymentId = null;
    }

    public function render()
    {
        // Get operations
        $waitingPaymentOp = Operation::where('value', 'avr_confirmed_waiting_payment')->first();
        $paymentCompletedOp = Operation::where('value', 'payment_completed')->first();

        $matches = MatchEntity::with([
            'ownerClub', 'guestClub', 'stadium', 'league', 'season', 'operation',
            'act_of_works.user', 'act_of_works.act_of_payments'
        ])
            ->whereIn('current_operation_id', [$waitingPaymentOp?->id, $paymentCompletedOp?->id])
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
            ->paginate(10);

        return view('livewire.act-of-payments', [
            'matches' => $matches,
        ])->layout(get_user_layout());
    }
}
