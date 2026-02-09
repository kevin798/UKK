<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $role = $user->role ?? 'user';

        // Gunakan view sesuai role
        $viewName = match($role) {
            'admin' => 'admin.profile.edit',
            'petugas' => 'petugas.profile.edit',
            default => 'profile.edit',
        };

        return view($viewName, compact('user', 'role'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $role = $user->role ?? 'user';

        $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'nullable|string|max:20',
            'address'=> 'nullable|string',
            'photo'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')
                            ->store('profile', 'public');
            $user->photo = $path;
        }

        $user->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        // Redirect ke dashboard masing-masing role
        $redirectRoute = match($role) {
            'admin' => 'admin.dashboard',
            'petugas' => 'petugas.dashboard',
            default => 'user.dashboard',
        };

        return redirect()
            ->route($redirectRoute)
            ->with('success', 'Profile berhasil diperbarui');
    }
}
