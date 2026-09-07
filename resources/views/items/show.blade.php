@extends('layouts.app')
@section('title', $item->nama_barang)

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        {{-- Foto Barang --}}
        <div>
            @if($item->images->isNotEmpty())
                <img src="{{ asset('storage/' . $item->images->first()->image_url) }}"
                     alt="{{ $item->nama_barang }}"
                     class="w-full rounded-xl shadow object-cover max-h-96">
                @if($item->images->count() > 1)
                <div class="flex gap-2 mt-3">
                    @foreach($item->images->skip(1) as $img)
                        <img src="{{ asset('storage/' . $img->image_url) }}"
                             class="w-20 h-20 object-cover rounded-lg border cursor-pointer">
                    @endforeach
                </div>
                @endif
            @else
                <div class="w-full h-80 bg-gray-200 rounded-xl flex items-center justify-center text-6xl">📦</div>
            @endif
        </div>

        {{-- Info Barang --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $item->nama_barang }}</h1>

            <div class="space-y-2 text-sm mb-4">
                @if($item->ukuran)
                <div class="flex items-center gap-2">
                    <span class="text-blue-600">✏️ Ukuran</span>
                    <span class="bg-gray-100 px-3 py-0.5 rounded-full font-medium">{{ $item->ukuran }}</span>
                </div>
                @endif
                @if($item->variasi)
                <div class="flex items-center gap-2">
                    <span class="text-blue-600">🌐 Variasi</span>
                    <span class="bg-gray-100 px-3 py-0.5 rounded-full font-medium">{{ $item->variasi }}</span>
                </div>
                @endif
                <div class="flex items-center gap-2">
                    <span class="text-blue-600">📍 Lokasi</span>
                    <span class="text-gray-700">
                        {{ $item->location->kecamatan ?? '-' }}, {{ $item->location->wilayah ?? '-' }}
                    </span>
                </div>
            </div>

            {{-- Card Pendonor --}}
            <div class="border rounded-xl p-4 mb-4 bg-white shadow-sm">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Pendonor</h3>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center font-bold text-[#0D1B5E]">
                            {{ strtoupper(substr($item->user->nama, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">{{ $item->user->nama }}</h4>
                            <span class="text-xs text-emerald-600 flex items-center gap-1 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Pendonor Terpercaya
                            </span>
                        </div>
                    </div>
                    @auth
                        @if($item->user_id !== auth()->id())
                            <a href="{{ route('chat.open', [$item->user_id, $item->id]) }}"
                               class="bg-[#0D1B5E] hover:bg-blue-900 text-white px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 transition shadow-md active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Chat Pendonor
                            </a>
                        @else
                            <span class="text-xs text-gray-400 italic bg-gray-100 px-3 py-2 rounded-lg">
                                Ini barang donasi Anda
                            </span>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold">
                            Login untuk Chat
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Deskripsi --}}
            @if($item->deskripsi)
            <div class="bg-blue-50 rounded-xl p-4 mb-4 text-sm text-gray-700">
                <span class="font-semibold text-blue-700">📋 Deskripsi:</span>
                {{ $item->deskripsi }}
            </div>
            @endif

            {{-- Tombol --}}
            @auth
                @if(auth()->id() !== $item->user_id)
                    @if($item->status_barang === 'available')
                        <a href="{{ route('pengajuan.create', ['item' => $item->id]) }}"
                           class="block w-full text-center bg-[#0D1B5E] text-white py-3 rounded-xl font-semibold hover:bg-blue-900 transition">
                            Pilih Barang Ini
                        </a>
                    @else
                        <div class="bg-gray-100 text-gray-500 text-center py-3 rounded-xl font-semibold">
                            Barang sudah tidak tersedia
                        </div>
                    @endif
                @else
                    <div class="flex gap-3">
                        <a href="{{ route('items.edit', $item->id) }}"
                           class="flex-1 text-center border border-[#0D1B5E] text-[#0D1B5E] py-2 rounded-xl text-sm font-semibold hover:bg-blue-50 transition">
                            Edit Barang
                        </a>
                        <form method="POST" action="{{ route('items.destroy', $item->id) }}"
                              onsubmit="return confirm('Yakin hapus barang ini?')">
                            @csrf @method('DELETE')
                            <button class="px-4 py-2 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                    <a href="{{ route('pengajuan.masuk') }}"
                       class="block mt-3 text-center text-sm text-blue-600 hover:underline">
                        Lihat Pengajuan Masuk →
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="block text-center bg-[#0D1B5E] text-white py-3 rounded-xl font-semibold hover:bg-blue-900 transition">
                    Login untuk Mengajukan
                </a>
            @endauth
        </div>
    </div>

    {{-- Section Ulasan --}}
    @if($item->reviews && $item->reviews->count() > 0)
    <div class="mt-10">
        <h2 class="text-xl font-bold text-gray-800 mb-4">⭐ Ulasan Penerima</h2>
        <div class="space-y-4">
            @foreach($item->reviews as $review)
            <div class="bg-white border rounded-xl p-4 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center font-bold text-sm text-[#0D1B5E]">
                        {{ strtoupper(substr($review->user->nama, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-sm">{{ $review->user->nama }}</p>
                        <p class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="ml-auto flex gap-0.5">
                        @php $rating = (int) $review->rating; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} text-lg">★</span>
                        @endfor
                    </div>
                </div>
                @if($review->komentar)
                <p class="text-sm text-gray-600 mt-1">{{ $review->komentar }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection