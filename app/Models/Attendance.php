<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'location_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'status',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(AttendanceLocation::class);
    }
}
