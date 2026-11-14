<?php

namespace App\Http\Controllers\Terapis;

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
        $terapis = $user->terapis;

        return view('terapis.profil.edit', compact('user', 'terapis'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $terapis = $user->terapis;

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'no_telepon' => ['required', 'string', 'max:20'],
            'foto_profil' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($request, $data, $user, $terapis) {
            $user->fill([
                'nama_lengkap' => $data['nama_lengkap'],
                'email' => $data['email'],
                'no_telepon' => $data['no_telepon'],
            ]);
            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();

            if ($request->hasFile('foto_profil')) {
                if ($terapis->foto_profil) {
                    Storage::disk('public')->delete($terapis->foto_profil);
                }
                $terapis->update(['foto_profil' => $request->file('foto_profil')->store('terapis', 'public')]);
            }
        });

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
