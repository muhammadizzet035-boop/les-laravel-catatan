<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kontak;

class KontakController extends Controller
{
    // Menampilkan daftar kontak
    public function index()
    {
        $kontak = Kontak::all(); // Mengambil semua data kontak dari database
        return view('kontak.index', compact('kontak'));
    }

    // Menampilkan form tambah kontak
    public function create()
    {
        return view('kontak.create');
    }

    // Menyimpan data ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'email' => 'required|email|unique:kontak',
            'pesan' => 'required'
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'nama.unique' => 'Email sudah terdaftar.',
            'pesan.required' => 'Pesan tidak boleh kosong.',
        ]);

        Kontak::create($request->only(['nama', 'email', 'pesan']));

        return redirect('/kontak')->with('sukses', 'Pesan berhasil dikirim!');
    }

    public function show($id)
    {
        $kontak = Kontak::findOrFail($id);
        return view('kontak.show', compact('kontak'));
    }

    public function edit($id)
    {
        $kontak = Kontak::findOrFail($id);
        return view('kontak.edit', compact('kontak'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'email' => 'required|email|unique:kontak,email,' . $id,
            'pesan' => 'required'
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'nama.unique' => 'Email sudah terdaftar.',
            'pesan.required' => 'Pesan tidak boleh kosong.',
        ]);

        $kontak = Kontak::findOrFail($id);
        $kontak->update($request->only(['nama', 'email', 'pesan']));
        return redirect("/kontak/{$id}")->with('sukses', 'Kontak berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kontak = Kontak::findOrFail($id);

        $kontak->delete();

        return redirect('/kontak')->with('sukses', 'Kontak berhasil dihapus!');
    }
}
