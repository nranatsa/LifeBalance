<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $pasien = $user->pasien;

        return view('pasien.profil.edit', compact('user', 'pasien'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $pasien = $user->pasien;

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'no_telepon' => ['required', 'string', 'max:20'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['required', 'string'],
            'catatan_medis' => ['nullable', 'string'],
            'foto_profil' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($request, $data, $user, $pasien) {
            $user->fill([
                'nama_lengkap' => $data['nama_lengkap'],
                'email' => $data['email'],
                'no_telepon' => $data['no_telepon'],
            ]);
            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();

            $foto = $pasien->foto_profil;
            if ($request->hasFile('foto_profil')) {
                if ($foto) {
                    Storage::disk('public')->delete($foto);
                }
                $foto = $request->file('foto_profil')->store('pasien', 'public');
            }

            $pasien->update([
                'tanggal_lahir' => $data['tanggal_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => $data['alamat'],
                'catatan_medis' => $data['catatan_medis'] ?? null,
                'foto_profil' => $foto,
            ]);
        });

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
