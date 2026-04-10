<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPembayaran;

class JenisPembayaranController extends Controller
{
    // tampilkan data
    public function index()
    {
        $data = JenisPembayaran::all();
        return view('admin.jenis_pembayaran.index', compact('data'));
    }

    // form tambah
    public function create()
    {
        return view('admin.jenis_pembayaran.create');
    }

    // simpan
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nominal' => 'required|numeric|min:0'
        ]);

        JenisPembayaran::create($request->all());

        return redirect()->route('admin.jenis-pembayaran.index')
    ->with('success', 'Jenis pembayaran ditambahkan');
    }

    // form edit
    public function edit($id)
    {
        $data = JenisPembayaran::findOrFail($id);
        // Pastikan redirect setelah store/update tetap ke index
        return redirect()->route('admin.jenis-pembayaran.index')
        ->with('success', 'Data berhasil diproses');
    }

    // update
    public function update(Request $request, $id)
    {
        $data = JenisPembayaran::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'nominal' => 'required|numeric|min:0'
        ]);

        $data->update($request->all());

        return redirect()->route('admin.jenis-pembayaran.index')
        ->with('success', 'Data berhasil diproses');
    }

    // delete
    public function destroy($id)
    {
        JenisPembayaran::findOrFail($id)->delete();

        return back()->with('success', 'Data dihapus');
    }
}