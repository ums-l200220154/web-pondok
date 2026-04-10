<?php
namespace App\Http\Controllers;

use App\Models\UangSaku;
use App\Models\Santri;
use Illuminate\Http\Request;

class UangSakuController extends Controller
{
    // ================= USER =================
    public function indexUser()
    {
        $user = auth()->user();

        $data = UangSaku::where('nis', $user->nis_FK)->latest()->get();

        return view('user.uangsaku.index', compact('data'));
    }

    // ================= BENDAHARA =================
    // Halaman utama: daftar santri
    public function index(Request $request)
    {
        $search = $request->get('search');
        $santris = Santri::when($search, function($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nis', 'like', "%{$search}%");
        })->orderBy('nama')
        ->paginate(15);
        

        return view('bendahara.uangsaku.index', compact('santris', 'search'));
    }

    // Halaman detail pengelolaan satu santri
    public function show(Request $request, $nis)
    {
        $santri = Santri::findOrFail($nis);
        $search = $request->get('search');

        $uangSaku = UangSaku::where('nis', $nis)
            ->when($search, function($query) use ($search) {
                return $query->where('keterangan', 'like', "%{$search}%")
                             ->orWhere('tanggal', $search);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_uangsaku', 'desc')
            ->paginate(15);

        $saldoSekarang = UangSaku::where('nis', $nis)->latest('id_uangsaku')->value('saldo') ?? 0;

        return view('bendahara.uangsaku.show', compact('santri', 'uangSaku', 'saldoSekarang', 'search'));
    }

    // Tambah pengambilan uang saku harian
    public function create($nis)
    {
        $santri = Santri::findOrFail($nis);
        return view('bendahara.uangsaku.create', compact('santri'));
    }

    public function store(Request $request, $nis)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'jenis' => 'required|in:masuk,keluar',
            'keterangan' => 'required|string|max:255',
        ]);

        $lastSaldo = UangSaku::where('nis', $nis)->latest('id_uangsaku')->value('saldo') ?? 0;

        // Hitung Saldo Baru
        if ($request->jenis == 'masuk') {
            $newSaldo = $lastSaldo + $request->jumlah;
        } else {
            if ($lastSaldo < $request->jumlah) {
                return back()->with('error', 'Saldo tidak mencukupi untuk penarikan.');
            }
            $newSaldo = $lastSaldo - $request->jumlah;
        }

        UangSaku::create([
            'nis' => $nis,
            'jumlah' => $request->jumlah,
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
            'saldo' => $newSaldo,
            'tanggal' => now(),
        ]);

        return back()->with('success', 'Transaksi berhasil dicatat.');
    }
}