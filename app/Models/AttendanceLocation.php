<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLocation extends Model
{
    protected $fillable = [
        'location_name',
        'address',
        'latitude',
        'longitude',
    ];
}
