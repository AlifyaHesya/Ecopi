@extends('layouts.app')
@section('title', 'Beranda')

@section('content')

{{-- Hero Banner --}}
<div class="bg-gradient-to-r from-gray-100 to-gray-200 px-12 py-10 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-700 leading-snug" style="font-family: Georgia, serif">
            Barangmu<br>
            Bisa Jadi <span class="text-green-600">Manfaat</span><br>
            Bagi Orang Lain
        </h1>
        <a href="{{ route('items.create') }}"
           class="mt-6 inline-block bg-[#0D1B5E] text-white px-6 py-2 rounded-full text-sm font-semibold hover:bg-blue-900 transition">
            Donasikan Barangmu →
        </a>
    </div>
    <div class="text-8xl">📦</div>
</div>

{{-- Filter Kategori --}}
<div class="px-8 py-4 bg-[#0D1B5E]">
    <div class="flex gap-3 overflow-x-auto">
        @foreach(['Semua','Elektronik','Pakaian','Buku','Perabotan','Dll'] as $kat)
            <a href="{{ route('items.search', ['kategori' => $kat === 'Semua' ? '' : $kat]) }}"
               class="px-4 py-1.5 rounded-full text-sm font-medium whitespace-nowrap
                      {{ request('kategori') === $kat || ($kat === 'Semua' && !request('kategori'))
                         ? 'bg-white text-[#0D1B5E]'
                         : 'border border-white text-white hover:bg-white hover:text-[#0D1B5E]' }} transition">
                {{ $kat }}
            </a>
        @endforeach
    </div>
</div>

{{-- Produk Donasi --}}
<div class="px-8 py-8">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Produk Donasi</h2>

    @if($items->isEmpty())
        <div class="text-center text-gray-500 py-16">
            <div class="text-5xl mb-4">📭</div>
            <p>Belum ada barang donasi tersedia.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($items as $item)
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                {{-- Foto --}}
                <div class="relative">
                    @if($item->firstImage)
                        <img src="{{ asset('storage/' . $item->firstImage->image_url) }}"
                             alt="{{ $item->nama_barang }}"
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-4xl">📦</div>
                    @endif
                    <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                        Available
                    </span>
                </div>

                {{-- Info --}}
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 text-sm mb-1">{{ $item->nama_barang }}</h3>
                    <p class="text-xs text-gray-500 mb-3">
                        📍 {{ $item->location->kecamatan ?? '-' }}, {{ $item->location->wilayah ?? '-' }}
                    </p>
                    <a href="{{ route('items.show', $item->id) }}"
                       class="block w-full text-center bg-[#0D1B5E] text-white text-xs font-semibold py-2 rounded hover:bg-blue-900 transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

@endsection