<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Santri;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    // ================= KEAMANAN =================

    // 🔹 ABSENSI HARIAN (SEMUA SANTRI)
    public function inputHarian()
    {
        $santri = Santri::orderBy('nama')->paginate(15);
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        // Logika Waktu Aktif sesuai permintaan:
        // Pagi: 04:00 - 15:59
        // Malam: 16:00 - 03:59 (hari berikutnya)
        $jam = $now->hour;
        $isPagiActive = ($jam >= 4 && $jam < 16);
        $isMalamActive = ($jam >= 16 || $jam < 4);

        $absensi = Absensi::where('tanggal', $today)
            ->get()
            ->keyBy(function ($item) {
                return $item->nis . '_' . $item->waktu;
            });

        return view('keamanan.absensi_harian', compact(
            'santri', 
            'absensi', 
            'today', 
            'isPagiActive', 
            'isMalamActive'
        ));
    }

    public function storeHarian(Request $request)
{
    $request->validate([
        'nis'        => 'required',
        'waktu'      => 'required|in:pagi,malam',
        'keterangan' => 'required|in:hadir,sakit,tidak hadir',
    ]);

    $now = Carbon::now('Asia/Jakarta');
    $jam = $now->hour;
    $today = Carbon::today()->toDateString();

    // --- PROTEKSI VALIDASI WAKTU ---
    if ($request->waktu == 'pagi') {
        // Jika mencoba absen pagi tapi bukan jam 04:00 - 15:59
        if (!($jam >= 4 && $jam < 16)) {
            return back()->with('error', 'Sesi absen PAGI sudah ditutup atau belum dibuka.');
        }
    }

    if ($request->waktu == 'malam') {
        // Jika mencoba absen malam tapi bukan jam 16:00 - 03:59
        if (!($jam >= 16 || $jam < 4)) {
            return back()->with('error', 'Sesi absen MALAM belum dibuka.');
        }
    }
    // --- END PROTEKSI ---

    // Simpan atau Update data
    Absensi::updateOrCreate(
        ['nis' => $request->nis, 'tanggal' => $today, 'waktu' => $request->waktu],
        ['keterangan' => $request->keterangan]
    );

    return back()->with('success', 'Absensi berhasil disimpan.');
}

    
    public function historiSantri(Request $request)
{
    $search = $request->get('search');
    
    // Menggunakan paginate(15)
    $santri = Santri::when($search, function($query) use ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
        })
        ->orderBy('nama')
        ->paginate(15);

    // Menjaga agar parameter search tidak hilang saat pindah halaman
    $santri->appends(['search' => $search]);

    return view('keamanan.absensi_histori', compact('santri', 'search'));
}

    // 🔹 HISTORI PER SANTRI
   // Tambahkan Request $request di parameter
public function index(Request $request, $nis)
{
    $santri = Santri::findOrFail($nis);
    $searchDate = $request->get('tanggal_cari');

    // 1. Query dasar dengan Paginate 10
    $query = Absensi::where('nis', $nis);

    if ($searchDate) {
        $query->where('tanggal', $searchDate);
    }

    // Ambil data unik tanggal saja untuk dipaginate agar grouping tidak berantakan
    // Kita gunakan perPage 10
    $absensiRaw = $query->orderBy('tanggal', 'desc')->paginate(10);

    // --- Logika Statistik (Tetap hitung dari semua data) ---
    $statsTotal = [
        'hadir' => Absensi::where('nis', $nis)->where('keterangan', 'hadir')->count(),
        'sakit' => Absensi::where('nis', $nis)->where('keterangan', 'sakit')->count(),
        'alpa'  => Absensi::where('nis', $nis)->where('keterangan', 'tidak hadir')->count(),
    ];

    $now = Carbon::now('Asia/Jakarta');
    $statsBulan = [
        'hadir' => Absensi::where('nis', $nis)->whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year)->where('keterangan', 'hadir')->count(),
        'sakit' => Absensi::where('nis', $nis)->whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year)->where('keterangan', 'sakit')->count(),
        'alpa'  => Absensi::where('nis', $nis)->whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year)->where('keterangan', 'tidak hadir')->count(),
    ];

    // Grouping data yang sudah di-paginate
    $absensiGrouped = $absensiRaw->groupBy('tanggal')->map(function ($row) {
        return [
            'pagi'  => $row->where('waktu', 'pagi')->first(),
            'malam' => $row->where('waktu', 'malam')->first(),
        ];
    });

    return view('keamanan.absensi_index', compact(
        'santri', 
        'absensiGrouped', 
        'absensiRaw', // Kirim objek paginator ke view
        'statsTotal', 
        'statsBulan', 
        'searchDate'
    ));
}
    // ================= USER =================

    public function myAbsensi()
    {
        $user = Auth::user();

        if (!$user->nis_FK) {
            return back()->with('error', 'User tidak terhubung dengan santri');
        }

        $santri = Santri::findOrFail($user->nis_FK);

        $absensi = Absensi::where('nis', $user->nis_FK)
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu')
            ->get();

        return view('user.absensi.index', compact('santri', 'absensi'));
    }
}