<?php

namespace App\Http\Livewire;

use App\Models\FinanceAssessor;
use App\Models\PayementAssessor;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AssessorAssetFinance extends Component
{
    use WithPagination;

    public $search, $limitPage = 12;
    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // check history payment assessor
        // $payment_assessor = PayementAssessor::where()

        if($this->search)
        {
            $user = User::where('name', 'like', '%'.$this->search.'%')->get()->pluck('id');
            $data_finance = FinanceAssessor::select('user_id',  DB::raw('SUM(amount) as total'))
            ->whereIn('user_id', $user)
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->paginate($this->limitPage);
        }else{
            $data_finance = FinanceAssessor::select('user_id',  DB::raw('SUM(amount) as total'))
            ->where('user_id', auth()->user()->id)
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->get();
        }
        // $users = User::role('asesor')->get();
        return view('livewire.assessor-asset-finance', [
            'data' => $data_finance,
        ]);
    }

    public function pay($id)
    {
        $this->dispatch('openModalPay', ['uid' => $id]);
    }
}
