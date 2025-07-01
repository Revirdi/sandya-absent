<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceLocation;

class AttendanceLocationApiController extends Controller
{
    public function index(Request $request)
    {
        $locations = AttendanceLocation::all();

        $userLat = $request->query('lat');
        $userLng = $request->query('lng');

        $nearest = null;
        $shortestDistance = INF;

        // Kalau user lat/lng dikirim, hitung Haversine
        if ($userLat !== null && $userLng !== null) {
            foreach ($locations as $location) {
                $distance = $this->haversine($userLat, $userLng, $location->latitude, $location->longitude);
                $location->distance = $distance;

                if ($distance < $shortestDistance) {
                    $shortestDistance = $distance;
                    $nearest = $location;
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $locations,
            'nearest' => $nearest,
        ]);
    }

    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // kilometer

        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        $dLat = $lat2 - $lat1;
        $dLng = $lng2 - $lng1;

        $a = sin($dLat / 2) ** 2 +
             cos($lat1) * cos($lat2) * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
