<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $guarded = [];

    const TYPE_HOME_SLIDER = 'home_slider';
    const TYPE_HOME_POPUP = 'home_popup';

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function getImagePathAttribute()
    {
        return $this->image ? asset('storage/images/ads/' . $this->image) : null;
    }
}
