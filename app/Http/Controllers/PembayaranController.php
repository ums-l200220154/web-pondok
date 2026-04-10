<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\Pembayaran;
use App\Models\RincianPembayaran;
use App\Models\UangSaku;
use App\Models\JenisPembayaran;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    // ================= USER: FORM CREATE =================
    // ================= USER: FORM CREATE =================
public function create(Request $request)
{
    $user = auth()->user();
    $tahunDipilih = $request->get('tahun', now()->year);

    $allJenis = JenisPembayaran::all();
    $jumlahJenisWajib = $allJenis->count();

    // 1. Bulan yang BENAR-BENAR LUNAS (Semua rincian sudah berstatus 'lunas')
    $bulanTerbayar = RincianPembayaran::whereHas('pembayaran', function($q) use ($user) {
            $q->where('nis', $user->nis_FK)->where('status', 'lunas');
        })
        ->where('tahun', $tahunDipilih)
        ->select('bulan', DB::raw('count(DISTINCT jenis) as jml_jenis'))
        ->groupBy('bulan')
        ->having('jml_jenis', '>=', $jumlahJenisWajib)
        ->pluck('bulan')->toArray();

    // 2. Bulan yang "Kurang Bayar" (Masih ada sisa rincian yang belum dibayar)
    $bulanBelumLunas = RincianPembayaran::whereHas('pembayaran', function($q) use ($user) {
            $q->where('nis', $user->nis_FK)->whereIn('status', ['lunas', 'belum lunas', 'menunggu']);
        })
        ->where('tahun', $tahunDipilih)
        ->pluck('bulan')->unique()->toArray();
    
    // Kita tetap masukkan bulan 'menunggu' ke dalam daftar yang tidak bisa dipilih secara bebas 
    // agar tidak terjadi double submit sebelum admin verifikasi.

    // 3. LOGIKA KUNCI: Ambil SEMUA rincian yang pernah dibayar (Apapun status pembayarannya)
    // tujuannya agar rincian ini di-DISABLE di form
    $rincianSudahAda = RincianPembayaran::whereHas('pembayaran', function($q) use ($user) {
            $q->where('nis', $user->nis_FK)->whereIn('status', ['lunas', 'belum lunas', 'menunggu']);
        })
        ->where('tahun', $tahunDipilih)
        ->get()
        ->groupBy('bulan')
        ->map(function($items) {
            return $items->pluck('jenis')->toArray();
        });

    $listTahun = [now()->year, now()->year + 1];
    $jenis = $allJenis;

    return view('user.pembayaran.pembayaran_create', compact(
        'jenis', 'bulanTerbayar', 'bulanBelumLunas', 'tahunDipilih', 'listTahun', 'rincianSudahAda'
    ));
}
    public function store(Request $request)
{
    $request->validate([
        'bulan' => 'required|array|min:1',
        'rincian_bulan' => 'required|array', // Nama input baru dari form
        'bukti' => 'required|image|mimes:jpg,png|max:2048',
        'tahun' => 'required'
    ]);

    $user = auth()->user();
    $file = $request->file('bukti')->store('bukti', 'public');

    // 1. Hitung Total Tagihan secara akurat dari rincian yang dipilih
    $totalTagihanWajib = 0;
    foreach ($request->bulan as $bulan) {
        if (isset($request->rincian_bulan[$bulan])) {
            foreach ($request->rincian_bulan[$bulan] as $index => $namaJenis) {
                // Ambil nominal yang sesuai dengan rincian bulan tersebut
                // Kita ambil dari nominal_bulan yang dikirim lewat hidden input
                $nominal = $request->nominal_bulan[$bulan][$index] ?? 0;
                $totalTagihanWajib += $nominal;
            }
        }
    }

    $titipanUangSaku = $request->uang_saku ?? 0;
    $grandTotal = $totalTagihanWajib + $titipanUangSaku;

    DB::transaction(function () use ($request, $user, $file, $totalTagihanWajib, $grandTotal, $titipanUangSaku) {
        // 2. Simpan Header Pembayaran
        $pembayaran = Pembayaran::create([
            'tanggal_pembayaran' => now(),
            'jumlah' => $totalTagihanWajib,
            'total_bayar' => $grandTotal,
            'status' => 'menunggu',
            'bukti' => $file,
            'nis' => $user->nis_FK
        ]);

        // 3. Simpan Rincian Pembayaran per Bulan
        foreach ($request->bulan as $bulan) {
            if (isset($request->rincian_bulan[$bulan])) {
                foreach ($request->rincian_bulan[$bulan] as $index => $namaJenis) {
                    RincianPembayaran::create([
                        'id_pembayaran' => $pembayaran->id_pembayaran,
                        'jenis' => $namaJenis,
                        'jumlah' => $request->nominal_bulan[$bulan][$index],
                        'bulan' => $bulan,
                        'tahun' => $request->tahun,
                        'kategori' => 'pembayaran'
                    ]);
                }
            }
        }

        // 4. Simpan Titipan Uang Saku jika ada
        if ($titipanUangSaku > 0) {
            RincianPembayaran::create([
                'id_pembayaran' => $pembayaran->id_pembayaran,
                'jenis' => 'Titipan Uang Saku',
                'jumlah' => $titipanUangSaku,
                'bulan' => null,
                'tahun' => $request->tahun,
                'kategori' => 'uang_saku'
            ]);
        }
    });

    return redirect()->route('user.pembayaran.index')->with('success', 'Konfirmasi pembayaran berhasil dikirim.');
}

    public function approve($id)
{
    // 1. Ambil transaksi yang sedang di-approve beserta rinciannya
    $pembayaran = Pembayaran::with('rincian')->findOrFail($id);
    $nisSantri = $pembayaran->nis;

    DB::transaction(function () use ($pembayaran, $nisSantri) {

        // 2. Update transaksi SAAT INI menjadi LUNAS
        $pembayaran->update([
            'status' => 'lunas',
            'keterangan_bendahara' => 'Disetujui Admin'
        ]);

        // 3. Ambil rincian bulan & tahun dari transaksi baru ini
        // Kita hanya mengambil yang kategori 'pembayaran' (bukan uang saku)
        $rincianBaru = $pembayaran->rincian->where('kategori', 'pembayaran');

        foreach ($rincianBaru as $r) {
            $bulanRincian = $r->bulan;
            $tahunRincian = $r->tahun;

            /**
             * LOGIKA UTAMA:
             * Cari ID Pembayaran lain milik santri ini yang memiliki rincian 
             * di BULAN dan TAHUN yang sama, dan statusnya masih 'belum lunas'.
             */
            $idTransaksiLama = RincianPembayaran::where('bulan', $bulanRincian)
                ->where('tahun', $tahunRincian)
                ->where('id_pembayaran', '!=', $pembayaran->id_pembayaran) // Bukan transaksi yang sekarang
                ->whereHas('pembayaran', function ($q) use ($nisSantri) {
                    $q->where('nis', $nisSantri)
                      ->where('status', 'belum lunas'); // Target spesifik yang macet
                })
                ->pluck('id_pembayaran')
                ->unique();

            // 4. Update status transaksi-transaksi lama tersebut menjadi LUNAS
            if ($idTransaksiLama->isNotEmpty()) {
                Pembayaran::whereIn('id_pembayaran', $idTransaksiLama)
                    ->update([
                        'status' => 'lunas',
                        'keterangan_bendahara' => 'Otomatis Lunas (Kekurangan dibayar pada TRX #' . $pembayaran->id_pembayaran . ')'
                    ]);
            }
        }

        // 5. Logic Uang Saku (Tetap dijalankan jika ada)
        $rincianUangSaku = $pembayaran->rincian->where('kategori', 'uang_saku')->first();
        if ($rincianUangSaku) {
            $lastSaldo = UangSaku::where('nis', $nisSantri)->latest('id_uangsaku')->value('saldo') ?? 0;
            UangSaku::create([
                'nis' => $nisSantri,
                'jumlah' => $rincianUangSaku->jumlah,
                'jenis' => 'masuk',
                'keterangan' => 'Titipan via Pembayaran #' . $pembayaran->id_pembayaran,
                'saldo' => $lastSaldo + $rincianUangSaku->jumlah,
                'tanggal' => now()
            ]);
        }
    });

    return back()->with('success', 'Status pembayaran telah diperbarui dan disinkronkan.');
}

    public function setBelumLunas(Request $request, $id)
{
    $pembayaran = Pembayaran::with('rincian')->findOrFail($id);
    
    DB::transaction(function () use ($pembayaran, $request) {
        // Status tetap 'belum lunas' agar bulan tersebut muncul lagi di form user
        $pembayaran->update([
            'status' => 'belum lunas',
            'keterangan_bendahara' => $request->keterangan_admin
        ]);

        // Jika ada titipan uang saku dalam transaksi 'belum lunas', 
        // uang saku tetap dimasukkan karena uang fisiknya sudah diterima bendahara.
        $rincianUangSaku = $pembayaran->rincian->where('kategori', 'uang_saku')->first();
        if ($rincianUangSaku) {
            $lastSaldo = UangSaku::where('nis', $pembayaran->nis)->latest('id_uangsaku')->value('saldo') ?? 0;
            UangSaku::create([
                'nis' => $pembayaran->nis,
                'jumlah' => $rincianUangSaku->jumlah,
                'jenis' => 'masuk',
                'keterangan' => 'Titipan (Status: Kurang Bayar) #' . $pembayaran->id_pembayaran,
                'saldo' => $lastSaldo + $rincianUangSaku->jumlah,
                'tanggal' => now()
            ]);
        }
    });

    return back()->with('warning', 'Pembayaran diterima sebagian (Belum Lunas).');
}

    public function reject(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update([
            'status' => 'ditolak',
            'keterangan_bendahara' => $request->keterangan_admin
        ]); 
        return back()->with('error', 'Pembayaran DITOLAK.');
    }

    public function getSantriData($nis, Request $request)
{
    $tahun = $request->get('tahun', date('Y'));
    // Ambil jumlah jenis pembayaran aktif
    $jumlahJenisWajib = JenisPembayaran::count();

    // Ambil daftar bulan yang statusnya benar-benar sudah lunas (semua jenis sudah dibayar)
    $bulanTerbayar = RincianPembayaran::whereHas('pembayaran', function($q) use ($nis) {
            $q->where('nis', $nis)->where('status', 'lunas');
        })
        ->where('tahun', $tahun)
        ->select('bulan', DB::raw('count(DISTINCT jenis) as jml_jenis'))
        ->groupBy('bulan')
        ->having('jml_jenis', '>=', $jumlahJenisWajib)
        ->pluck('bulan')->toArray();

    // Ambil rincian yang sudah dibayar (meskipun status pembayaran masih 'menunggu' atau 'belum lunas')
    // Ini digunakan untuk mem-filter item di dalam MODAL nantinya
    $rincianSudahAda = RincianPembayaran::whereHas('pembayaran', function($q) use ($nis) {
            $q->where('nis', $nis)->whereIn('status', ['lunas', 'belum lunas', 'menunggu']);
        })
        ->where('tahun', $tahun)
        ->get()
        ->groupBy('bulan')
        ->map(function($items) {
            return $items->pluck('jenis')->toArray();
        });

    return response()->json([
        'bulanTerbayar' => $bulanTerbayar,
        'rincianSudahAda' => $rincianSudahAda
    ]);
}

    // Tambahkan di bagian BENDAHARA: INPUT MANUAL
public function createManual()
{
    $listSantri = Santri::all();
    $jenis = JenisPembayaran::all();
    return view('bendahara.pembayaran.manual', compact('listSantri', 'jenis'));
}

    public function storeManual(Request $request)
{
    $request->validate([
        'nis' => 'required',
        'bulan' => 'required|array',
        'rincian_bulan' => 'required|array',
        'tahun' => 'required',
        'status_manual' => 'required|in:lunas,belum lunas' // Input baru untuk status
    ]);

    DB::transaction(function () use ($request) {
        $totalTagihan = 0;

        // 1. Hitung total dari rincian per bulan
        foreach ($request->bulan as $bulan) {
            if (isset($request->rincian_bulan[$bulan])) {
                foreach ($request->nominal_bulan[$bulan] as $nominal) {
                    $totalTagihan += $nominal;
                }
            }
        }

        // 2. Buat Header Pembayaran
        // Status diambil dari pilihan bendahara (lunas/belum lunas)
        $pembayaran = Pembayaran::create([
            'tanggal_pembayaran' => now(),
            'jumlah' => $totalTagihan,
            'total_bayar' => $totalTagihan,
            'status' => $request->status_manual, 
            'bukti' => 'manual_tunai.jpg',
            'nis' => $request->nis,
            'keterangan_bendahara' => $request->status_manual == 'lunas' 
                ? 'Pembayaran Tunai (Lunas)' 
                : 'Penerimaan Tunai Sebagian: ' . ($request->keterangan_admin ?? 'Tidak ada keterangan')
        ]);

        // 3. Simpan Rincian detail per Bulan
        foreach ($request->bulan as $bulan) {
            if (isset($request->rincian_bulan[$bulan])) {
                foreach ($request->rincian_bulan[$bulan] as $index => $namaJenis) {
                    RincianPembayaran::create([
                        'id_pembayaran' => $pembayaran->id_pembayaran,
                        'jenis' => $namaJenis,
                        'jumlah' => $request->nominal_bulan[$bulan][$index],
                        'bulan' => $bulan,
                        'tahun' => $request->tahun,
                        'kategori' => 'pembayaran'
                    ]);
                }
            }
        }
    });

    return redirect()->route('bendahara.pembayaran.index')
        ->with('success', 'Pembayaran manual berhasil dicatat dengan status: ' . strtoupper($request->status_manual));
}

    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = Pembayaran::with(['santri', 'rincian'])
            ->when($search, function($query) use ($search) {
                $query->whereHas('santri', function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })->orWhere('nis', 'like', "%{$search}%");
            })
            ->latest('id_pembayaran')
            ->paginate(15);
            
        $listSantri = Santri::all(); // Untuk modal manual
        $jenis = JenisPembayaran::all();

        return view('bendahara.pembayaran.index', compact('data', 'search', 'listSantri', 'jenis'));
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with(['santri', 'rincian'])->findOrFail($id);
        return view('bendahara.pembayaran.show', compact('pembayaran'));
    }

    // ================= BENDAHARA: DAFTAR REKAPITULASI SEMUA SANTRI =================
public function rekapSantri(Request $request)
{
    $search = $request->get('search');
    
    // Gunakan nama variabel $santri agar sinkron dengan View
    $santri = Santri::when($search, function($query) use ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
        })
        ->paginate(10); 

    // Kirimkan variabel 'santri'
    return view('bendahara.pembayaran.rekap_santri', compact('santri', 'search'));
}

    // ================= BENDAHARA: DETAIL HISTORI PER SANTRI =================
    public function detailRekapSantri($nis)
    {
        $santri = Santri::where('nis', $nis)->firstOrFail();
        
        // Mengambil riwayat semua transaksi santri tersebut
        $riwayat = Pembayaran::with('rincian')
            ->where('nis', $nis)
            ->latest('id_pembayaran')
            ->get();
        
        // Ambil daftar bulan yang sudah lunas di tahun berjalan untuk grid visual
        $lunas = RincianPembayaran::whereHas('pembayaran', function($q) use ($nis) {
                $q->where('nis', $nis)->where('status', 'lunas');
            })
            ->where('tahun', date('Y'))
            ->pluck('bulan')
            ->toArray();

        return view('bendahara.pembayaran.rekap_detail', compact('santri', 'riwayat', 'lunas'));
    }

    // ================= USER: RIWAYAT =================
    // ================= USER: RIWAYAT =================
public function riwayat(Request $request)
{
    $search = $request->get('search');
    $userNis = auth()->user()->nis_FK;

    // Eager load rincian untuk performa
    $data = Pembayaran::with('rincian')
        ->where('nis', $userNis)
        ->when($search, function($query) use ($search) {
            $query->where(function($q) use ($search) {
                // Cari berdasarkan ID Pembayaran
                $q->where('id_pembayaran', 'like', "%{$search}%")
                // Atau cari berdasarkan bulan di dalam rincian
                ->orWhereHas('rincian', function($sq) use ($search) {
                    $sq->where('bulan', 'like', "%{$search}%");
                });
            });
        })
        ->latest('id_pembayaran')
        ->paginate(10); // Menampilkan 10 data per halaman
        
    // Menambahkan parameter pencarian ke link pagination agar tidak hilang saat pindah page
    $data->appends(['search' => $search]);

    return view('user.pembayaran.pembayaran_histori', compact('data', 'search'));
}

    
}