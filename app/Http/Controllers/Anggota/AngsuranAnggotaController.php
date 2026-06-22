<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use Illuminate\Support\Facades\Auth;

class AngsuranAnggotaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Pastikan user punya data anggota
        if (!$user->anggota) {
            return redirect('/dashboard-anggota')->with('error', 'Data keanggotaan tidak ditemukan.');
        }

        // Ambil semua daftar pinjaman untuk opsi dropdown riwayat
        $semuaPinjaman = Pinjaman::where('anggota_id', $user->anggota->id)
            ->whereIn('status', ['disetujui', 'lunas'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pinjamanId = $request->query('id');

        if ($pinjamanId) {
            // Jika user memilih pinjaman tertentu dari dropdown
            $pinjaman = Pinjaman::with('angsurans')
                ->where('anggota_id', $user->anggota->id)
                ->where('id', $pinjamanId)
                ->first();
        } else {
            // Cari pinjaman yang sedang aktif (disetujui/berjalan) terlebih dahulu
            $pinjaman = Pinjaman::with('angsurans')
                ->where('anggota_id', $user->anggota->id)
                ->where('status', 'disetujui')
                ->latest()
                ->first();

            // Jika tidak ada yang aktif, tampilkan histori pinjaman terakhir
            if (!$pinjaman) {
                $pinjaman = Pinjaman::with('angsurans')
                    ->where('anggota_id', $user->anggota->id)
                    ->latest()
                    ->first();
            }
        }

        return view('anggota.angsuran.index', compact('pinjaman', 'semuaPinjaman'));
    }

    // Memproses upload bukti transfer oleh Anggota
    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Anda harus mengunggah foto bukti transfer.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $angsuran = Angsuran::findOrFail($id);

        if ($angsuran->pinjaman->anggota->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        try {
            $file = $request->file('bukti_pembayaran');
            
            $ekstensi = $file->getClientOriginalExtension();
            $fileName = time() . '_struk_anggota_' . $id . '.' . $ekstensi; 
            
            // Pindahkan langsung ke folder public
            $file->storeAs('uploads/angsuran', $fileName, 'public');

            $angsuran->bukti_pembayaran = $fileName;
            $angsuran->status = 'menunggu_verifikasi';
            $angsuran->keterangan_tolak = null;
            $angsuran->save();

            return back()->with('success', 'Bukti pembayaran berhasil diunggah. Silakan tunggu verifikasi dari Bendahara.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunggah bukti pembayaran: ' . $e->getMessage());
        }
    }
}