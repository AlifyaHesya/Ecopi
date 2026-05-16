@extends('layouts.app')
@section('title', 'Donasikan Barang')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">
    <div class="bg-[#0D1B5E] text-white px-6 py-4 rounded-t-xl flex items-center gap-3">
        <a href="{{ route('home') }}" class="text-white hover:text-gray-300">←</a>
        <h1 class="font-bold text-lg">Form Donasi Barang</h1>
    </div>

    <div class="bg-white rounded-b-xl shadow p-8">
        <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Upload Foto --}}
            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-2">Tambahkan Gambar Produk</label>
                <label class="block border-2 border-dashed border-gray-300 rounded-xl p-10 text-center cursor-pointer hover:border-blue-400 transition">
                    <div class="text-4xl mb-2">⬆️</div>
                    <span class="bg-blue-600 text-white px-4 py-1.5 rounded text-sm font-semibold">Unggah</span>
                    <p class="text-gray-400 text-sm mt-2">Masukkan gambar produkmu di sini</p>
                    <input type="file" name="images[]" multiple accept="image/*" class="hidden"
       onchange="previewImages(this)">
                </label>
                {{-- Preview foto --}}
                <div id="preview" class="flex gap-2 mt-3 flex-wrap"></div>
                @error('images.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Informasi Produk --}}
            <div class="border rounded-xl p-6 mb-6">
                <h2 class="font-bold text-gray-700 mb-4">Informasi Produk</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Produk</label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                               placeholder="Masukkan nama produk"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                      @error('nama_barang') border-red-500 @enderror">
                        @error('nama_barang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
                        <select name="kategori"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach(['Elektronik','Pakaian','Buku','Perabotan','Dll'] as $kat)
                                <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3"
                                  placeholder="Masukkan deskripsi berkaitan dengan produk"
                                  class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Variasi</label>
                        <input type="text" name="variasi" value="{{ old('variasi') }}"
                               placeholder="Contoh: Hitam, Merah"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Ukuran</label>
                        <input type="text" name="ukuran" value="{{ old('ukuran') }}"
                               placeholder="Contoh: 41, M, L"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- Lokasi --}}
            <div class="border rounded-xl p-6 mb-6">
                <h2 class="font-bold text-gray-700 mb-4">Lokasi Barang</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Wilayah</label>
                        <select name="wilayah" id="wilayah"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onchange="filterKecamatan()">
                            <option value="">-- Pilih Wilayah --</option>
                            <option value="Kota Malang">Kota Malang</option>
                            <option value="Kabupaten Malang">Kabupaten Malang</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Kecamatan</label>
                        <select name="location_id" id="kecamatan"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                       @error('location_id') border-red-500 @enderror">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}"
                                        data-wilayah="{{ $loc->wilayah }}"
                                        {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->kecamatan }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-[#0D1B5E] text-white py-3 rounded-xl font-semibold hover:bg-blue-900 transition">
                Posting Barang Donasi
            </button>
        </form>
    </div>
</div>

<script>
// Preview foto sebelum upload
function previewImages(input) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-20 h-20 object-cover rounded-lg border';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}

// Filter kecamatan berdasarkan wilayah
function filterKecamatan() {
    const wilayah = document.getElementById('wilayah').value;
    const kecamatanSelect = document.getElementById('kecamatan');
    const options = kecamatanSelect.querySelectorAll('option');
    
    let hasValidSelection = false;

    options.forEach(opt => {
        if (!opt.value) return;
        
        if (!wilayah || opt.dataset.wilayah === wilayah) {
            opt.style.display = '';
            if (opt.selected) hasValidSelection = true;
        } else {
            opt.style.display = 'none';
            if (opt.selected) opt.selected = false; // Batalkan pilihan jika wilayahnya tidak cocok
        }
    });

    // Hanya reset nilai kecamatan jika pilihan sebelumnya tidak ada dalam wilayah yang baru dipilih
    if (!hasValidSelection && wilayah) {
        kecamatanSelect.value = '';
    }
}

// Jalankan fungsi satu kali saat halaman pertama kali dibuka untuk menyesuaikan kondisi 'old' input
document.addEventListener("DOMContentLoaded", function() {
    if(document.getElementById('wilayah').value) {
        filterKecamatan();
    }
});
</script>
@endsection