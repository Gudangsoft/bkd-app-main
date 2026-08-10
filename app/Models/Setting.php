<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function getPdfManualBookAttribute()
    {
        return asset('storage/manual_book').'/'.$this->manual_book;
    }

    public function getLoginBackgroundUrlAttribute()
    {
        if ($this->login_background) {
            return asset('storage/login_background').'/'.$this->login_background;
        }

        return asset('assets/img/background/background-blue.webp');
    }
}
