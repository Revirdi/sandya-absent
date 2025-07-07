<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::latest()->paginate(10);
        return view('holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('holidays.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'holiday_date' => 'required|date',
            'name' => 'required|max:100',
            'description' => 'nullable',
        ]);

        Holiday::create($request->all());

        return redirect()->route('holidays.index')->with('success', 'Holiday created successfully.');
    }

    public function show(Holiday $holiday)
    {
        return view('holidays.show', compact('holiday'));
    }

    public function edit(Holiday $holiday)
    {
        return view('holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'holiday_date' => 'required|date',
            'name' => 'required|max:100',
            'description' => 'nullable',
        ]);

        $holiday->update($request->all());

        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday deleted successfully.');
    }
}
