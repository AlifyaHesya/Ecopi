@extends('layouts.app')
@section('title', 'Chat')

@section('content')
<div class="max-w-2xl mx-auto bg-white min-h-screen">

    {{-- Header --}}
    <div class="bg-[#0D1B5E] text-white px-6 py-4 flex items-center gap-4">
        <a href="{{ route('home') }}" class="w-8 h-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center hover:bg-opacity-30 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="font-bold text-xl">Chat</h1>
    </div>

    {{-- Tab Filter --}}
    <div class="px-4 pt-4 pb-2 flex gap-2">
        <button onclick="filterTab('semua', this)"
                class="tab-btn px-5 py-1.5 rounded-full text-sm font-semibold border-2 border-[#0D1B5E] text-[#0D1B5E] bg-white relative">
            <span class="absolute -top-1 -left-1 w-2.5 h-2.5 bg-blue-600 rounded-full"></span>
            Semua
        </button>
        <button onclick="filterTab('unread', this)"
                class="tab-btn px-5 py-1.5 rounded-full text-sm font-medium border border-gray-300 text-gray-500 bg-white">
            Belum Dibaca
        </button>
        <button onclick="filterTab('read', this)"
                class="tab-btn px-5 py-1.5 rounded-full text-sm font-medium border border-gray-300 text-gray-500 bg-white">
            Sudah Dibaca
        </button>
    </div>

    {{-- Daftar Chat --}}
    <div id="chat-list" class="divide-y divide-gray-100 px-2">
        @forelse($chats as $chat)
        @php
            $lawan = $chat->sender_id === auth()->id() ? $chat->receiver : $chat->sender;
            $lastMsg = $chat->messages->last();
            $unreadCount = $chat->messages->where('sender_id', '!=', auth()->id())->where('is_read', false)->count();
            $isUnread = $unreadCount > 0;
        @endphp
        <a href="{{ route('chat.show', $chat->id) }}"
           class="chat-item flex items-center gap-4 px-4 py-4 rounded-xl mx-1 my-1 hover:bg-blue-50 transition cursor-pointer {{ $isUnread ? 'bg-blue-50' : 'bg-white' }}"
           data-read="{{ $isUnread ? 'unread' : 'read' }}">

            {{-- Avatar --}}
            <div class="w-14 h-14 rounded-full bg-gray-300 flex items-center justify-center font-bold text-gray-600 text-lg flex-shrink-0 overflow-hidden">
                @if($lawan && $lawan->foto_profil)
                    <img src="{{ asset('storage/' . $lawan->foto_profil) }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($lawan->nama ?? '?', 0, 2)) }}
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-800 text-base">{{ $lawan->nama ?? 'Pengguna' }}</p>
                <p class="text-sm text-gray-400 truncate mt-0.5">
                    {{ $lastMsg?->message_text ?? 'Belum ada pesan' }}
                </p>
            </div>

            {{-- Waktu + badge --}}
            <div class="flex flex-col items-end gap-1 flex-shrink-0">
                <span class="text-xs text-gray-400">
                    @if($lastMsg)
                        {{ $lastMsg->timestamp->isToday() ? $lastMsg->timestamp->format('H:i') : $lastMsg->timestamp->format('d/m') }}
                    @endif
                </span>
                @if($unreadCount > 0)
                    <span class="bg-blue-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">
                        {{ $unreadCount }}
                    </span>
                @endif
            </div>
        </a>
        @empty
        <div class="text-center py-20 text-gray-400">
            <div class="text-5xl mb-3">💬</div>
            <p class="font-medium">Belum ada chat</p>
            <p class="text-sm mt-1">Mulai chat dengan menekan tombol "Chat Pendonor" di halaman barang</p>
        </div>
        @endforelse
    </div>
</div>

<script>
function filterTab(type, btn) {
    // Update style tombol
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.className = 'tab-btn px-5 py-1.5 rounded-full text-sm font-medium border border-gray-300 text-gray-500 bg-white';
    });
    btn.className = 'tab-btn px-5 py-1.5 rounded-full text-sm font-semibold border-2 border-[#0D1B5E] text-[#0D1B5E] bg-white';

    // Filter item
    document.querySelectorAll('.chat-item').forEach(item => {
        if (type === 'semua') {
            item.style.display = '';
        } else if (type === 'unread') {
            item.style.display = item.dataset.read === 'unread' ? '' : 'none';
        } else {
            item.style.display = item.dataset.read === 'read' ? '' : 'none';
        }
    });
}
</script>
@endsection