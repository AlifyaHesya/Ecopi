@extends('layouts.app')
@section('title', 'Masuk')

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

            {{-- Error global --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-700 text-sm px-4 py-2 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="Isi Emailmu di sini"
                           class="w-full border border-gray-300 rounded px-4 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                  @error('email') border-red-500 @enderror">
                </div>

                {{-- Password --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Masukkan Password</label>
                    <input type="password" name="password"
                           placeholder="Isi Passwordmu di sini"
                           class="w-full border border-gray-300 rounded px-4 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2 mb-6">
                    <input type="checkbox" name="remember" id="remember" class="accent-blue-700">
                    <label for="remember" class="text-sm text-gray-500">Ingat saya</label>
                </div>

                <button type="submit"
                        class="w-full bg-[#0D1B5E] text-white py-2 rounded font-semibold
                               text-sm hover:bg-blue-900 transition">
                    MASUK
                </button>

                <p class="text-sm text-gray-500 mt-4 text-center">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">
                        Daftar sekarang
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection