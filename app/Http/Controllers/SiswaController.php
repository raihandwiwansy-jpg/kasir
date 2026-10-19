<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswas = Siswa::query()
            ->when(request('search'), function ($query) {
                $search = request('search');
                $query->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('jurusan', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('siswas.index', compact('siswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('siswas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nisn' => 'required|string|max:255|unique:siswas',
            'jurusan' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        Siswa::create($validatedData);

        return redirect()->route('siswas.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('siswas.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('siswas.edit', compact('siswa'));      
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validatedData = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nisn' => 'required|string|max:255|unique:siswas,nisn,' . $siswa->id,
            'jurusan' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $siswa->update($validatedData);

        return redirect()->route('siswas.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('siswas.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
