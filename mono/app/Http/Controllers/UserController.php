<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Display a listing of the users.
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    // Show the form for creating a new user.
    public function create()
    {
        return view('user.create');
    }

    // Store a newly created user in storage.
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'status' => 'required',
            'foto' => 'nullable|image',
            'fototrain' => 'nullable|file', // Validation for fototrain
        ]);

        $user = new User($request->all());

        if ($request->hasFile('foto')) {
            $filename = $request->nama . '_' . time() . '.' . $request->file('foto')->getClientOriginalExtension();
            $path = $request->file('foto')->storeAs('fotos', $filename, 'public');
            $user->foto = $path;
        }

        if ($request->hasFile('fototrain')) {
            $filename = $request->nama . '_train_' . time() . '.' . $request->file('fototrain')->getClientOriginalExtension();
            $path = $request->file('fototrain')->storeAs('fototrains', $filename, 'public');
            $user->fototrain = $path;
        }

        $user->save();

        return redirect()->route('user.index');
    }

    // Display the specified user.
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    // Show the form for editing the specified user.
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    // Update the specified user in storage.
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required',
            'status' => 'required',
            'foto' => 'nullable|image',
            'fototrain' => 'nullable|file', // Validation for fototrain
        ]);

        $user->fill($request->all());

        if ($request->hasFile('foto')) {
            $filename = $request->nama . '_' . time() . '.' . $request->file('foto')->getClientOriginalExtension();
            $path = $request->file('foto')->storeAs('fotos', $filename, 'public');
            $user->foto = $path;
        }

        if ($request->hasFile('fototrain')) {
            $filename = $request->nama . '_train_' . time() . '.' . $request->file('fototrain')->getClientOriginalExtension();
            $path = $request->file('fototrain')->storeAs('fototrains', $filename, 'public');
            $user->fototrain = $path;
        }

        $user->save();

        return redirect()->route('user.index');
    }

    // Remove the specified user from storage.
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index');
    }
}

