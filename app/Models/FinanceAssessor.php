<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class FinanceAssessor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function getAssessorFee()
    {
        return $this->belongsTo(AssessorFee::class, 'dosen_status');
    }

    public function getSaldoTotalAttribute()
    {
        $data = FinanceAssessor::where('user_id', $this->user_id)->where('status', 1)->get()->sum('amount');
        // dd($data);
        return $data;
    }

    public function getSaldoTakenAttribute()
    {
        $data = PayementAssessor::where('assessor_id', $this->user_id)->get()->sum('amount');
        return $data;
    }

    public function getAssessorStatusAttribute()
    {
        // dd($this->getUser->assessor_fee);
        $status = $this->getUser->status;
        return $status;
    }

    public function getCountDosenSerdosAttribute()
    {
        $data = FinanceAssessor::where('user_id', $this->user_id)->where('dosen_status', 1)->where('status', 1)->get()->count();

        return $data;

    }

    public function getCountDosenNonSerdosAttribute()
    {
        $data = FinanceAssessor::where('user_id', $this->user_id)->where('dosen_status', 2)->where('status', 1)->get()->count();

        return $data;

    }

    // public function getNominalSerdosAttribute()
    // {
    //     if($this->getUser->internal
    // }

}
