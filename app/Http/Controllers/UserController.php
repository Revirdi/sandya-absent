<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
public function index(): View
{
    $users = User::paginate(10); // pagination 10 per halaman
    return view('users.index', compact('users'));
}

    /**
 * Show the form for creating a new user.
 */
public function create(): View
{
    return view('users.create');
}

/**
 * Store a newly created user in storage.
 */
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:50'],
        'position' => ['nullable', 'string', 'max:50'],
        'departmen' => ['nullable', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:150', 'unique:users,email'],
        'phone' => ['nullable', 'string', 'max:20'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role' => ['required', 'in:admin,employee'],
        'status' => ['required', 'in:active,inactive'],
    ]);

    User::create([
        'name' => $request->name,
        'position' => $request->position,
        'departmen' => $request->departmen,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'status' => $request->status,
    ]);

    return redirect()->route('users.index')->with('success', 'User created successfully.');
}


    /**
     * Show the form for editing a specific user.
     */
public function edit(string $id): View
{
    $user = User::findOrFail($id);
    return view('users.edit', compact('user'));
}

    /**
     * Update a user's information.
     */
public function update(Request $request, string $id): RedirectResponse
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => ['required', 'string', 'max:50'],
        'position' => ['nullable', 'string', 'max:50'],
        'departmen' => ['nullable', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
        'phone' => ['nullable', 'string', 'max:20'],
        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        'role' => ['required', 'in:admin,employee'],
        'status' => ['required', 'in:active,inactive'],
    ]);

    $data = $request->only([
        'name', 'position', 'departmen', 'email', 'phone', 'role', 'status'
    ]);

    // Jika password diisi, encrypt dan update
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}


    /**
     * Remove the specified user.
     */
    public function destroy(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
