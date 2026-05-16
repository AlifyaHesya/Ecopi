@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">
    <div class="mb-10">
        <a href="{{ route('items.index') }}" class="inline-flex items-center text-gray-500 hover:text-[#040B7A] mb-4 transition text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke semua kategori
        </a>
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 font-logo">Hasil Pencarian</h1>
                <p class="text-gray-500 mt-2">
                    Menampilkan hasil untuk: <span class="text-[#040B7A] font-bold">"{{ request('search') }}"</span>
                </p>
            </div>
            <p class="text-sm text-gray-400">
                Ditemukan {{ $items->count() }} barang yang cocok
            </p>
        </div>
    </div>

    @if($items->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($items as $item)
                <div class="bg-white rounded-[2rem] shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group flex flex-col h-full border border-gray-100">
                    <div class="relative h-56 overflow-hidden">
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-[#A3C983] text-white text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-sm">
                                Available
                            </span>
                        </div>
                        
                        @php
                            $imageUrl = $item->images->first() 
                                ? asset('storage/' . $item->images->first()->image_url) 
                                : asset('images/no-image.jpg');
                        @endphp

                        <img src="{{ $imageUrl }}" 
                             alt="{{ $item->nama_barang }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>

                    <div class="p-6 flex flex-col flex-1">
                        <div class="mb-auto">
                            <span class="text-[#78B83E] text-xs font-bold uppercase tracking-wider">{{ $item->kategori }}</span>
                            <h2 class="text-xl font-bold text-gray-800 mt-1 group-hover:text-[#040B7A] transition-colors line-clamp-1">
                                {{ $item->nama_barang }}
                            </h2>
                            
                            <div class="flex items-center text-gray-500 mt-3 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $item->location->kecamatan }}
                            </div>
                        </div>

                        <a href="{{ route('items.show', $item) }}" 
                           class="mt-6 block w-full bg-[#1a1f71] text-white text-center py-3 rounded-xl font-semibold hover:bg-blue-900 transition-all active:scale-[0.98]">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $items->appends(['search' => request('search')])->links() }}
        </div>

    @else
        <div class="text-center py-20 bg-white rounded-[3rem] border border-dashed border-gray-200">
            <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Barang tidak ditemukan</h3>
            <p class="text-gray-500 mt-2 max-w-xs mx-auto">Maaf, kami tidak menemukan barang yang cocok dengan kata kunci tersebut. Coba kata kunci lain atau cek kategori lainnya.</p>
            <a href="{{ route('items.index') }}" class="mt-8 inline-block bg-[#040B7A] text-white px-8 py-3 rounded-2xl font-bold hover:shadow-lg transition-all">
                Lihat Semua Barang
            </a>
        </div>
    @endif
</div>
@endsection