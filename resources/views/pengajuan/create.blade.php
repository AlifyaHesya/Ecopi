@extends('layouts.app')
@section('title', 'Form Pengajuan')

@section('content')
    <div class="bg-[#0D1B5E] text-white px-6 py-4 flex items-center gap-3">
        <a href="{{ route('items.show', $item->id) }}" class="text-white hover:text-gray-300">←</a>
        <h1 class="font-bold text-lg">Form Pengajuan</h1>
    </div>

    <div class="max-w-5xl mx-auto px-6 py-8 grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Form Kiri --}}
        <div class="md:col-span-2">
            <form method="POST" action="{{ route('pengajuan.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">

                {{-- 1. Informasi Penerima --}}
                <div class="border rounded-xl p-6 mb-4">
                    <h2 class="font-semibold text-blue-700 mb-4">① Informasi Penerima</h2>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Penerima</label>
                        <input type="text" name="nama_penerima" value="{{ auth()->user()->nama }}"
                            placeholder="Masukkan nama lengkap penerima"
                            class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nomor Telepon</label>
                        <input type="text" name="no_telepon" value="{{ auth()->user()->no_telepon }}" placeholder="+62"
                            class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- 2. Bukti Diri --}}
                <div class="border rounded-xl p-6 mb-4">
                    <h2 class="font-semibold text-blue-700 mb-4">② Bukti Diri</h2>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Bukti Identitas (KTP/KK)</label>
                        <label class="flex items-center gap-2 border rounded px-3 py-2 cursor-pointer hover:bg-gray-50">
                            <span class="text-gray-400 text-sm" id="file-label">
                                ☁️ Klik untuk unggah file
                            </span>

                            <input type="file" name="bukti_identitas" accept=".jpg,.jpeg,.png,.pdf" class="hidden"
                                onchange="previewIdentitas(event)">
                        </label>

                        {{-- Preview --}}
                        <div id="preview-identitas" class="mt-3 hidden">
                            <img id="preview-image" class="w-40 h-40 object-cover rounded-lg border shadow-sm">

                            <div id="preview-pdf"
                                class="hidden px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600 font-medium">
                                📄 File PDF berhasil dipilih
                            </div>
                        </div>
                        @error('bukti_identitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">
                            Alasan <span class="text-gray-400 text-xs float-right" id="char-count">0/300</span>
                        </label>
                        <textarea name="alasan_kebutuhan" rows="4" maxlength="300"
                            placeholder="Berikan alasan donasi ini diajukan"
                            oninput="document.getElementById('char-count').textContent = this.value.length + '/300'"
                            class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                         @error('alasan_kebutuhan') border-red-500 @enderror">{{ old('alasan_kebutuhan') }}</textarea>
                        @error('alasan_kebutuhan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- 3. Metode Pengambilan --}}
                <div class="border rounded-xl p-6 mb-6">
                    <h2 class="font-semibold text-blue-700 mb-4">③ Metode Pengambilan</h2>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <label class="border rounded-xl p-3 cursor-pointer hover:border-blue-500 transition"
                            id="label-ambil">
                            <input type="radio" name="metode_ambil" value="ambil_sendiri" class="accent-blue-700"
                                onchange="toggleMetode()" {{ old('metode_ambil', 'ambil_sendiri') === 'ambil_sendiri' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-semibold">🤝 Ambil Sendiri</span>
                            <p class="text-xs text-gray-400 mt-1 ml-5">Penerima akan mengambil barang langsung ke lokasi.
                            </p>
                        </label>
                        <label class="border rounded-xl p-3 cursor-pointer hover:border-blue-500 transition"
                            id="label-antar">
                            <input type="radio" name="metode_ambil" value="pesan_antar" class="accent-blue-700"
                                onchange="toggleMetode()" {{ old('metode_ambil') === 'pesan_antar' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-semibold">📦 Pesan Antar</span>
                            <p class="text-xs text-gray-400 mt-1 ml-5">Barang akan diantar ke alamat penerima.</p>
                        </label>
                    </div>

                    {{-- Alamat (muncul kalau pilih pesan antar) --}}
                    <div id="alamat-section" class="{{ old('metode_ambil') === 'pesan_antar' ? '' : 'hidden' }}">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Alamat Lengkap</label>
                            <input type="text" name="alamat_kirim" value="{{ old('alamat_kirim') }}"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Kelurahan</label>
                                <input type="text" name="kelurahan_kirim" value="{{ old('kelurahan_kirim') }}"
                                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Kecamatan</label>
                                <input type="text" name="kecamatan_kirim" value="{{ old('kecamatan_kirim') }}"
                                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Kota</label>
                                <input type="text" name="kota_kirim" value="{{ old('kota_kirim') }}"
                                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-[#0D1B5E] text-white py-3 rounded-xl font-semibold hover:bg-blue-900 transition">
                    Kirim Pengajuan
                </button>
            </form>
        </div>

        {{-- Ringkasan Barang Kanan --}}
        <div>
            <div class="bg-[#0D1B5E] text-white rounded-t-xl p-4 text-center font-semibold">
                🛍️ Ringkasan Barang
            </div>
            <div class="bg-blue-800 text-white p-4 rounded-b-xl">
                <div class="flex gap-3 items-start mb-4">
                    @if($item->firstImage)
                        <img src="{{ asset('storage/' . $item->firstImage->image_url) }}"
                            class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-16 bg-blue-600 rounded-lg flex items-center justify-center text-2xl">📦</div>
                    @endif
                    <div>
                        <p class="font-bold">{{ $item->nama_barang }}</p>
                        <span class="text-xs bg-blue-500 px-2 py-0.5 rounded-full">Kuantitas: 1</span>
                    </div>
                </div>
                <div class="text-xs space-y-2">
                    @if($item->ukuran)
                        <div class="flex justify-between">
                            <span class="text-blue-300">Ukuran</span>
                            <span>{{ $item->ukuran }}</span>
                        </div>
                    @endif
                    @if($item->variasi)
                        <div class="flex justify-between">
                            <span class="text-blue-300">Variasi</span>
                            <span>{{ $item->variasi }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-blue-300">Lokasi</span>
                        <span class="text-right">{{ $item->location->kecamatan ?? '-' }},
                            {{ $item->location->wilayah ?? '-' }}</span>
                    </div>
                </div>
                <button onclick="document.querySelector('form button[type=submit]').click()"
                    class="w-full mt-4 bg-white text-[#0D1B5E] py-2 rounded-lg font-bold text-sm hover:bg-gray-100 transition">
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleMetode() {
            const isPesanAntar =
                document.querySelector('input[name="metode_ambil"]:checked').value === 'pesan_antar';

            document.getElementById('alamat-section')
                .classList.toggle('hidden', !isPesanAntar);
        }

        // Preview bukti identitas
        function previewIdentitas(event) {
            const file = event.target.files[0];

            if (!file) return;

            const previewContainer = document.getElementById('preview-identitas');
            const previewImage = document.getElementById('preview-image');
            const previewPdf = document.getElementById('preview-pdf');
            const fileLabel = document.getElementById('file-label');

            previewContainer.classList.remove('hidden');

            fileLabel.innerText = file.name;

            // Jika file gambar
            if (file.type.startsWith('image/')) {

                previewImage.classList.remove('hidden');
                previewPdf.classList.add('hidden');

                const reader = new FileReader();

                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                };

                reader.readAsDataURL(file);

            } else {
                // Jika PDF
                previewImage.classList.add('hidden');
                previewPdf.classList.remove('hidden');
            }
        }
    </script>
@endsection