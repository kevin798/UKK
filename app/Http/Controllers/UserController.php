<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\MakePetugasRequest;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /* ===================== LIST ===================== */

    public function peminjamList()
    {
        $peminjam = User::where('role', 'user')
            ->latest()
            ->paginate(10);

        return view('admin.petugas.peminjam', compact('peminjam'));
    }

    public function petugasList()
    {
        $petugas = User::where('role', 'petugas')
            ->latest()
            ->paginate(10);

        return view('admin.petugas.index', compact('petugas'));
    }

    public function userList()
    {
        $users = User::where('role', 'user')
            ->latest()
            ->get();

        return view('user.users', compact('users'));
    }

    /* ===================== CREATE ===================== */

    public function createPetugasForm()
    {
        return view('admin.petugas.create');
    }

    public function createPetugas(MakePetugasRequest $request)
    {
        $petugas = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'petugas',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Tambah Petugas',
            'description' => sprintf('Menambah petugas %s (%s)', $petugas->name, $petugas->email),
        ]);

        return redirect()
            ->route('admin.petugas')
            ->with('success', 'Petugas berhasil ditambahkan');
    }

    /* ===================== EDIT ===================== */

    public function editPetugasForm($id)
    {
        $petugas = User::findOrFail($id);

        return view('admin.petugas.edit', compact('petugas'));
    }

    public function updatePetugas(MakePetugasRequest $request, User $user)
    {
        $this->ensurePetugas($user);

        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Update Petugas',
            'description' => sprintf('Memperbarui petugas %s (%s)', $user->name, $user->email),
        ]);

        return redirect()
            ->route('admin.petugas')
            ->with('success', 'Petugas berhasil diperbarui');
    }

    /* ===================== DELETE ===================== */

    public function deletePetugas($id)
    {
        $user = User::findOrFail($id);
        $this->ensurePetugas($user);

        $user->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Hapus Petugas',
            'description' => sprintf('Menghapus petugas %s (%s)', $user->name, $user->email),
        ]);

        return redirect()
            ->route('admin.petugas')
            ->with('success', 'Petugas berhasil dihapus');
    }

    /* ===================== PEMINJAM (ADMIN) ===================== */

    public function editPeminjamForm($id)
    {
        $peminjam = User::findOrFail($id);
        $this->ensurePeminjam($peminjam);

        return view('admin.petugas.edit_peminjam', compact('peminjam'));
    }

    public function updatePeminjam(Request $request)
    {
        $id = $request->input('id');
        $user = User::findOrFail($id);
        $this->ensurePeminjam($user);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.peminjam')
            ->with('success', 'Peminjam berhasil diperbarui');
    }

    public function deletePeminjam($id)
    {
        $user = User::findOrFail($id);
        $this->ensurePeminjam($user);

        $user->delete();

        return redirect()
            ->route('admin.peminjam')
            ->with('success', 'Peminjam berhasil dihapus');
    }

    /* ===================== GUARD ===================== */

    private function ensurePetugas(User $user): void
    {
        if ($user->role !== 'petugas') {
            abort(404);
        }
    }

    private function ensurePeminjam(User $user): void
    {
        if ($user->role !== 'user') {
            abort(404);
        }
    }

    public function togglePetugas(User $user)
    {
        $this->ensurePetugas($user);
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->back()->with('success', 'Status petugas diperbarui.');
    }

    public function togglePeminjam($id)
    {
        $user = User::findOrFail($id);
        $this->ensurePeminjam($user);

        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->back()->with('success', 'Status peminjam diperbarui.');
    }
}
