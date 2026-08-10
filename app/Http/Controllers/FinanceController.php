<?php

namespace App\Http\Controllers;

use App\Models\FinanceAssessor;
use App\Models\Rekening;
use App\Models\User;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->category == 'dosen') {
            if (auth()->user()->getRoleNames()[0] != 'admin') {
                $uid = auth()->user()->id;
            } else {
                $uid = null;
            }

            return view('admin.data.finance.index', [
                'users' => User::role('dosen')->where('is_active', true)->get(),
                'assessor' => User::role('asesor')->where('is_active', true)->get(),
                'rekening' => Rekening::where('status', true)->get(),
                'uid' => $uid,
            ]);

        } else {
            return view('admin.data.finance.assessor.index', [
                'rekening' => Rekening::where('status', 1)->get(),
                'total_saldo' => FinanceAssessor::where('status', 1)->sum('amount'),
            ]);
        }
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
        //
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
