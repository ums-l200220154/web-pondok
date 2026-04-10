<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Absensi;
use App\Models\Pembayaran;
use App\Models\SiteContent;
use App\Models\UangSaku;
use App\Models\RincianPembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // ================= ADMIN =================
    public function index()
    {
        return view('admin.dashboard', [
            'totalSantri' => Santri::count(),
        ]);
    }

    // ================= KEAMANAN =================
    public function keamanan()
    {
        return view('keamanan.dashboard', [
            'totalSantri'     => Santri::count(),
            'absensiHariIni'  => Absensi::whereDate('tanggal', now())->count(),
        ]);
    }

    public function bendahara()
{
    return view('bendahara.dashboard', [
        'totalPembayaran' => \App\Models\Pembayaran::count(),
        'menunggu' => \App\Models\Pembayaran::where('status', 'menunggu')->count(),
        // 'totalUangSaku' => \App\Models\UangSaku::sum('saldo') ?? 0, 
        'totalJenis' => \App\Models\JenisPembayaran::count(),
    ]);
}

    // ================= USER =================
    public function user()
    {
        $user = Auth::user();
        $nis = $user->nis_FK;

        // 1. Data Dasar & Saldo
        $santri = Santri::where('nis', $nis)->first();
        $saldo = UangSaku::where('nis', $nis)->value('saldo') ?? 0;

        // 2. Notifikasi (Maks 3 status terakhir dari tabel induk Pembayaran)
        $notifikasi = Pembayaran::where('nis', $nis)
            ->whereIn('status', ['lunas', 'belum lunas'])
            ->latest()
            ->take(3)
            ->get();

        // 3. Logika Grid Pembayaran (Mengecek Rincian Pembayaran)
        // Kita ambil rincian yang status induknya 'lunas'
        $bulanMap = [
            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
            'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
            'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12
        ];

        $pembayaranLunas = RincianPembayaran::whereHas('pembayaran', function($query) use ($nis) {
                $query->where('nis', $nis)->where('status', 'lunas');
            })
            ->where('tahun', date('Y'))
            ->where('kategori', 'pembayaran')
            ->pluck('bulan')
            ->map(function($bulan) use ($bulanMap) {
                return $bulanMap[strtolower($bulan)] ?? null;
            })
            ->filter()
            ->unique()
            ->toArray();

        // 4. Konten Admin
        $adminContents = SiteContent::latest()->get();

        return view('user.dashboard', [
            'santri'          => $santri,
            'saldo'           => $saldo,
            'notifikasi'      => $notifikasi,
            'pembayaranLunas' => $pembayaranLunas,
            'adminContents'   => $adminContents,
            'listBulan'       => [1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'Mei', 6=>'Jun', 7=>'Jul', 8=>'Agu', 9=>'Sep', 10=>'Okt', 11=>'Nov', 12=>'Des']
        ]);
    }
}