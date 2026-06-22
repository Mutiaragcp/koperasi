<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Ditambahkan untuk mengatasi P1013

class AngsuranController extends Controller
{
    public function index(Request $request)
    {
        $query = Angsuran::with(['pinjaman.anggota.user'])
                         ->whereIn('status', ['belum_bayar', 'telat', 'menunggu_verifikasi', 'ditolak']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $angsurans = $query->orderByRaw("FIELD(status, 'menunggu_verifikasi', 'ditolak', 'telat', 'belum_bayar')")
                           ->orderBy('tgl_jatuh_tempo', 'asc')
                           ->get();

        $totalTagihanBulanIni = Angsuran::where('status', 'belum_bayar')
            ->whereMonth('tgl_jatuh_tempo', Carbon::now()->month)
            ->whereYear('tgl_jatuh_tempo', Carbon::now()->year)
            ->sum('jumlah_bayar');

        $totalTelat = Angsuran::where('status', 'telat')->count();
        $totalMenunggu = Angsuran::where('status', 'belum_bayar')->count();
        $totalVerifikasi = Angsuran::where('status', 'menunggu_verifikasi')->count();

        return view('pengurus.angsuran.index', compact('angsurans', 'totalTagihanBulanIni', 'totalTelat', 'totalMenunggu', 'totalVerifikasi'));
    }

    public function bayarForm(string $id)
    {
        $angsuran = Angsuran::with('pinjaman.anggota.user')->findOrFail($id);

        if ($angsuran->status == 'lunas') {
            return redirect('/transaksi-angsuran')->with('error', 'Angsuran ini sudah berstatus lunas.');
        }

        return view('pengurus.angsuran.bayar', compact('angsuran'));
    }

    public function bayarAngsuran(Request $request, string $id) // Ditambahkan 'string'
    {
        $angsuran = Angsuran::with('pinjaman.anggota.user')->findOrFail($id);

        if ($angsuran->status == 'lunas') {
            return back()->with('error', 'Angsuran ini sudah lunas sebelumnya.');
        }

        DB::beginTransaction();

        try {
            $buktiPath = $angsuran->bukti_pembayaran; 

            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $ekstensi = $file->getClientOriginalExtension();
                $fileName = time() . '_struk_kasir_' . $id . '.' . $ekstensi; 
                $file->storeAs('uploads/angsuran', $fileName, 'public');
                $buktiPath = $fileName;
            }

            $tglBayar = $request->tgl_bayar ?? now()->toDateString();
            $nominalDenda = $request->denda ?? 0;
            $totalUangMasuk = $angsuran->jumlah_bayar + $nominalDenda;

            $angsuran->status = 'lunas';
            $angsuran->tgl_bayar = $tglBayar;
            $angsuran->denda = $nominalDenda;
            $angsuran->bukti_pembayaran = $buktiPath;
            $angsuran->save();

            DB::table('transaksi_kas')->insert([
                'tanggal' => $tglBayar,
                'jenis' => 'masuk',
                'kategori' => 'Angsuran Pinjaman',
                'nominal' => $totalUangMasuk,
                'keterangan' => 'Angsuran Ke-' . $angsuran->ke_berapa . ' dari Anggota: ' . ($angsuran->pinjaman->anggota->user->nama ?? 'Tanpa Nama') . ' (No Anggota: ' . $angsuran->pinjaman->anggota->no_anggota . ')',
                'dieksekusi_oleh' => Auth::id(), // Diubah dari auth()->id() ke Auth::id()
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $pinjaman = Pinjaman::with('angsurans')->findOrFail($angsuran->pinjaman_id);
            $sisaAngsuran = $pinjaman->angsurans->where('status', '!=', 'lunas')->count();

            if ($sisaAngsuran == 0) {
                $pinjaman->status = 'lunas';
                $pinjaman->save();
            }

            DB::commit();
            return redirect('/transaksi-angsuran')->with('success', 'Pembayaran angsuran berhasil disahkan dan tercatat di Buku Kas Koperasi!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function tolakPembayaran(Request $request, string $id)
    {
        $request->validate([
            'keterangan_tolak' => 'required|string|max:500'
        ], [
            'keterangan_tolak.required' => 'Alasan penolakan wajib diisi.'
        ]);

        $angsuran = Angsuran::findOrFail($id);
        
        $angsuran->status = 'ditolak';
        $angsuran->keterangan_tolak = $request->keterangan_tolak;
        $angsuran->save();

        return redirect('/transaksi-angsuran')->with('success', 'Bukti transfer ditolak. Anggota akan diminta untuk mengunggah ulang.');
    }
}