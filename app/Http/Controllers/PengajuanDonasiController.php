<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Item;
use App\Models\PengajuanDonasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanDonasiController extends Controller
{
    // Menampilkan semua pengajuan milik user yang login
    public function index()
    {
        $pengajuan = PengajuanDonasi::with('item.firstImage', 'item.location')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pengajuan.index', compact('pengajuan'));
    }

    // Form pengajuan barang
    public function create(Request $request)
    {
        $item = Item::with('location')->findOrFail($request->item);

        // Cegah pemilik barang mengajukan barangnya sendiri
        if ($item->user_id === Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa mengajukan barang milikmu sendiri.');
        }

        // Cegah pengajuan ganda
        $sudahAjukan = PengajuanDonasi::where('item_id', $item->id)
            ->where('user_id', Auth::id())
            ->whereIn('status_pengajuan', ['pending', 'accepted'])
            ->exists();

        if ($sudahAjukan) {
            return back()->with('error', 'Kamu sudah mengajukan barang ini.');
        }

        return view('pengajuan.create', compact('item'));
    }

    // Simpan pengajuan baru
    public function store(Request $request)
    {
        $request->validate([
            'item_id'          => 'required|exists:items,id',
            'alasan_kebutuhan' => 'required|string|max:300',
            'bukti_identitas'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'metode_ambil'     => 'required|in:ambil_sendiri,pesan_antar',
            'alamat_kirim'     => 'required_if:metode_ambil,pesan_antar',
            'kelurahan_kirim'  => 'required_if:metode_ambil,pesan_antar',
            'kecamatan_kirim'  => 'required_if:metode_ambil,pesan_antar',
            'kota_kirim'       => 'required_if:metode_ambil,pesan_antar',
        ]);

        // Upload bukti identitas
        $buktiPath = $request->file('bukti_identitas')->store('bukti', 'public');

        PengajuanDonasi::create([
            'item_id'          => $request->item_id,
            'user_id'          => Auth::id(),
            'alasan_kebutuhan' => $request->alasan_kebutuhan,
            'bukti_identitas'  => $buktiPath,
            'metode_ambil'     => $request->metode_ambil,
            'alamat_kirim'     => $request->alamat_kirim,
            'kelurahan_kirim'  => $request->kelurahan_kirim,
            'kecamatan_kirim'  => $request->kecamatan_kirim,
            'kota_kirim'       => $request->kota_kirim,
            'status_pengajuan' => 'pending',
        ]);

        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan berhasil dikirim! Tunggu konfirmasi dari pendonasi.');
    }

    // Detail satu pengajuan
    public function show(PengajuanDonasi $pengajuanDonasi)
    {
        $pengajuanDonasi->load('item.images', 'item.location', 'user');
        return view('pengajuan.show', compact('pengajuanDonasi'));
    }

    // Terima atau tolak pengajuan (sisi PENDONASI)
    public function update(Request $request, PengajuanDonasi $pengajuanDonasi)
    {
        $request->validate([
            'status_pengajuan' => 'required|in:accepted,rejected',
        ]);

        // Pastikan hanya pemilik barang yang bisa update
        if ($pengajuanDonasi->item->user_id !== Auth::id()) {
            abort(403);
        }

        $pengajuanDonasi->update([
            'status_pengajuan' => $request->status_pengajuan,
        ]);

        if ($request->status_pengajuan === 'accepted') {
            // Ubah status barang jadi reserved
            $pengajuanDonasi->item->update(['status_barang' => 'reserved']);

            // Tolak semua pengajuan lain untuk barang yang sama
            PengajuanDonasi::where('item_id', $pengajuanDonasi->item_id)
                ->where('id', '!=', $pengajuanDonasi->id)
                ->where('status_pengajuan', 'pending')
                ->update(['status_pengajuan' => 'rejected']);

            // Buat chat antara pendonasi dan penerima
            Chat::create([
                'item_id'       => $pengajuanDonasi->item_id,
                'pengajuan_id'  => $pengajuanDonasi->pengajuan_id,
                'sender_id'     => Auth::id(),                      // pendonasi
                'receiver_id'   => $pengajuanDonasi->user_id,       // penerima
            ]);

            return back()->with('success', 'Pengajuan diterima! Chat telah dibuka.');
        }

        return back()->with('success', 'Pengajuan ditolak.');
    }

    // Batalkan pengajuan (sisi PENERIMA)
    public function destroy(PengajuanDonasi $pengajuanDonasi)
    {
        if ($pengajuanDonasi->user_id !== Auth::id()) {
            abort(403);
        }

        if ($pengajuanDonasi->status_pengajuan !== 'pending') {
            return back()->with('error', 'Pengajuan yang sudah diproses tidak bisa dibatalkan.');
        }

        $pengajuanDonasi->delete();

        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan berhasil dibatalkan.');
    }

    // Halaman status pengajuan milik user
    public function status()
    {
        $pengajuan = PengajuanDonasi::with('item.firstImage', 'item.location', 'item.user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pengajuan.status', compact('pengajuan'));
    }

    // Daftar pengajuan masuk untuk barang milik pendonasi
    public function masuk()
    {
        $pengajuan = PengajuanDonasi::with('item.firstImage', 'user')
            ->whereHas('item', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->where('status_pengajuan', 'pending')
            ->latest()
            ->get();

        return view('pengajuan.masuk', compact('pengajuan'));
    }
}