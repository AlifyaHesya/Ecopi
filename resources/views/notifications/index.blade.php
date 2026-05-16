@extends('layouts.app')
@section('title', 'Notifikasi')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-[#0D1B5E] text-white px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="text-white">←</a>
            <h1 class="font-bold text-lg">Notifikasi</h1>
        </div>
        @if(auth()->user()->unreadNotifications->count() > 0)
        <form method="POST" action="{{ route('notifications.markAllRead') }}">
            @csrf
            <button class="text-xs text-blue-300 hover:text-white">Tandai semua dibaca</button>
        </form>
        @endif
    </div>

    {{-- Tab --}}
    <div class="flex border-b bg-white px-4 pt-2">
        <button class="px-4 py-2 text-sm font-semibold text-blue-700 border-b-2 border-blue-700">Semua</button>
        <button class="px-4 py-2 text-sm text-gray-400">Belum Dibaca</button>
        <button class="px-4 py-2 text-sm text-gray-400">Sudah Dibaca</button>
    </div>

    @forelse($notifications as $notif)
    <div class="flex items-start gap-4 px-6 py-4 border-b {{ $notif->read_at ? 'bg-white' : 'bg-blue-50' }}">
        <div class="w-10 h-10 bg-gray-300 rounded-full flex-shrink-0 flex items-center justify-center text-gray-600">
            🔔
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold">{{ $notif->data['title'] ?? 'Notifikasi' }}</p>
            <p class="text-xs text-gray-500">{{ $notif->data['message'] ?? '' }}</p>
        </div>
        <span class="text-xs text-gray-400 flex-shrink-0">
            {{ $notif->created_at->diffForHumans() }}
        </span>
    </div>
    @empty
        <div class="text-center text-gray-500 py-16">
            <div class="text-5xl mb-4">🔔</div>
            <p>Belum ada notifikasi.</p>
        </div>
    @endforelse
</div>
@endsection