<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\MakePetugasRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ================= LOGIN =================
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $candidate = User::where('email', $credentials['email'])->first();
        if ($candidate && $candidate->is_active === false) {
            return back()->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi admin.',
            ]);
        }

        if (Auth::attempt(array_merge($credentials, ['is_active' => true]))) {
            $request->session()->regenerate();

            $role = Auth::user()->role ?? 'user';

            return match ($role) {
                'admin'   => redirect()->route('admin.dashboard'),
                'petugas' => redirect()->route('petugas.dashboard'),
                default   => redirect()->route('user.dashboard'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // ================= REGISTER =================
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $payload = $request->validated();

        User::create([
            'name'     => $payload['name'],
            'email'    => $payload['email'],
            'password' => bcrypt($payload['password']),
            'phone'    => $payload['phone'] ?? null,
            'address'  => $payload['address'] ?? null,
            'role'     => 'user', // 🔥 FIX UNDEFINED ROLE
            'is_active'=> true,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ================= PETUGAS =================
    public function makePetugasForm()
    {
        return view('auth.make-petugas');
    }

    public function makePetugas(MakePetugasRequest $request)
    {
        $payload = $request->validated();

        User::create([
            'name'     => $payload['name'],
            'email'    => $payload['email'],
            'password' => bcrypt($payload['password']),
            'role'     => 'petugas',
            'is_active'=> true,
        ]);

        return back()->with('success', 'Akun petugas berhasil dibuat.');
    }

    public function makePetugasDetail($id)
    {
        $user = User::findOrFail($id);
        return view('auth.make-petugas-detail', compact('user'));
    }

    public function editPetugasForm($id)
    {
        $user = User::findOrFail($id);
        return view('auth.edit-petugas', compact('user'));
    }

    public function editPetugas(MakePetugasRequest $request, $id)
    {
        $payload = $request->validated();
        $user = User::findOrFail($id);

        $user->update([
            'name'     => $payload['name'],
            'email'    => $payload['email'],
            'password' => bcrypt($payload['password']),
        ]);

        return back()->with('success', 'Data petugas berhasil diupdate.');
    }

    public function deletePetugas($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Akun petugas berhasil dihapus.');
    }
}
