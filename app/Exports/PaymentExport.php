<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentExport implements FromView
{
    protected $selected_id;

    public function __construct($id)
    {
        $this->selected_id = $id;
    }

    public function view() : View
    {
        if(empty($this->selected_id)){
            $payment = Payment::orderByDesc('created_at')->get();
        }else{
            $payment = Payment::whereIn('id', $this->selected_id)->orderByDesc('created_at')->get();
        }
        return view('admin.data.finance.view.export', [
            'payments' => $payment
        ]);
    }
}


// class PaymentExport implements FromCollection
// {
//     use Exportable;

//     protected $selected_id;

//     public function __construct($id)
//     {
//         $this->selected_id = $id;
//     }

//     public function collection()
//     {
//         // dd($this->selected_id);
//         return Payment::whereIn('id', $this->selected_id)->get();
//     }
// }
