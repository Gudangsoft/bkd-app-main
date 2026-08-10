<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Payment;
use App\Models\Rekening;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'total_data' => Payment::all()->count(),
            'total_amount' => Payment::all()->sum('amount'),
            'complete' => Payment::where('status_accessor_one', 3)->where('status_accessor_two', 3)->get()->count(),
            'rejected' => Payment::where('status_accessor_one', 2)->where('status_accessor_two', 2)->get()->count(),
            'pending'  => Payment::where('status_accessor_one', 1)->where('status_accessor_two', 1)->get()->count(),
        ];

        if (isset(auth()->user()->id) && auth()->user()->getRoleNames()[0] != 'admin') {
            $uid = auth()->user()->id;
        } else {
            $uid = null;
        }

        $rekening_admin = User::role('admin')->get()->pluck('id');

        return view('home', [
            'users' => User::role('dosen')->where('is_active', true)->get(),
            'assessor' => User::role('asesor')->where('is_active', true)->get(),
            'rekening' => Rekening::whereIn('user_id', $rekening_admin)->where('status', true)->get(),
            'data' => $data,
            'uid' => $uid,
            'ad_sliders' => Ad::active()->type(Ad::TYPE_HOME_SLIDER)->latest()->get(),
            'ad_popup' => Ad::active()->type(Ad::TYPE_HOME_POPUP)->latest()->first(),
        ]);
    }
}
