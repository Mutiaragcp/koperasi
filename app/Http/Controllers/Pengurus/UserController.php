<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('anggota')->orderBy('role')->orderBy('nama')->get();
        return view('pengurus.users.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,bendahara,anggota',
        ]);

        $user = User::findOrFail($id);

        // Cegah admin mengubah role dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'Role user "' . $user->nama . '" berhasil diubah menjadi ' . strtoupper($request->role) . '.');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        // Cegah admin mereset password dirinya sendiri lewat fitur ini
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mereset password akun Anda sendiri dari sini.');
        }

        // Reset password ke default: password123
        $user->password = Hash::make('password123');
        $user->save();

        return back()->with('success', 'Password user "' . $user->nama . '" berhasil direset ke: password123');
    }
}
