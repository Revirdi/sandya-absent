<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLocation;
use Illuminate\Http\Request;

class AttendanceLocationController extends Controller
{
    public function index()
    {
        $locations = AttendanceLocation::all();
        return view('attendance_locations.index', compact('locations'));
    }

    public function create()
    {
        return view('attendance_locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:100',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        AttendanceLocation::create($request->all());

        return redirect()->route('attendance-location.index')->with('success', 'Location created successfully.');
    }

    public function show(AttendanceLocation $attendance_location)
    {
        return view('attendance_locations.show', compact('attendance_location'));
    }

    public function edit(AttendanceLocation $attendance_location)
    {
        return view('attendance_locations.edit', compact('attendance_location'));
    }

    public function update(Request $request, AttendanceLocation $attendance_location)
    {
        $request->validate([
            'location_name' => 'required|string|max:100',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $attendance_location->update($request->all());

        return redirect()->route('attendance-location.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(AttendanceLocation $attendance_location)
    {
        $attendance_location->delete();
        return redirect()->route('attendance-location.index')->with('success', 'Location deleted successfully.');
    }
}
