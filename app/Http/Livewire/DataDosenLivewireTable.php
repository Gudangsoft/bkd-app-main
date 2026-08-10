<?php

namespace App\Http\Livewire;

use App\Models\Payment;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DataDosenLivewireTable extends Component
{
    use WithPagination;

    public $search, $limitPage = 12;
    protected $queryString = ['search'];
    protected $paginationTheme = 'tailwind';
    public $uid;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->uid = auth()->user()->id;

        if($this->search)
        {
            $user = User::where('name', 'like', '%'.$this->search.'%')->get()->pluck('id');
            $data = Payment::whereIn('user_id', $user)->paginate($this->limitPage);
        }else{
            $data = Payment::where('assessor_one_id', $this->uid)->orWhere('assessor_two_id', $this->uid)->paginate($this->limitPage);
            // $total_amount_one = Payment::where('assessor_one_id', $this->uid)->orWhere('assessor_two_id', $this->uid)->get()->sum('amount_one');
            // $total_amount_two = Payment::where('assessor_one_id', $this->uid)->orWhere('assessor_two_id', $this->uid)->get()->sum('amount_two');
        }
        // dd($total_amount_two);
        return view('livewire.data-dosen-livewire-table', [
            'data' => $data,
        ]);
    }
}
