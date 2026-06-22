<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AnggotaController extends Controller
{
    // Menampilkan halaman tabel data anggota
    public function index()
    {
        $anggotas = Anggota::with('user')->latest()->get();
        return view('pengurus.anggota.index', compact('anggotas'));
    }

    // Menampilkan form tambah anggota
    public function create()
    {
        return view('pengurus.anggota.create');
    }

    // Proses menyimpan data ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nik' => 'required|string|size:16|unique:anggotas,nik',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ], [
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'nik.unique' => 'NIK ini sudah terdaftar sebagai anggota.',
            'nik.size' => 'NIK harus tepat 16 digit.',
            'foto_ktp.max' => 'Ukuran file foto KTP maksimal 2MB.',
        ]);

        DB::beginTransaction();

        try {
            $user = new User();
            $user->nama = $request->nama;
            $user->email = $request->email;
            $user->password = Hash::make('password123'); 
            $user->role = 'anggota'; 
            $user->save();

            $fotoPath = null;
            if ($request->hasFile('foto_ktp')) {
                // Simpan ke folder 'ktp' di storage/app/public/
                $fotoPath = $request->file('foto_ktp')->store('ktp', 'public');
            }

            $no_anggota = 'ANG-' . date('Ym') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);

            $anggota = new Anggota();
            $anggota->user_id = $user->id;
            $anggota->no_anggota = $no_anggota;
            $anggota->nik = $request->nik;
            $anggota->alamat = $request->alamat;
            $anggota->no_hp = $request->no_hp;
            $anggota->tgl_gabung = now(); // Set tanggal hari ini otomatis
            $anggota->status = 'aktif';   // Set status aktif otomatis
            $anggota->foto_ktp = $fotoPath; // Simpan path foto
            $anggota->save();

            DB::commit();

            return redirect('/anggota')->with('success', 'Anggota baru berhasil ditambahkan! Akun login otomatis dibuat dengan sandi: password123');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
        }
    }

    // Menampilkan halaman detail anggota (show)
    public function show($id)
    {
        $anggota = Anggota::with(['user', 'simpanans.jenis_simpanan', 'pinjamans'])->findOrFail($id);

        $totalSetor = $anggota->simpanans->where('status', 'disetujui')->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = $anggota->simpanans->where('status', 'disetujui')->where('jenis_transaksi', 'tarik')->sum('jumlah');
        $totalSaldo = $totalSetor - $totalTarik;

        $pinjamanAktif = $anggota->pinjamans->whereIn('status', ['menunggu', 'disetujui'])->first();

        // Kirim semua data ke view anggota.show
        return view('pengurus.anggota.show', compact('anggota', 'totalSaldo', 'pinjamanAktif'));
    }
    // Menampilkan form edit anggota
    public function edit($id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);

        return view('pengurus.anggota.edit', compact('anggota'));
    }

    // Proses update data ke database
    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);
        $user = User::findOrFail($anggota->user_id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6', // Password boleh kosong
            'nik' => 'required|string|size:16|unique:anggotas,nik,' . $anggota->id,
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ]);

        DB::beginTransaction();

        try {
            $userData = [
                'nama' => $request->nama,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            $fotoPath = $anggota->foto_ktp; // Default: pakai foto lama
            if ($request->hasFile('foto_ktp')) {
                if ($anggota->foto_ktp && \Illuminate\Support\Facades\Storage::disk('public')->exists($anggota->foto_ktp)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($anggota->foto_ktp);
                }
                $fotoPath = $request->file('foto_ktp')->store('ktp', 'public');
            }

            $anggota->update([
                'nik' => $request->nik,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'status' => $request->status,
                'foto_ktp' => $fotoPath, // Simpan path foto
            ]);

            DB::commit();

            return redirect('/anggota/' . $anggota->id)->with('success', 'Data anggota berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat update: ' . $e->getMessage()])->withInput();
        }
    }

    // Menghapus data anggota
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $anggota = Anggota::findOrFail($id);
            $userId = $anggota->user_id;

            // Hapus data dari tabel anggotas
            $anggota->delete();

            User::findOrFail($userId)->delete();

            DB::commit();

            return redirect('/anggota')->with('success', 'Data anggota beserta akun login berhasil dihapus permanen.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/anggota')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
