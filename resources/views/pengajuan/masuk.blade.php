@extends('layouts.app')
@section('title', 'Pengajuan Masuk')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <h1 class="text-xl font-bold text-blue-700 mb-1">Pengajuan Masuk</h1>
    <p class="text-sm text-gray-500 mb-6">Daftar pengguna yang ingin mengambil barang donasimu</p>

    @forelse($pengajuan as $p)
    <div class="border rounded-xl p-5 mb-4 shadow-sm">
        <div class="flex items-center gap-3 mb-3">
            @if($p->item->firstImage)
                <img src="{{ asset('storage/' . $p->item->firstImage->image_url) }}"
                     class="w-14 h-14 object-cover rounded-lg">
            @else
                <div class="w-14 h-14 bg-gray-200 rounded-lg flex items-center justify-center text-2xl">📦</div>
            @endif
            <div>
                <p class="font-bold text-sm">{{ $p->item->nama_barang }}</p>
                <p class="text-xs text-gray-500">📍 {{ $p->item->location->kecamatan ?? '-' }}</p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-3 mb-3 text-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($p->user->nama, 0, 2)) }}
                </div>
                <div>
                    <p class="font-semibold text-sm">{{ $p->user->nama }}</p>
                    <p class="text-xs text-gray-400">Diajukan: {{ $p->created_at->format('d M Y') }}</p>
                </div>
            </div>
            <p class="text-xs text-gray-600"><b>Alasan:</b> {{ $p->alasan_kebutuhan }}</p>
            <p class="text-xs text-gray-600 mt-1"><b>Metode:</b> {{ $p->metode_ambil === 'ambil_sendiri' ? 'Ambil Sendiri' : 'Pesan Antar' }}</p>
        </div>

        {{-- Tombol terima / tolak --}}
        <div class="flex gap-3">
            <form method="POST" action="{{ route('pengajuan.update', $p->id) }}">
                @csrf @method('PUT')
                <input type="hidden" name="status_pengajuan" value="accepted">
                <button class="px-5 py-2 bg-green-600 text-white text-sm rounded-lg font-semibold hover:bg-green-700 transition">
                    ✅ Terima
                </button>
            </form>
            <form method="POST" action="{{ route('pengajuan.update', $p->id) }}">
                @csrf @method('PUT')
                <input type="hidden" name="status_pengajuan" value="rejected">
                <button class="px-5 py-2 bg-red-500 text-white text-sm rounded-lg font-semibold hover:bg-red-600 transition">
                    ✕ Tolak
                </button>
            </form>
        </div>
    </div>
    @empty
        <div class="text-center text-gray-500 py-16">
            <div class="text-5xl mb-4">📭</div>
            <p>Belum ada pengajuan masuk.</p>
        </div>
    @endforelse
</div>
@endsection