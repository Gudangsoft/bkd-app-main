<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    protected $casts = [
            'amount' => 'integer',
        ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function getRekening()
    {
        return $this->belongsTo(Rekening::class, 'rekening_id');
    }

    public function assessorOne()
    {
        return $this->belongsTo(User::class, 'assessor_one_id')->withTrashed();
    }

    public function assessorTwo()
    {
        return $this->belongsTo(User::class, 'assessor_two_id')->withTrashed();
    }

    public function getImageAttribute()
    {   if($this->proof_of_payment == null)
        {
            $image = asset('assets/img/imagedummy.png');
        }else{
            $image = asset('/storage/images/proof_of_payment') . '/' . $this->proof_of_payment;
        }

        return $image;
    }

    public function getDateAttribute()
    {
        return Carbon::parse($this->created_at)->isoFormat('D/M/Y');
    }

    public function assignmentLetterStatusOne($uid)
    {
        $assignment_letter = AssignmentLetter::where('dosen_id', $uid)->where('assessor_id', $this->assessor_one_id)->first();

        if ($assignment_letter != null) {
            $data = [
                'status' => $assignment_letter->status,
            ];
        } else {
            $data = [
                'status' => false,
            ];
        }
        // dd($data);
        return $data;
    }

    public function assignmentLetterStatusTwo($uid)
    {
        $assignment_letter = AssignmentLetter::where('dosen_id', $uid)->where('assessor_id', $this->assessor_two_id)->first();
        // $assignment_letter = AssignmentLetter::where('dosen_id', $uid)->first();

        if ($assignment_letter != null) {
            $data = [
                'status' => $assignment_letter->status,
            ];
        } else {
            $data = [
                'status' => false,
            ];
        }
        return $data;
    }

    // public function assignmentletter()
    // {
    //     return $this->hasMany(AssignmentLetter::class, 'payment_id');
    // }

}
