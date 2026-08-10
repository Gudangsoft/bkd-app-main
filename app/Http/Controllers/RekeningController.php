<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RekeningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.data.rekening.index', [
            'users' => User::where('is_active', true)->get(),
            'rekening_check' => Rekening::where('user_id', auth()->user()->id)->first(),
        ]);
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
        $request->validate([
            'nomor_rekening' => 'required|unique:rekenings,nomor_rekening|numeric|min:0',
        ]);

        try {
            Rekening::create([
                'user_id' => $request->user_id,
                'nama_nasabah' => $request->nama_nasabah,
                'nomor_rekening' => $request->nomor_rekening,
                'jenis_rekening' => $request->jenis_rekening,
                'saldo' => 0,
                'status' => 1,
            ]);

            Alert::success('Berhasil', 'Data rekening baru berhasil ditambahkan');
            return back();
        } catch(Exception $e){
            Alert::error('Invalid', $e->getMessage());
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
        //
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
        try {
            Rekening::find($id)->update([
                'user_id' => $request->user_id,
                'nama_nasabah' => $request->nama_nasabah,
                'nomor_rekening' => $request->nomor_rekening,
                'jenis_rekening' => $request->jenis_rekening,
            ]);

            Alert::success('Berhasil', 'Data rekening baru berhasil update');
            return back();
        } catch(Exception $e){
            Alert::error('Invalid', $e->getMessage());
            return back();
        }
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
