<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceLocation;
use App\Models\Holiday;
use App\Models\User;
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
                'status' => 'present',
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

    public function index(Request $request)
    {
        $query = Attendance::with(['user', 'location'])
            ->when($request->input('name'), function ($q) use ($request) {
                $q->whereHas('user', function ($uq) use ($request) {
                    $uq->where('name', 'like', '%' . $request->input('name') . '%');
                });
            })
            ->orderBy('attendance_date', 'desc');

        $attendances = $query->paginate(10)->withQueryString();

        return view('attendances.index', compact('attendances'));
    }
    public function userAttendance()
    {
        $userId = auth()->id();

        // Ambil parameter bulan & tahun dari request atau default ke bulan & tahun sekarang
        $month = request()->get('month', Carbon::now()->month);
        $year = request()->get('year', Carbon::now()->year);

        // Buat awal dan akhir bulan berdasarkan month & year
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Ambil semua data attendances user untuk bulan yang dipilih
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
            $items->slice($offset, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('attendances.user', [
            'daysInMonth' => $pagedData,
            'selectedMonth' => $month,
            'selectedYear' => $year,
        ]);
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

    public function exportPdf(Request $request)
    {
        $userId = auth()->id();
        $user = auth()->user();

        $month = $request->input('month', now()->month); // default: bulan sekarang
        $year = $request->input('year', now()->year);    // default: tahun sekarang

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

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
        $pdf = Pdf::loadView('attendances.pdf', compact('daysInMonth', 'user'));
        return $pdf->download('attendance-' . now()->format('Y-m') . '.pdf');
    }
    public function create()
    {
        $users = User::all();
        $attendanceLocations = AttendanceLocation::all();
        return view('attendances.create', compact('users', 'attendanceLocations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'location_id' => 'required|exists:attendance_locations,id', // kalau ada tabel locations
            'attendance_date' => 'required|date',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i|after:check_in_time',
            'status' => 'required|string|in:present,absent', // sesuaikan enum/status yang kamu pake
            'remarks' => 'nullable|string|max:255',
        ]);

        Attendance::create([
            'user_id'        => $request->user_id,
            'location_id'    => $request->location_id,
            'attendance_date'=> $request->attendance_date,
            'check_in_time'  => $request->check_in_time,
            'check_out_time' => $request->check_out_time,
            'status'         => $request->status,
            'remarks'        => $request->remarks,
        ]);

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance created successfully.');
    }
}
// dd($daysInMonth);
// // Load ke PDF view
// $pdf = Pdf::loadView('attendances.pdf', compact('daysInMonth'));
// return $pdf->download('attendance-' . now()->format('Y-m') . '.pdf');
// return view('attendances.pdf', [
//     'daysInMonth' => $daysInMonth,
// ]);
