<?php

namespace App\Http\Controllers;

use App\Charts\MonthlyAssessorRegistedChart;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MonthlyAssessorRegistedChart $monthly_assessor_registed_chart, Request $request)
    {
        $count = [
            'user' => User::where('is_active', true)->count(),
            'assessor' => User::where('is_active', true)->role('asesor')->count(),
            'bkd_user' => Payment::all()->count(),
            'bkd_success' => Payment::where('status_accessor_one', 3)->where('status_accessor_two', 3)->get()->count(),
            'bkd_pending' => Payment::where('status_accessor_one', 2)->where('status_accessor_two', 2)->get()->count(),
        ];
        // dd($count);
        return view('admin.index', [
            'count' => $count,
            'monthly_assessor_registed_chart' => $monthly_assessor_registed_chart->build($request->year),
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
