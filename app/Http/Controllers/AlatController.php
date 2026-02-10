<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AlatController extends Controller
{
    public function index()
    {
        $alat = Alat::with('kategori')->latest()->get();
        $kategoriCount = Kategori::count();
        return view('admin.alat.index', compact('alat', 'kategoriCount'));
    }

    public function userList(Request $request)
    {
        $query = Alat::with('kategori')->where('jumlah', '>', 0)->latest();

        // Filter berdasarkan kategori jika ada
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('kategoris.id', $request->kategori_id);
            });
        }

        $alat = $query->get();
        $kategori = Kategori::orderBy('nama')->get();

        return view('user.alat-list', compact('alat', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.alat.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|array|min:1',
            'kategori_id.*' => 'exists:kategoris,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except('kategori_id');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('alat', 'public');
        }

        $alat = Alat::create($data);
        $alat->kategori()->sync($request->kategori_id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Tambah Alat',
            'jumlah' => $alat->jumlah,
            'description' => sprintf('Menambah alat %s (stok %s)', $alat->nama, $alat->jumlah),
        ]);

        return redirect()->route('alat.index')
            ->with('success', 'Alat berhasil ditambahkan');
    }

    public function edit(Alat $alat)
    {
        $kategori = Kategori::all();
        return view('admin.alat.edit', compact('alat', 'kategori'));
    }

    public function update(Request $request, Alat $alat)
    {
        $oldJumlah = $alat->jumlah;
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|array|min:1',
            'kategori_id.*' => 'exists:kategoris,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except('kategori_id');

        if ($request->hasFile('gambar')) {
            if ($alat->gambar) {
                Storage::disk('public')->delete($alat->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('alat', 'public');
        }

        $alat->update($data);
        $alat->kategori()->sync($request->kategori_id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Update Alat',
            'jumlah' => $alat->jumlah,
            'description' => sprintf(
                'Memperbarui alat %s (stok %s → %s)',
                $alat->nama,
                $oldJumlah,
                $alat->jumlah
            ),
        ]);

        return redirect()->route('alat.index')
            ->with('success', 'Alat berhasil diperbarui');
    }

    public function destroy(Alat $alat)
    {
        $nama = $alat->nama;
        $stok = $alat->jumlah;
        if ($alat->gambar) {
            Storage::disk('public')->delete($alat->gambar);
        }

        $alat->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Hapus Alat',
            'jumlah' => $stok,
            'description' => sprintf('Menghapus alat %s (stok terakhir %s)', $nama, $stok),
        ]);

        return redirect()->route('alat.index')
            ->with('success', 'Alat berhasil dihapus');
    }

}
