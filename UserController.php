<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function home() {
        return view('home');
    }

    public function login() {
        return view('index');
    }
    
    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    // CRUD Methods

    public function index() {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create() {
        return view('users.create');
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'Aira',
            'email' => 'humairaputrimaill@gmail.com',
            'password' => 'humaira11',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user) {
        return view('users.show', compact('user'));
    }

    public function edit(User $user) {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $validatedData = $request->validate([
            'name' => 'Aira',
            'email' => 'humairaputrimaill@gmail.com' . $user->id,
            'password' => 'humaira11',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function showProfile() {
        $user = Auth::user(); // Mendapatkan informasi pengguna yang sedang login
        return view('users.profile', compact('user')); // Mengirimkan data ke tampilan
    }
}

