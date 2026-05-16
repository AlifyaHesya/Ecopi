@extends('layouts.app')
@section('title', 'Profil')

@section('content')
@php
    $user = auth()->user();
    $itemsTersedia = $user->items->where('status_barang', 'available');
    $itemsHabis = $user->items->whereIn('status_barang', ['taken','completed']);
    $pengajuanSaya = $user->pengajuan()->with('item.firstImage','item.location')->latest()->get();
@endphp

<div class="max-w-3xl mx-auto px-4 py-6">

    {{-- Header Profil --}}
    <div class="bg-white rounded-xl shadow mb-4 overflow-hidden">
        <div class="bg-[#0D1B5E] h-20 relative">
            <div class="absolute -bottom-8 left-5">
                <div class="w-16 h-16 rounded-full border-4 border-white overflow-hidden bg-gray-700 flex items-center justify-center">
                    @if($user->foto_profil)
                        <img src="{{ asset('storage/' . $user->foto_profil) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-white font-bold text-lg">{{ strtoupper(substr($user->nama, 0, 2)) }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="pt-10 pb-5 px-5 flex items-start justify-between">
            <div>
                <h2 class="font-bold text-base">{{ $user->nama }}</h2>
                <p class="text-xs text-gray-500">✉️ {{ $user->email }}</p>
                <p class="text-xs text-gray-500">📞 {{ $user->no_telepon ?? '-' }}</p>
                <p class="text-xs text-gray-500">📍 {{ $user->kecamatan ?? '-' }}</p>
            </div>
            <button onclick="document.getElementById('edit-form').classList.toggle('hidden')"
                    class="bg-red-500 text-white px-3 py-1.5 rounded text-xs font-semibold hover:bg-red-600 transition">
                Ubah Profile
            </button>
        </div>
    </div>

    {{-- Form Edit Profil --}}
    <div id="edit-form" class="hidden bg-white rounded-xl shadow p-5 mb-4">
        <h3 class="font-bold text-gray-700 mb-3 text-sm">Edit Profil</h3>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- Upload Foto --}}
            <div class="mb-3">
                <label class="block text-xs font-medium text-gray-600 mb-1">Foto Profil</label>
                <div class="flex items-center gap-3">
                    <div id="foto-preview" class="w-12 h-12 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center flex-shrink-0">
                        @if($user->foto_profil)
                            <img src="{{ asset('storage/' . $user->foto_profil) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-gray-600 font-bold">{{ strtoupper(substr($user->nama, 0, 2)) }}</span>
                        @endif
                    </div>
                    <label class="cursor-pointer bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded text-xs font-medium text-gray-600 transition">
                        📷 Pilih Foto
                        <input type="file" name="foto_profil" accept="image/*" class="hidden" onchange="previewFoto(this)">
                    </label>
                    <span class="text-xs text-gray-400">Maks 2MB</span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ $user->nama }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">No. Telepon</label>
                    <input type="text" name="no_telepon" value="{{ $user->no_telepon }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ $user->kecamatan }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <button type="submit"
                    class="mt-3 bg-[#0D1B5E] text-white px-5 py-2 rounded text-sm font-semibold hover:bg-blue-900 transition">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- SECTION: PEMANTAUAN PENGAJUAN SAYA     --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow mb-4">
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Pengajuan Saya</h3>
            <a href="{{ route('pengajuan.index') }}" class="text-xs text-blue-600 hover:underline">Lihat Semua →</a>
        </div>

        @if($pengajuanSaya->isEmpty())
            <div class="px-5 py-6 text-center text-gray-400 text-sm">
                Belum ada pengajuan.
                <a href="{{ route('home') }}" class="text-blue-600 hover:underline block mt-1">Cari barang donasi →</a>
            </div>
        @else
            {{-- Tab status --}}
            <div class="flex gap-1 px-5 pt-3 text-xs overflow-x-auto">
                @php
                    $tabs = [
                        'Semua'    => null,
                        'Pending'  => 'pending',
                        'Diterima' => 'accepted',
                        'Ditolak'  => 'rejected',
                        'Selesai'  => 'completed',
                    ];
                @endphp
                @foreach($tabs as $label => $val)
                    @php $count = $val ? $pengajuanSaya->where('status_pengajuan', $val)->count() : $pengajuanSaya->count(); @endphp
                    <button onclick="filterPengajuan('{{ $val ?? 'semua' }}')"
                            class="px-3 py-1 rounded-full border font-medium whitespace-nowrap mr-1 tab-pengajuan
                                   {{ $val === null ? 'bg-[#0D1B5E] text-white border-[#0D1B5E]' : 'text-gray-500 border-gray-300' }}"
                            data-tab="{{ $val ?? 'semua' }}">
                        {{ $label }} ({{ $count }})
                    </button>
                @endforeach
            </div>

            <div class="px-5 py-3 space-y-3" id="pengajuan-list">
                @foreach($pengajuanSaya as $p)
                @php
                    $statusColor = [
                        'pending'   => 'bg-yellow-100 text-yellow-700',
                        'accepted'  => 'bg-green-100 text-green-700',
                        'rejected'  => 'bg-red-100 text-red-700',
                        'completed' => 'bg-blue-100 text-blue-700',
                    ][$p->status_pengajuan] ?? '';
                    $statusLabel = [
                        'pending'   => '⏳ Menunggu',
                        'accepted'  => '✅ Diterima',
                        'rejected'  => '❌ Ditolak',
                        'completed' => '🎉 Selesai',
                    ][$p->status_pengajuan] ?? '-';
                @endphp
                <div class="flex items-center gap-3 border rounded-lg p-3 pengajuan-item"
                     data-status="{{ $p->status_pengajuan }}">
                    @if($p->item->firstImage)
                        <img src="{{ asset('storage/' . $p->item->firstImage->image_url) }}"
                             class="w-12 h-12 object-cover rounded-lg flex-shrink-0">
                    @else
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center text-xl flex-shrink-0">📦</div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm truncate">{{ $p->item->nama_barang }}</p>
                        <p class="text-xs text-gray-400">{{ $p->created_at->format('d M Y') }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-1 flex-shrink-0">
                        @if($p->status_pengajuan === 'accepted' && $p->chat)
                            <a href="{{ route('chat.show', $p->chat->id) }}"
                               class="text-xs bg-[#0D1B5E] text-white px-2 py-1 rounded font-semibold">💬 Chat</a>
                        @endif
                        @if($p->status_pengajuan === 'accepted' && !$p->review)
                            <a href="{{ route('reviews.create', ['pengajuan' => $p->id]) }}"
                               class="text-xs bg-green-600 text-white px-2 py-1 rounded font-semibold">⭐ Ulasan</a>
                        @endif
                        @if($p->status_pengajuan === 'pending')
                            <form method="POST" action="{{ route('pengajuan.destroy', $p->id) }}"
                                  onsubmit="return confirm('Batalkan pengajuan ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs bg-red-500 text-white px-2 py-1 rounded font-semibold w-full">Batal</button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- SECTION: DONASI BARANG SAYA            --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow mb-4">
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Donasi Barang Saya</h3>
            <a href="{{ route('items.create') }}" class="text-xs text-blue-600 hover:underline">+ Donasi Baru</a>
        </div>

        {{-- Tab Tersedia / Pengajuan Masuk / Habis --}}
        <div class="flex border-b px-5 text-xs">
            <button onclick="showTab('tersedia')"
                    class="py-2 px-3 font-semibold text-blue-700 border-b-2 border-blue-700" id="tab-tersedia">
                Tersedia ({{ $itemsTersedia->count() }})
            </button>
            <a href="{{ route('pengajuan.masuk') }}"
               class="py-2 px-3 text-gray-500 hover:text-gray-700 font-medium">
                Pengajuan Masuk
            </a>
            <button onclick="showTab('habis')"
                    class="py-2 px-3 text-gray-500 hover:text-gray-700 font-medium" id="tab-habis">
                Habis ({{ $itemsHabis->count() }})
            </button>
        </div>

        {{-- Tersedia --}}
        <div id="content-tersedia" class="px-5 py-3 space-y-3">
            @forelse($itemsTersedia as $item)
            <div class="flex items-center gap-3 border rounded-lg p-3">
                @if($item->firstImage)
                    <img src="{{ asset('storage/' . $item->firstImage->image_url) }}"
                         class="w-14 h-14 object-cover rounded-lg flex-shrink-0">
                @else
                    <div class="w-14 h-14 bg-gray-200 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">📦</div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm truncate">{{ $item->nama_barang }}</p>
                    @if($item->ukuran)
                        <span class="text-xs border px-2 py-0.5 rounded mr-1">✏️ Ukuran: {{ $item->ukuran }}</span>
                    @endif
                    @if($item->variasi)
                        <span class="text-xs border px-2 py-0.5 rounded">🌐 Variasi: {{ $item->variasi }}</span>
                    @endif
                </div>
                <a href="{{ route('items.show', $item->id) }}"
                   class="text-blue-600 text-xs font-semibold hover:underline flex-shrink-0">Lihat Detail ›</a>
            </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Belum ada barang yang didonasikan.</p>
            @endforelse
        </div>

        {{-- Habis --}}
        <div id="content-habis" class="hidden px-5 py-3 space-y-3">
            @forelse($itemsHabis as $item)
            <div class="flex items-center gap-3 border rounded-lg p-3 opacity-70">
                @if($item->firstImage)
                    <img src="{{ asset('storage/' . $item->firstImage->image_url) }}"
                         class="w-14 h-14 object-cover rounded-lg flex-shrink-0 grayscale">
                @else
                    <div class="w-14 h-14 bg-gray-200 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">📦</div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm truncate">{{ $item->nama_barang }}</p>
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                        {{ ucfirst($item->status_barang) }}
                    </span>
                </div>
                <a href="{{ route('items.show', $item->id) }}"
                   class="text-blue-600 text-xs font-semibold hover:underline flex-shrink-0">Lihat Detail ›</a>
            </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Belum ada barang yang habis.</p>
            @endforelse
        </div>
    </div>

</div>

<script>
// Preview foto profil
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('foto-preview').innerHTML =
                `<img src="${e.target.result}" class="w-full h-full object-cover rounded-full">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Tab Donasi Barang (Tersedia / Habis)
function showTab(tab) {
    document.getElementById('content-tersedia').classList.toggle('hidden', tab !== 'tersedia');
    document.getElementById('content-habis').classList.toggle('hidden', tab !== 'habis');
    document.getElementById('tab-tersedia').className =
        tab === 'tersedia'
        ? 'py-2 px-3 font-semibold text-blue-700 border-b-2 border-blue-700'
        : 'py-2 px-3 text-gray-500 hover:text-gray-700 font-medium';
    document.getElementById('tab-habis').className =
        tab === 'habis'
        ? 'py-2 px-3 font-semibold text-blue-700 border-b-2 border-blue-700'
        : 'py-2 px-3 text-gray-500 hover:text-gray-700 font-medium';
}

// Filter pengajuan berdasarkan status
function filterPengajuan(status) {
    document.querySelectorAll('.tab-pengajuan').forEach(btn => {
        btn.className = btn.dataset.tab === status
            ? 'px-3 py-1 rounded-full border font-medium whitespace-nowrap mr-1 tab-pengajuan bg-[#0D1B5E] text-white border-[#0D1B5E]'
            : 'px-3 py-1 rounded-full border font-medium whitespace-nowrap mr-1 tab-pengajuan text-gray-500 border-gray-300';
    });
    document.querySelectorAll('.pengajuan-item').forEach(item => {
        item.style.display = (status === 'semua' || item.dataset.status === status) ? '' : 'none';
    });
}
</script>
@endsection