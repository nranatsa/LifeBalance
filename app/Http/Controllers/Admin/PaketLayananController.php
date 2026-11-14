<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketLayanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaketLayananController extends Controller
{
    public function index(): View
    {
        $paket = PaketLayanan::withCount('reservasi')->latest()->paginate(10);

        return view('admin.paket.index', compact('paket'));
    }

    public function create(): View
    {
        return view('admin.paket.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto_paket')) {
            $data['foto_paket'] = $request->file('foto_paket')->store('paket', 'public');
        }

        PaketLayanan::create($data);

        return redirect()->route('admin.paket.index')->with('success', 'Paket layanan berhasil ditambahkan.');
    }

    public function show(PaketLayanan $paket): View
    {
        $paket->loadCount('reservasi');

        return view('admin.paket.show', compact('paket'));
    }

    public function edit(PaketLayanan $paket): View
    {
        return view('admin.paket.edit', compact('paket'));
    }

    public function update(Request $request, PaketLayanan $paket): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto_paket')) {
            if ($paket->foto_paket) {
                Storage::disk('public')->delete($paket->foto_paket);
            }
            $data['foto_paket'] = $request->file('foto_paket')->store('paket', 'public');
        }

        $paket->update($data);

        return redirect()->route('admin.paket.index')->with('success', 'Paket layanan berhasil diperbarui.');
    }

    public function destroy(PaketLayanan $paket): RedirectResponse
    {
        if ($paket->reservasi()->exists()) {
            // sudah punya riwayat reservasi: jangan dihapus, cukup di-unpublish
            $paket->update(['status_publish' => false]);

            return back()->with('success', 'Paket sudah memiliki riwayat reservasi sehingga hanya di-unpublish, tidak dihapus.');
        }

        if ($paket->foto_paket) {
            Storage::disk('public')->delete($paket->foto_paket);
        }
        $paket->delete();

        return redirect()->route('admin.paket.index')->with('success', 'Paket layanan berhasil dihapus.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'nama_paket' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'durasi_menit' => ['required', 'integer', 'min:15', 'max:480'],
            'harga' => ['required', 'numeric', 'min:0'],
            'foto_paket' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['status_publish'] = $request->boolean('status_publish');
        unset($data['foto_paket']);

        return $data;
    }
}
