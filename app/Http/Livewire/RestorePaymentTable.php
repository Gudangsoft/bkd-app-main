<?php

namespace App\Http\Livewire;

use App\Models\Payment;
use Livewire\Component;
use Log;

class RestorePaymentTable extends Component
{
    public $message_restore, $message_delete;

    public function render()
    {
        $payments = Payment::onlyTrashed()->get();

        return view('livewire.restore-payment-table', [
            'data' => $payments
        ]);
    }

    public function restore($id)
    {
        $data = Payment::onlyTrashed()->find($id);
        // dd($data);
        $this->message_restore = 'Data Pembayaran ' . $data->user->name . ' Restored';
        if ($data) {
            $data->restore();
        }
        Log::info('Restore data payment ' . $id . ' R success | User:' . auth()->user()->name . '(ID:' . auth()->user()->id . ')');
    }

    public function delete($id)
    {
        $data = Payment::onlyTrashed()->find($id);
        // dd($data);
        $this->message_delete = 'Data Pembayaran ' . $data->user->name . ' Deleted';
        if ($data) {
            $data->forceDelete();
        }
        Log::info('Delete permanent data payment ' . $id . ' success | User:' . auth()->user()->name . '(ID:' . auth()->user()->id . ')');
    }

}
