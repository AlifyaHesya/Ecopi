<?php

namespace App\Http\Controllers;

use App\Models\PengajuanDonasi;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Form beri ulasan (setelah barang diterima)
    public function create(Request $request)
    {
        $pengajuan = PengajuanDonasi::with('item')->findOrFail($request->pengajuan);

        // Hanya penerima yang bisa memberi ulasan
        if ($pengajuan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('reviews.create', compact('pengajuan'));
    }

    // Simpan ulasan + konfirmasi penerimaan barang
    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_donasi_id' => 'required|exists:pengajuan_donasi,id',
            'rating'       => 'required|integer|min:1|max:5',
            'komentar'     => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanDonasi::findOrFail($request->pengajuan_id);

        if ($pengajuan->user_id !== Auth::id()) {
            abort(403);
        }

        // Simpan ulasan
        Review::create([
            'pengajuan_donasi_id' => $request->pengajuan_id,
            'user_id'      => Auth::id(),
            'rating'       => $request->rating,
            'komentar'     => $request->komentar,
        ]);

        // Update status pengajuan & barang jadi completed
        $pengajuan->update(['status_pengajuan' => 'completed']);
        $pengajuan->item->update(['status_barang' => 'completed']);

        return redirect()->route('pengajuan.index')
            ->with('success', 'Terima kasih! Ulasan berhasil dikirim dan donasi selesai.');
    }
}