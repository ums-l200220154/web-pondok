<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

use App\Imports\SantriImport;
use Maatwebsite\Excel\Facades\Excel;

class SantriController extends Controller
{
    // TAMPILKAN DAFTAR
    public function index(Request $request)
{
    $search = $request->get('search');

    $santri = Santri::when($search, function($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nis', 'like', "%{$search}%");
        })
        ->orderBy('nama')
        ->paginate(15) // Ubah get() jadi paginate(15)
        ->appends(['search' => $search]);

    return view('admin.santri.index', compact('santri', 'search'));
}

    public function create()
    {
        return view('admin.santri.create'); // Pastikan path view benar
    }

    public function show($nis)
    {
        $santri = Santri::findOrFail($nis);
        return view('admin.santri.show', compact('santri'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis'           => 'required|unique:santri,nis',
            'nik'           => 'required|unique:santri,nik',
            'nama'          => 'required',
            'alamat'        => 'required',
            'tempat_lahir'  => 'required',
            'tanggal_lahir' => 'required|date',
            'nama_ayah'     => 'required',
            'no_hp'         => 'required',
        ]);

        Santri::create($request->all());

        return redirect()->route('admin.santri.index')->with('success', 'Santri berhasil ditambahkan');
    }

    public function update(Request $request, $nis)
    {
        $santri = Santri::findOrFail($nis);

        $request->validate([
            'nik'           => 'required|unique:santri,nik,' . $nis . ',nis',
            'nama'          => 'required',
            'alamat'        => 'required',
            'tempat_lahir'  => 'required',
            'tanggal_lahir' => 'required|date',
            'nama_ayah'     => 'required',
            'no_hp'         => 'required',
        ]);

        $santri->update($request->all());

        return back()->with('success', 'Data santri berhasil diperbarui');
    }

    // HAPUS
    public function destroy($nis)
    {
        Santri::findOrFail($nis)->delete();

        return redirect()->route('admin.santri.index')
            ->with('success', 'Santri berhasil dihapus');
    }

    public function importExcel(Request $request)
{
    $request->validate([
        'file_excel' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
        Excel::import(new SantriImport, $request->file('file_excel'));
        return back()->with('success', 'Data santri berhasil diimpor dari Excel!');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal mengimpor data. Pastikan format kolom sesuai.');
    }
}
}
