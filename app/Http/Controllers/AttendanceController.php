<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AttendanceController extends Controller
{
public function checkIn(Request $request): JsonResponse
{
    $user = Auth::user();
    $today = now()->toDateString();

    $attendance = Attendance::firstOrCreate(
        ['user_id' => $user->id, 'attendance_date' => $today],
        [
            'location_id' => $request->location_id ?? 1,
            'check_in_time' => now()->format('H:i:s'),
            'status' => 'hadir',
        ]
    );

    return response()->json([
        'success' => true,
        'message' => 'Check-in successful.',
        'data' => $attendance,
    ]);
}

public function checkOut(): JsonResponse
{
    $user = Auth::user();
    $today = now()->toDateString();

    $attendance = Attendance::where('user_id', $user->id)
        ->where('attendance_date', $today)
        ->first();

    if (!$attendance) {
        return response()->json([
            'success' => false,
            'message' => 'No attendance record found.',
        ], 404);
    }

    if ($attendance->check_out_time) {
        return response()->json([
            'success' => false,
            'message' => 'Already checked out.',
        ], 400);
    }

    $attendance->update(['check_out_time' => now()->format('H:i:s')]);

    return response()->json([
        'success' => true,
        'message' => 'Check-out successful.',
        'data' => $attendance,
    ]);
}

    public function index()
    {
        $attendances = Attendance::with(['user', 'location'])->latest()->paginate(20);
        return view('attendances.index', compact('attendances'));
    }

    public function userAttendance($userId)
{
    $attendances = Attendance::with('location')
        ->where('user_id', $userId)
        ->orderByDesc('attendance_date')
        ->paginate(20);

    return view('attendances.user', compact('attendances'));
}
}
