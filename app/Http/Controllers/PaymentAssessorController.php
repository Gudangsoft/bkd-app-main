<?php

namespace App\Http\Controllers;

use App\Models\PayementAssessor;
use App\Models\Rekening;
use App\Services\ImageServices;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PaymentAssessorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);

        // find rekening
        $rekening = Rekening::findOrFail($request->rekening_id);
        if ($rekening->saldo >= $request->amount) {
            $rekening->saldo = $rekening->saldo - $request->amount;
            $rekening->save();

            if ($request->file('bukti_bayar')) {
                $validate = $request->validate([
                    'bukti_bayar' => 'image|mimes:jpeg,png,jpg,gif|dimensions:max_width=1500,max_height:1500|max:2024',
                ]);

                // create directory
                if (!file_exists(public_path('storage/images/payment_assessor/proof_of_payment'))) {
                    mkdir(public_path('storage/images/payment_assessor/proof_of_payment'), 0755, true);
                }

                $data = [
                    'file' => $request->file('bukti_bayar'),
                    'path' => public_path('storage/images/payment_assessor/proof_of_payment/'),
                    'modul' => 'image'
                ];
                $upload = ImageServices::uploadImage($data);
                if ($upload['status'] == true) {
                    $image = $upload['name'];
                } else {
                    Alert::error('Failed', 'Gagal upload file bukti bayar');
                    return back();
                }
            }

            try {

                $save = new PayementAssessor();
                $save->rekening_id = $request->rekening_id;
                $save->assessor_id = $request->assessor_id;
                $save->amount = $request->amount;
                if($image){
                    $save->proof_of_payment = $image;
                }
                $save->description = $request->description;
                $save->created_by = auth()->user()->id;
                $save->status = 1;
                $save->save();

                Alert::success('Berhasil', 'Pembayaran berhasil.');
                return back();
            } catch (Exception $e) {
                Alert::error('Error', $e->getMessage());
                return back();
            }
        } else {
            Alert::warning('Peringatan', 'Saldo rekening tidak mencukupi.');
            return back();
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.data.finance.assessor.history', [
            'data' => PayementAssessor::where('assessor_id', $id)->paginate(10)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
