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
    public function getLocations()
    {
        $locations = AttendanceLocation::all();

        return response()->json([
            'status' => 'success',
            'data' => $locations
        ]);
    }
    public function compare(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        if (!$lat || !$lng) {
            return response()->json([
                'error' => 'lat and lng are required.'
            ], 422);
        }

        $locations = AttendanceLocation::all();
        $nearest = null;
        $shortest = INF;
        $comparison = [];

        foreach ($locations as $loc) {
            // Haversine time
            $start1 = microtime(true);
            $haversine = $this->haversine($lat, $lng, $loc->latitude, $loc->longitude);
            $timeHaversine = (microtime(true) - $start1) * 1000;

            // Equirectangular time
            $start2 = microtime(true);
            $equirect = $this->equirectangular($lat, $lng, $loc->latitude, $loc->longitude);
            $timeEquirect = (microtime(true) - $start2) * 1000;

            $diff = abs($haversine - $equirect);

            $comparison[] = [
                'id' => $loc->id,
                'location_name' => $loc->location_name,
                'latitude' => $loc->latitude,
                'longitude' => $loc->longitude,
                'haversine_km' => round($haversine, 6),
                'equirect_km' => round($equirect, 6),
                'difference_km' => round($diff, 6),
                'time_haversine_ms' => round($timeHaversine, 6),
                'time_equirect_ms' => round($timeEquirect, 6),
                'user_lat' => $lat,
                'user_lng' => $lng
            ];

            if ($haversine < $shortest) {
                $shortest = $haversine;
                $nearest = $loc;
                $nearest->distance_km = round($haversine, 6);
            }
        }

        usort($comparison, function ($a, $b) {
            return $a['haversine_km'] <=> $b['haversine_km'];
        });

        return response()->json([
            'status' => 'success',
            'comparison' => $comparison,
            'nearest' => $nearest
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

    private function equirectangular($lat1, $lng1, $lat2, $lng2)
    {
    $earthRadius = 6371; // in kilometers

    $lat1 = deg2rad($lat1);
    $lat2 = deg2rad($lat2);
    $lng1 = deg2rad($lng1);
    $lng2 = deg2rad($lng2);

    $x = ($lng2 - $lng1) * cos(($lat1 + $lat2) / 2);
    $y = $lat2 - $lat1;
    $distance = sqrt($x * $x + $y * $y) * $earthRadius;

    return $distance;
    }
}
