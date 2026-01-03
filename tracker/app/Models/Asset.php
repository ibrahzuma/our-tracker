<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'value',
        'valuation_date',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
