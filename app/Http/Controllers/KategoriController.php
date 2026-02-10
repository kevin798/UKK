<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriRequest;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();
        return view("admin.kategori.index", compact("kategori"));   
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.kategori.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KategoriRequest $request)
    {
        Kategori::create($request->validated());
        return redirect()->route('kategori.index')->with("success", "Kategori berhasil ditambahkan");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view("admin.kategori.edit", compact("kategori"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KategoriRequest $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->validated());
        return redirect()->route('kategori.index')->with("success", "Kategori berhasil diupdate");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        
        // Check if any alat uses this kategori
        if ($kategori->alats()->exists()) {
            return redirect()->route('kategori.index')->with("error", "Kategori masih digunakan di alat");
        }
        
        $kategori->delete();
        return redirect()->route('kategori.index')->with("success", "Kategori berhasil dihapus");
    }
}
