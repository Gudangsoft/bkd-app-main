<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayementAssessor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getAssessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function getRekening()
    {
        return $this->belongsTo(Rekening::class, 'rekening_id');
    }

    public function getDateAttribute()
    {

        // Membuat objek Carbon dari string waktu awal
        $carbonObj = Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at, 'UTC');

        // Menyesuaikan zona waktu ke Waktu Indonesia Barat (WIB)
        $carbonObj->setTimezone('Asia/Jakarta');

        // Mengubah format waktu sesuai dengan preferensi
        $waktuIndonesia = $carbonObj->isoFormat('dddd, D MMMM Y HH:mm:ss');

        return $waktuIndonesia;
    }
}
