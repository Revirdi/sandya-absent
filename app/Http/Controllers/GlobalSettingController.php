<?php

namespace App\Http\Controllers;

use App\Models\GlobalSetting;
use Illuminate\Http\Request;

class GlobalSettingController extends Controller
{
    public function index()
    {
        $settings = GlobalSetting::all();
        return view('globals.index', compact('settings'));
    }

    public function create()
    {
        return view('globals.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:global,name',
            'value' => 'nullable|string',
        ]);

        GlobalSetting::create($data);

        return redirect()->route('globals.index')->with('success', 'Setting created successfully.');
    }

    public function edit(GlobalSetting $global)
    {
        return view('globals.edit', compact('global'));
    }

    public function update(Request $request, GlobalSetting $global)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:global,name,' . $global->id,
            'value' => 'nullable|string',
        ]);

        $global->update($data);

        return redirect()->route('globals.index')->with('success', 'Setting updated successfully.');
    }

    public function destroy(GlobalSetting $global)
    {
        $global->delete();

        return redirect()->route('globals.index')->with('success', 'Setting deleted successfully.');
    }
    public function show(GlobalSetting $global)
    {
        return redirect()->route('globals.index');
    }

    // Get single setting by name in JSON
    public function getByName($name)
    {
        $setting = GlobalSetting::where('name', $name)->first();

        return response()->json([
            'name' => $name,
            'value' => $setting?->value,
        ]);
    }
}


