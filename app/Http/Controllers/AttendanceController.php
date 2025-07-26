<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceLocation;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{

    public function showCheckin()
    {
        $user = Auth::user();
        $today = now()->toDateString(); // format: YYYY-MM-DD

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        return view('checkin', ['attendance' => $attendance]);
    }
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
        $attendances = Attendance::with(['user', 'location']) 
            ->orderBy('attendance_date', 'desc')
            ->paginate(10);
        // dd($attendances);
        return view('attendances.index', compact('attendances'));
    }

    public function userAttendance()
    {
        $userId = auth()->id();
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // Ambil semua data attendances user bulan ini
        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('attendance_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy('attendance_date');

        // Ambil semua tanggal libur dari tabel holidays
        $holidays = Holiday::whereBetween('holiday_date', [$startDate, $endDate])
            ->get()
            ->keyBy(fn($holiday) => Carbon::parse($holiday->holiday_date)->toDateString());

        // Buat array semua tanggal dari 1–31 (atau sesuai bulan)
        $daysInMonth = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dateString = $date->toDateString();
            $workingMinutes = null;

            if (isset($attendances[$dateString])) {
                $checkIn = $attendances[$dateString]->check_in_time ?? null;
                $checkOut = $attendances[$dateString]->check_out_time ?? null;

                if ($checkIn && $checkOut) {
                    $workingMinutes = Carbon::parse($checkIn)->diffInMinutes(Carbon::parse($checkOut), false);
                }
            }

            // Cek apakah hari ini libur
            $holiday = $holidays[$dateString] ?? null;

            $daysInMonth[] = [
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->translatedFormat('l'),
                'is_weekend' => $date->isWeekend(),
                'is_holiday' => $holiday ? [
                    'status' => true,
                    'name' => $holiday->name,
                ] : [
                    'status' => false,
                    'name' => null,
                ],
                'attendance' => $attendances[$dateString] ?? null,
                'working_minutes' => $workingMinutes !== null ? floor($workingMinutes) : null,
            ];
        }

        // Pagination manual
        $page = request()->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $items = collect($daysInMonth);
        $pagedData = new LengthAwarePaginator(
            $items->slice($offset, $perPage)->values(), // data yang ditampilkan
            $items->count(),                            // total semua data
            $perPage,                                   // per halaman
            $page,                                      // halaman saat ini
            ['path' => request()->url(), 'query' => request()->query()] // biar pagination link jalan
        );

        return view('attendances.user', ['daysInMonth' => $pagedData]);
    }

    public function edit($id)
    {
        $attendance = Attendance::with(['user', 'location'])->findOrFail($id);
        $attendanceLocations = AttendanceLocation::all(); // atau bisa pakai orderBy, whereActive, dll

        return view('attendances.edit', compact('attendance', 'attendanceLocations'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'check_in_time' => 'nullable',
            'check_out_time' => 'nullable',
            'status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $attendance->update($request->all());

        return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully.');
    }
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Attendance record deleted successfully.');
    }

    public function exportPdf()
    {
        $userId = auth()->id();
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        // Ambil data attendance dan ubah key-nya jadi toDateString()
        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('attendance_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->attendance_date)->toDateString());

        // Ambil semua tanggal libur beserta nama liburnya
        $holidays = Holiday::whereBetween('holiday_date', [$startDate, $endDate])
            ->get()
            ->keyBy(fn($holiday) => Carbon::parse($holiday->holiday_date)->toDateString());

        // Loop semua tanggal dalam sebulan
        $daysInMonth = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dateString = $date->toDateString();
            $workingMinutes = null;

            if (isset($attendances[$dateString])) {
                $checkIn = $attendances[$dateString]->check_in_time ?? null;
                $checkOut = $attendances[$dateString]->check_out_time ?? null;

                if ($checkIn && $checkOut) {
                    $workingMinutes = Carbon::parse($checkIn)->diffInMinutes(Carbon::parse($checkOut), false);
                }
            }

            // Cek apakah hari ini libur
            $holiday = $holidays[$dateString] ?? null;

            $daysInMonth[] = [
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->translatedFormat('l'),
                'is_weekend' => $date->isWeekend(),
                'is_holiday' => $holiday ? [
                    'status' => true,
                    'name' => $holiday->name,
                ] : [
                    'status' => false,
                    'name' => null,
                ],
                'attendance' => $attendances[$dateString] ?? null,
                'working_minutes' => $workingMinutes !== null ? floor($workingMinutes) : null,
            ];
        }
        // dd($daysInMonth);
        $pdf = Pdf::loadView('attendances.pdf', compact('daysInMonth'));
        return $pdf->download('attendance-' . now()->format('Y-m') . '.pdf');
    }

}
// dd($daysInMonth);
// // Load ke PDF view
// $pdf = Pdf::loadView('attendances.pdf', compact('daysInMonth'));
// return $pdf->download('attendance-' . now()->format('Y-m') . '.pdf');
// return view('attendances.pdf', [
//     'daysInMonth' => $daysInMonth,
// ]);
