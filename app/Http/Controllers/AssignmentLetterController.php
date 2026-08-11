<?php

namespace App\Http\Controllers;

use App\Models\AssignmentLetter;
use App\Models\Payment;
use App\Models\Rekening;
use App\Models\User;
use App\Services\FileServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class AssignmentLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dosen payment successfully
        $dosen_id = Payment::where('status', 1)->pluck('user_id');

        return view('admin.data.assignment-letter.index', [
            'users' => User::where('is_active', true)->role('asesor')->get(),
            'dosen' => User::whereIn('id', $dosen_id)->where('is_active', true)->role('dosen')->get(),
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
        $file_surat = '';
        if($request->file('file_surat')){
            $validate = $request->validate([
                'file_surat' => 'max:5000',
            ]);

            $data = [
            'file' => $request->file('file_surat'),
            'path' => public_path('storage/file/'),
            'modul' => 'image'
            ];
            $upload = FileServices::uploadFile($data);

            if($upload['status'] == true){
                $file_surat = $upload['name'];
            }else{
                Alert::error('Failed', 'Gagal upload file surat');
                return back();
            }
        }

        // ambil id pembayran dosen
        $payment_dosen = Payment::where('user_id', $request->dosen_id)->first();

        try {
            $save = new AssignmentLetter();
            $save->payment_id = $payment_dosen->id;
            $save->assessor_id = $request->assessor_id;
            $save->dosen_id = $request->dosen_id;
            if($file_surat){
                $save->file = $file_surat;
            }
            $save->url = $request->url;
            $save->description = $request->description;
            $save->created_by = auth()->user()->id;
            $save->status = 0;
            $save->save();

            Alert::success('Berhasil', 'Surat tugas berhasil ditambahkan.');
            return back();

        } catch (Exception $error) {
            // dd($error->getMessage());
            Alert::error('Error', $error->getMessage());
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
        $dosen_id = Payment::where('assessor_one_id', $id)->orWhere('assessor_two_id', $id)->pluck('user_id');

        $data = [
            'dosen' => User::whereIn('id', $dosen_id)->role('dosen')->get(),
        ];

        return json_encode($data);
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
        $file_surat = '';
        if($request->file('file_surat')){
            $validate = $request->validate([
                'file_surat' => 'max:5000',
            ]);

            $data = [
            'file' => $request->file('file_surat'),
            'path' => public_path('storage/file/'),
            'modul' => 'image'
            ];
            $upload = FileServices::uploadFile($data);

            if($upload['status'] == true){
                $file_surat = $upload['name'];
            }else{
                Alert::error('Failed', 'Gagal upload file surat');
                return back();
            }
        }

        try {
            $save = AssignmentLetter::find($id);
            $save->assessor_id = $request->assessor_id;
            $save->dosen_id = $request->dosen_id;
            if($file_surat){
                $save->file = $file_surat;
            }
            $save->url = $request->url;
            $save->description = $request->description;
            $save->created_by = auth()->user()->id;
            $save->save();

            Alert::success('Berhasil', 'Surat tugas berhasil diupdate.');
            return back();

        } catch (Exception $error) {
            Alert::error('Error', $error->getMessage());
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

    public function assignmentLetterAssessor()
    {
        return view('admin.data.assessor.assignment-letter.list-assignment-letter');
    }
}
