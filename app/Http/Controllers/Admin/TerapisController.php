<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Terapis;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TerapisController extends Controller
{
    public function index(): View
    {
        $terapis = Terapis::with('user')->withCount('jadwalPraktik')->latest()->paginate(10);

        return view('admin.terapis.index', compact('terapis'));
    }

    public function create(): View
    {
        return view('admin.terapis.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'spesialisasi' => ['nullable', 'string', 'max:255'],
            'foto_profil' => ['nullable', 'image', 'max:2048'],
        ]);

        DB::transaction(function () use ($request, $data) {
            $user = User::create([
                'nama_lengkap' => $data['nama_lengkap'],
                'email' => $data['email'],
                'no_telepon' => $data['no_telepon'],
                'password' => $data['password'],
                'role' => 'terapis',
            ]);

            Terapis::create([
                'user_id' => $user->id,
                'spesialisasi' => $data['spesialisasi'] ?? null,
                'status_aktif' => $request->boolean('status_aktif', true),
                'foto_profil' => $request->hasFile('foto_profil')
                    ? $request->file('foto_profil')->store('terapis', 'public')
                    : null,
            ]);
        });

        return redirect()->route('admin.terapis.index')->with('success', 'Terapis berhasil ditambahkan.');
    }

    public function show(Terapis $terapi): View
    {
        $terapi->load(['user', 'jadwalPraktik']);

        $reservasi = $terapi->reservasi()
            ->with(['pasien.user', 'paket'])
            ->where('tanggal_praktik', '>=', today())
            ->aktif()
            ->orderBy('tanggal_praktik')
            ->orderBy('jam_mulai')
            ->limit(20)
            ->get();

        return view('admin.terapis.show', ['terapis' => $terapi, 'reservasi' => $reservasi]);
    }

    public function edit(Terapis $terapi): View
    {
        $terapi->load('user');

        return view('admin.terapis.edit', ['terapis' => $terapi]);
    }

    public function update(Request $request, Terapis $terapi): RedirectResponse
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($terapi->user_id)],
            'no_telepon' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'spesialisasi' => ['nullable', 'string', 'max:255'],
            'foto_profil' => ['nullable', 'image', 'max:2048'],
        ]);

        DB::transaction(function () use ($request, $data, $terapi) {
            $user = $terapi->user;
            $user->fill([
                'nama_lengkap' => $data['nama_lengkap'],
                'email' => $data['email'],
                'no_telepon' => $data['no_telepon'],
            ]);
            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();

            $foto = $terapi->foto_profil;
            if ($request->hasFile('foto_profil')) {
                if ($foto) {
                    Storage::disk('public')->delete($foto);
                }
                $foto = $request->file('foto_profil')->store('terapis', 'public');
            }

            $terapi->update([
                'spesialisasi' => $data['spesialisasi'] ?? null,
                'status_aktif' => $request->boolean('status_aktif'),
                'foto_profil' => $foto,
            ]);
        });

        return redirect()->route('admin.terapis.index')->with('success', 'Data terapis berhasil diperbarui.');
    }

    public function destroy(Terapis $terapi): RedirectResponse
    {
        if ($terapi->reservasi()->exists()) {
            // punya riwayat reservasi (FK cascade akan menghapus riwayat) -> nonaktifkan saja
            $terapi->update(['status_aktif' => false]);

            return back()->with('success', 'Terapis memiliki riwayat reservasi sehingga hanya dinonaktifkan, tidak dihapus.');
        }

        DB::transaction(function () use ($terapi) {
            if ($terapi->foto_profil) {
                Storage::disk('public')->delete($terapi->foto_profil);
            }
            $user = $terapi->user;
            $terapi->delete();
            $user->delete();
        });

        return redirect()->route('admin.terapis.index')->with('success', 'Terapis berhasil dihapus.');
    }
}
