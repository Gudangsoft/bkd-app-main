<?php

namespace App\Http\Controllers;

use App\Models\AssessorFee;
use App\Models\FinanceAssessor;
use App\Models\Payment;
use App\Models\Rekening;
use App\Models\User;
use App\Services\ImageServices;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
use RealRashid\SweetAlert\Facades\Alert;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // dd(Payment::where('assessor_two_id', 116)->get()->sum('amount_two'));
        // dd(Payment::where('assessor_one_id', 116)->get()->sum('amount_one'));
        $data = [
            'total_data' => Payment::all()->count(),
            'total_amount' => Payment::all()->sum('amount'),
            'complete' => Payment::where('status_accessor_one', 3)->where('status_accessor_two', 3)->get()->count(),
            'rejected' => Payment::where('status_accessor_one', 2)->where('status_accessor_two', 2)->get()->count(),
            'pending' => Payment::where('status_accessor_one', 1)->where('status_accessor_two', 1)->get()->count(),
        ];

        if (auth()->user()->getRoleNames()[0] != 'admin') {
            $uid = auth()->user()->id;
        } else {
            $uid = null;
        }

        $rekening_admin = User::role('admin')->get()->pluck('id');

        return view('admin.payments.index', [
            'users' => User::role('dosen')->where('is_active', true)->get(),
            'assessor' => User::role('asesor')->where('is_active', true)->get(),
            'rekening' => Rekening::whereIn('user_id', $rekening_admin)->where('status', true)->get(),
            'data' => $data,
            'uid' => $uid,
        ]);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        // dd($request);
        $image = '';
        if ($request->file('image')) {
            $validate = $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|dimensions:max_width=1500,max_height:1500|max:2024',
            ]);

            $data = [
                'file' => $request->file('image'),
                'path' => public_path('storage/images/proof_of_payment/'),
                'modul' => 'image'
            ];
            $upload = ImageServices::uploadImage($data);
            if ($upload['status'] == true) {
                $image = $upload['name'];
            } else {
                Alert::error('Failed', 'Gagal upload file photo');
                return back();
            }
        }

        try {
            foreach ($request->assessor_one as $value) {
                FinanceAssessor::create([
                    'dosen_id' => $request->user_list[0],
                    'dosen_status' => User::find($request->user_list[0])->assessor_fee,
                    'user_id' => $value,
                    'amount' => $request->bea_asesor_one,
                    'assessor_of' => 1,
                    'status' => 0,
                ]);
            }

            foreach ($request->assessor_two as $value_two) {
                FinanceAssessor::create([
                    'dosen_id' => $request->user_list[0],
                    'dosen_status' => User::find($request->user_list[0])->assessor_fee,
                    'user_id' => $value_two,
                    'amount' => $request->bea_asesor_two,
                    'assessor_of' => 2,
                    'status' => 0,
                ]);
            }

            $save = new Payment();
            $save->user_id = $request->user_list[0];
            $save->rekening_id = $request->rekening_id;
            $save->assessor_one_id = $request->assessor_one[0];
            $save->assessor_two_id = $request->assessor_two[0];
            $save->amount_one = $request->bea_asesor_one;
            $save->amount_two = $request->bea_asesor_two;
            $save->amount = $request->amount;
            if ($image) {
                $save->proof_of_payment = $image;
            }
            $save->description = $request->description;
            $save->status_accessor_one = 1;
            $save->status_accessor_two = 1;
            $save->save();

            if ($save) {
                $update_saldo = Rekening::find($request->rekening_id);
                $update_saldo->saldo = $update_saldo->saldo + $request->amount;
                $update_saldo->save();
            }

            Alert::success('Success', Lang::get('dashboard.payment.add_success'));
            return back();
        } catch (Exception $error) {
            dd($error->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $image = '';
        if ($request->file('image')) {
            $validate = $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
            ]);

            $data = [
                'file' => $request->file('image'),
                'path' => public_path('storage/images/proof_of_payment/'),
                'modul' => 'image'
            ];
            $upload = ImageServices::uploadImage($data);
            if ($upload['status'] == true) {
                $image = $upload['name'];
            } else {
                Alert::error('Failed', 'Gagal upload file photo');
                return back();
            }
        }

        try {
            $save = Payment::find($id);
            $save->rekening_id = $request->rekening_id;
            // $save->assessor_one_id = $request->assessor_one[0];
            // $save->assessor_two_id = $request->assessor_two[0];
            // $save->amount = $request->amount;
            if ($image) {
                $save->proof_of_payment = $image;
            }
            $save->description = $request->description;
            $save->save();

            Alert::success('Success', Lang::get('dashboard.payment.edit_success'));
            return back();
        } catch (Exception $error) {
            dd($error->getMessage());
        }
    }

    public function findAssessorOne($aid, $did)
    {
        $assessor = User::find($aid);
        $dosen = User::find($did);
        $beaAssessor = AssessorFee::select('id', 'name', $assessor->status)->where('id', $dosen->assessor_fee)->get();
        $data = [
            'assessor_one_status' => $assessor->status == 'external_dif' ? 'external non kepanitiaan' : $assessor->status,
            'serdos_status' => $beaAssessor[0]['name'],
            'amount_assessor_one' => (int) $beaAssessor[0][$assessor->status],
        ];
        return json_encode($data);
    }

    public function findAssessorTwo($aid, $did)
    {
        $assessor = User::find($aid);
        $dosen = User::find($did);
        $beaAssessor = AssessorFee::select('id', 'name', $assessor->status)->where('id', $dosen->assessor_fee)->get();
        $data = [
            'assessor_two_status' => $assessor->status == 'external_dif' ? 'external non kepanitiaan' : $assessor->status,
            // 'serdos_status' => $beaAssessor[0]['name'],
            'amount_assessor_two' => (int) $beaAssessor[0][$assessor->status],
        ];
        return json_encode($data);
    }

    public function invoice($id)
    {
        $payment = Payment::find($id);

        return view('admin.payments.invoice', compact('payment'));
    }

    public function downloadInvoice($id)
    {
        $payment = Payment::find($id);
        $data = [
            'date' => $payment->date,
            'name' => $payment->user->name,
            'progdi' => $payment->user->progdi,
            'assessor_one' => $payment->assessorOne->name,
            'assessor_two' => $payment->assessorTwo->name,
            'amount_one' => $payment->amount_one,
            'amount_two' => $payment->amount_two,
            'amount' => $payment->amount,
            'image' => $payment->image,

        ];
        // dd($data['date']);
        $pdf = Pdf::loadView('admin.payments.invoice-download', ['data' => $data]);
        return $pdf->download('invoice' . time() . '.pdf');
        // return view('admin.payments.invoice-download', compact('payment'));
    }

    public function restore()
    {
        return view('admin.payments.restore');
    }
}
