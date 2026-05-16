@extends('layouts.app')
@section('title', 'Pengajuan Saya')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8">
    <h1 class="text-xl font-bold text-blue-700">Pengajuan Saya</h1>
    <p class="text-sm text-gray-500 mb-4">Kelola semua pengajuan barang donasi</p>

    {{-- Tab filter --}}
    <div class="flex gap-4 border-b mb-6 text-sm">
        @foreach(['Semua' => '', 'Pending' => 'pending', 'Diterima' => 'accepted', 'Ditolak' => 'rejected', 'Selesai' => 'completed'] as $label => $val)
            <a href="{{ route('pengajuan.index', ['status' => $val]) }}"
               class="pb-2 font-medium {{ request('status') === $val ? 'border-b-2 border-blue-700 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
                <span class="text-xs bg-gray-100 px-1.5 rounded-full ml-1">
                    {{ $pengajuan->where('status_pengajuan', $val ?: null)->count() ?: $pengajuan->count() }}
                </span>
            </a>
        @endforeach
    </div>

    @forelse($pengajuan as $p)
    <div class="border rounded-xl p-5 mb-4 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            @if($p->item->firstImage)
                <img src="{{ asset('storage/' . $p->item->firstImage->image_url) }}"
                     class="w-14 h-14 object-cover rounded-lg">
            @else
                <div class="w-14 h-14 bg-gray-200 rounded-lg flex items-center justify-center text-2xl">📦</div>
            @endif
            <div>
                <p class="font-bold text-sm">{{ $p->item->nama_barang }}</p>
                <p class="text-xs text-gray-500">📍 {{ $p->item->location->kecamatan ?? '-' }}</p>
                @php
                    $statusColor = [
                        'pending'   => 'bg-yellow-100 text-yellow-700',
                        'accepted'  => 'bg-green-100 text-green-700',
                        'rejected'  => 'bg-red-100 text-red-700',
                        'completed' => 'bg-blue-100 text-blue-700',
                    ][$p->status_pengajuan] ?? 'bg-gray-100 text-gray-700';
                    $statusLabel = [
                        'pending'   => '⏳ Menunggu Konfirmasi',
                        'accepted'  => '✅ Pengajuan Diterima',
                        'rejected'  => '❌ Pengajuan Ditolak',
                        'completed' => '🎉 Selesai',
                    ][$p->status_pengajuan] ?? '-';
                @endphp
                <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </div>
        </div>

        {{-- Progress bar --}}
        @php $steps = ['Diajukan','Menunggu','Koordinasi','Ambil Barang','Selesai'];
             $activeStep = ['pending'=>1,'accepted'=>2,'completed'=>4][$p->status_pengajuan] ?? 0; @endphp
        <div class="flex items-center justify-between mb-4 text-xs">
            @foreach($steps as $i => $step)
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold
                        {{ $i <= $activeStep ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                        {{ $i + 1 }}
                    </div>
                    <span class="{{ $i <= $activeStep ? 'text-green-600 font-semibold' : 'text-gray-400' }} mt-1">
                        {{ $step }}
                    </span>
                </div>
                @if(!$loop->last)
                    <div class="flex-1 h-0.5 {{ $i < $activeStep ? 'bg-green-500' : 'bg-gray-200' }} mx-1"></div>
                @endif
            @endforeach
        </div>

        {{-- Info detail --}}
        <div class="grid grid-cols-2 gap-3 text-xs bg-gray-50 rounded-lg p-3 mb-3">
            <div><span class="text-gray-400">Tanggal Pengajuan</span><br><b>{{ $p->created_at->format('d M Y') }}</b></div>
            <div><span class="text-gray-400">Status Barang</span><br><b>{{ ucfirst($p->item->status_barang) }}</b></div>
            <div><span class="text-gray-400">Metode Ambil</span><br><b>{{ $p->metode_ambil === 'ambil_sendiri' ? 'COD / Langsung' : 'Pesan Antar' }}</b></div>
            <div><span class="text-gray-400">Pendonasi</span><br><b>{{ $p->item->user->nama }}</b></div>
        </div>

        {{-- Tombol aksi --}}
        <div class="flex gap-2">
            @if($p->status_pengajuan === 'accepted' && $p->chat)
                <a href="{{ route('chat.show', $p->chat->id) }}"
                   class="px-4 py-1.5 bg-[#0D1B5E] text-white text-xs rounded-lg font-semibold">💬 Buka Chat</a>
            @endif
            <a href="{{ route('items.show', $p->item->id) }}"
               class="px-4 py-1.5 border border-gray-300 text-gray-600 text-xs rounded-lg font-semibold">🔍 Detail Barang</a>
            @if($p->status_pengajuan === 'pending')
                <form method="POST" action="{{ route('pengajuan.destroy', $p->id) }}"
                      onsubmit="return confirm('Batalkan pengajuan ini?')">
                    @csrf @method('DELETE')
                    <button class="px-4 py-1.5 bg-red-500 text-white text-xs rounded-lg font-semibold">✕ Batalkan</button>
                </form>
            @endif
            @if($p->status_pengajuan === 'accepted' && !$p->review)
                <a href="{{ route('reviews.create', ['pengajuan' => $p->id]) }}"
                   class="px-4 py-1.5 bg-green-600 text-white text-xs rounded-lg font-semibold">⭐ Konfirmasi & Beri Ulasan</a>
            @endif
        </div>
    </div>
    @empty
        <div class="text-center text-gray-500 py-16">
            <div class="text-5xl mb-4">📋</div>
            <p>Belum ada pengajuan.</p>
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline text-sm mt-2 inline-block">Cari barang donasi →</a>
        </div>
    @endforelse
</div>
@endsection