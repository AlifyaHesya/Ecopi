@extends('layouts.app')
@section('title', 'Daftar Akun')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#1A1F8B] to-[#B1B1B1] flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl flex overflow-hidden">

        {{-- Sisi kiri - ilustrasi --}}
        <div class="hidden md:flex w-1/2 items-center justify-center p-10">
            <div class="text-center">
                <img src="{{ asset('images/download.png') }}" alt="Ilustrasi Donasi" class="w-52 mx-auto mb-4">
                <p class="text-[#0D1B5E] font-semibold text-lg">Barangmu Bisa Jadi</p>
                <p class="text-green-600 font-bold text-xl">Manfaat Bagi Orang Lain</p>
            </div>
        </div>

        {{-- Sisi kanan - form --}}
        <div class="w-full md:w-1/2 p-10">
            <h1 class="text-2xl font-bold text-[#0D1B5E] mb-6">Selamat Datang di Ecopi</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Nama --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                           placeholder="Isi Namamu di sini"
                           class="w-full border border-gray-300 rounded px-4 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                  @error('nama') border-red-500 @enderror">
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="Isi Emailmu di sini"
                           class="w-full border border-gray-300 rounded px-4 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                  @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buat Password</label>
                    <input type="password" name="password"
                           placeholder="Isi Passwordmu di sini"
                           class="w-full border border-gray-300 rounded px-4 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                  @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                           placeholder="Ulangi passwordmu"
                           class="w-full border border-gray-300 rounded px-4 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Submit + checkbox --}}
                <div class="flex items-center gap-5 mb-3">
                    <button type="submit"
                            class="bg-[#0D1B5E] text-white px-12 py-2 rounded font-semibold
                                   text-sm hover:bg-blue-900 transition">
                        MULAI SEKARANG
                    </button>
                    <label class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer">
                        <input type="checkbox" required class="accent-blue-700">
                        Saya Setuju dengan
                        <span class="text-blue-600 underline">Kebijakan Privasi</span>
                    </label>
                </div>

                <p class="text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">
                        Masuk sini
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection