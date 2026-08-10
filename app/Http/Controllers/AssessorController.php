<?php

namespace App\Http\Controllers;

use App\Models\FinanceAssessor;
use App\Models\PayementAssessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessorController extends Controller
{
    public function dataDosenAssessor()
    {
        return view('admin.data.assessor.data-dosen.index');
    }

    public function assetKeuangan()
    {
        $data_finance = FinanceAssessor::select('user_id',  DB::raw('SUM(amount) as total'))
            ->where('user_id', auth()->user()->id)
            ->groupBy('user_id')
            ->where('status', 1)
            ->orderBy('total', 'desc')
            ->get();
        // dd($data_finance);
        return view('admin.data.assessor.asset-finance.index', [
            'data_finance' => $data_finance->isEmpty() ? $data_finance : $data_finance[0],
            'data' => PayementAssessor::where('assessor_id', auth()->user()->id)->orderByDesc('created_at')->paginate(10)
        ]);
    }
}
