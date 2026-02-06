<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlatController extends Controller
{
    public function index()
    {
        $alat = Alat::with('kategori')->latest()->get();
        $kategoriCount = Kategori::count();
        return view('admin.alat.index', compact('alat', 'kategoriCount'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        if ($kategori->isEmpty()) {
            return redirect()->route('alat.index')
                ->with('error', 'Tidak ada kategori. Silakan tambah kategori terlebih dahulu.');
        }
        return view('admin.alat.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            Alat::create($request->all());
            DB::commit();
            return redirect()->route('alat.index')->with('success', 'Data alat berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambah data alat')->withInput();
        }
    }

    public function edit(Alat $alat)
    {
        $kategori = Kategori::all();
        return view('admin.alat.edit', compact('alat', 'kategori'));
    }

    public function update(Request $request, Alat $alat)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $alat->update($request->all());
            DB::commit();
            return redirect()->route('alat.index')->with('success', 'Data alat berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengupdate data alat')->withInput();
        }
    }

    public function destroy(Alat $alat)
    {
        DB::beginTransaction();
        try {
            $alat->delete();
            DB::commit();
            return redirect()->route('alat.index')->with('success', 'Data alat berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('alat.index')->with('error', 'Gagal menghapus data alat');
        }
    }

    public function userList(Request $request)
    {
        $kategori = Kategori::all();
        $query = Alat::with('kategori');

        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $alat = $query->latest()->get();
        return view('user.alat-list', compact('alat', 'kategori'));
    }
}

