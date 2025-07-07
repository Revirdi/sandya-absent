<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'holiday_date',
        'name',
        'description',
    ];

    protected $casts = [
    'holiday_date' => 'date',
];
}
