@extends('layouts.app')
@section('title', 'Beri Ulasan')

@section('content')
<div class="max-w-xl mx-auto px-6 py-8">
    <div class="bg-[#0D1B5E] text-white px-6 py-4 rounded-t-xl">
        <h1 class="font-bold text-lg">⭐ Konfirmasi & Beri Ulasan</h1>
    </div>
    <div class="bg-white rounded-b-xl shadow p-6">
        <p class="text-sm text-gray-500 mb-4">
            Konfirmasi bahwa kamu sudah menerima barang <b>{{ $pengajuan->item->nama_barang }}</b>
            dari <b>{{ $pengajuan->item->user->nama }}</b>.
        </p>

        <form method="POST" action="{{ route('reviews.store') }}">
            @csrf
            <input type="hidden" name="pengajuan_donasi_id" value="{{ $pengajuan->id }}">

            {{-- Rating --}}
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-2">Rating</label>
                <div class="flex gap-2" id="star-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setRating({{ $i }})"
                                class="text-3xl text-gray-300 hover:text-yellow-400 transition star" data-val="{{ $i }}">
                            ★
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input" value="{{ old('rating') }}">
                @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Komentar --}}
            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-2">Komentar (opsional)</label>
                <textarea name="komentar" rows="4" maxlength="500"
                          placeholder="Bagaimana pengalaman donasi kamu?"
                          class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('komentar') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition">
                ✅ Konfirmasi Penerimaan & Kirim Ulasan
            </button>
        </form>
    </div>
</div>

<script>
function setRating(val) {
    document.getElementById('rating-input').value = val;
    document.querySelectorAll('.star').forEach(s => {
        s.classList.toggle('text-yellow-400', parseInt(s.dataset.val) <= val);
        s.classList.toggle('text-gray-300', parseInt(s.dataset.val) > val);
    });
}
</script>
@endsection