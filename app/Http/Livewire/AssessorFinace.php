<?php

namespace App\Http\Livewire;

use App\Models\FinanceAssessor;
use App\Models\Payment;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AssessorFinace extends Component
{
    use WithPagination;

    #[Url(as:'q')]
    public $search = '';
    public $limitPage = 20;
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = FinanceAssessor::query()
            ->with(['getUser' => function($query) {
                $query->with('getAssessorFee');
            }])
            ->where('status', 1);

        if ($this->search) {
            $query->whereHas('getUser', function($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            });
        }

        $assessors = $query->select('user_id', \DB::raw('MAX(id) as max_id'), \DB::raw('count(*) as total'))
                        ->groupBy('user_id')
                        ->get();
        // dd($results);
        // Process and prepare all data at once instead of in the blade
        $processedData = $assessors->map(function($item) {
            // Get all paid payments where this user is an assessor
            $payments = Payment::where('status', 1)
                ->where(function($query) use ($item) {
                    $query->where('assessor_one_id', $item->user_id)
                        ->orWhere('assessor_two_id', $item->user_id);
                })
                ->get();

            $userIds = $payments->pluck('user_id')->unique()->toArray();

            // Get serdos and non-serdos users
            $serdosUsers = User::whereIn('id', $userIds)
                ->where('assessor_fee', 1)
                ->with('getAssessorFee')
                ->get();

            $nonSerdosUsers = User::whereIn('id', $userIds)
                ->where('assessor_fee', 2)
                ->with('getAssessorFee')
                ->get();

            // Extract fee amounts if available
            $serdosFee = $serdosUsers->isNotEmpty() ?
                (int)$serdosUsers->first()->getAssessorFee[$item->assessorStatus] : 0;

            $nonSerdosFee = $nonSerdosUsers->isNotEmpty() ?
                (int)$nonSerdosUsers->first()->getAssessorFee[$item->assessorStatus] : 0;

            // Calculate totals
            $serdosCount = $serdosUsers->count();
            $nonSerdosCount = $nonSerdosUsers->count();
            $serdosTotal = $serdosCount * $serdosFee;
            $nonSerdosTotal = $nonSerdosCount * $nonSerdosFee;

            return [
                'user_id' => $item->user_id,
                'user' => $item->getUser,
                'assessorStatus' => $item->assessorStatus,
                'serdosCount' => $serdosCount,
                'serdosFee' => $serdosFee,
                'serdosTotal' => $serdosTotal,
                'nonSerdosCount' => $nonSerdosCount,
                'nonSerdosFee' => $nonSerdosFee,
                'nonSerdosTotal' => $nonSerdosTotal,
                'saldoTotal' => $serdosTotal + $nonSerdosTotal,
                'saldoTaken' => $item->saldoTaken,
                'saldoRemaining' => ($serdosTotal + $nonSerdosTotal) - $item->saldoTaken
            ];
        });
        // dd($processedData);
        return view('livewire.assessor-finace', [
            'data' => $processedData
        ]);
    }

    public function pay($id)
    {
        $this->dispatch('openModalPay', ['uid' => $id]);
    }
}
