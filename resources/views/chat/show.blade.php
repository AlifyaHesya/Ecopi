@extends('layouts.app')
@section('title', 'Chat')

@section('content')
@php $lawan = $chat->sender_id === auth()->id() ? $chat->receiver : $chat->sender; @endphp

<div class="max-w-2xl mx-auto flex flex-col bg-white" style="height: calc(100vh - 0px)">

    {{-- Header --}}
    <div class="bg-[#0D1B5E] text-white px-4 py-3 flex items-center gap-3 flex-shrink-0">
        <a href="{{ route('chat.index') }}"
           class="w-8 h-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center hover:bg-opacity-30 transition flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        {{-- Avatar lawan --}}
        <div class="w-10 h-10 rounded-full bg-gray-400 flex items-center justify-center font-bold text-sm overflow-hidden flex-shrink-0">
            @if($lawan && $lawan->foto_profil)
                <img src="{{ asset('storage/' . $lawan->foto_profil) }}" class="w-full h-full object-cover">
            @else
                {{ strtoupper(substr($lawan->nama ?? '?', 0, 2)) }}
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <p class="font-bold text-sm">{{ $lawan->nama ?? 'Pengguna' }}</p>
            <p class="text-xs text-blue-300 truncate">{{ $chat->item->nama_barang ?? '' }}</p>
        </div>
    </div>

    {{-- Area Pesan --}}
    <div class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-gray-50" id="chat-box">

        {{-- Info barang di atas --}}
        @if($chat->item)
        <div class="flex justify-center mb-2">
            <div class="bg-white border rounded-xl px-4 py-2 flex items-center gap-3 shadow-sm max-w-xs w-full">
                @if($chat->item->firstImage)
                    <img src="{{ asset('storage/' . $chat->item->firstImage->image_url) }}"
                         class="w-10 h-10 object-cover rounded-lg flex-shrink-0">
                @else
                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-lg flex-shrink-0">📦</div>
                @endif
                <div class="min-w-0">
                    <p class="text-xs font-bold text-gray-700 truncate">{{ $chat->item->nama_barang }}</p>
                    <p class="text-xs text-gray-400">{{ $chat->item->location->kecamatan ?? '-' }}</p>
                </div>
                <a href="{{ route('items.show', $chat->item->id) }}"
                   class="text-xs text-blue-600 hover:underline flex-shrink-0">Lihat</a>
            </div>
        </div>
        @endif

        @forelse($chat->messages as $msg)
        @php $isMine = $msg->sender_id === auth()->id(); @endphp
        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-end gap-2">

            {{-- Avatar lawan (kiri) --}}
            @if(!$isMine)
            <div class="w-7 h-7 rounded-full bg-gray-300 flex items-center justify-center text-xs font-bold text-gray-600 flex-shrink-0 overflow-hidden">
                @if($lawan && $lawan->foto_profil)
                    <img src="{{ asset('storage/' . $lawan->foto_profil) }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($lawan->nama ?? '?', 0, 1)) }}
                @endif
            </div>
            @endif

            {{-- Bubble pesan --}}
            <div class="max-w-xs lg:max-w-sm">
                <div class="px-4 py-2.5 rounded-2xl text-sm shadow-sm
                    {{ $isMine
                        ? 'bg-blue-500 text-white rounded-br-none'
                        : 'bg-white text-gray-800 rounded-bl-none border border-gray-100' }}">
                    <p class="leading-relaxed">{{ $msg->message_text }}</p>
                </div>
                <p class="text-xs mt-1 text-gray-400 {{ $isMine ? 'text-right' : 'text-left' }}">
                    {{ $msg->timestamp->format('H:i') }}
                    @if($isMine)
                        <span class="{{ $msg->is_read ? 'text-blue-400' : 'text-gray-300' }}">
                            {{ $msg->is_read ? '✓✓' : '✓' }}
                        </span>
                    @endif
                </p>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-400">
            <p class="text-sm">Belum ada pesan. Mulai percakapan!</p>
        </div>
        @endforelse
    </div>

    {{-- Input Pesan --}}
    <div class="bg-white border-t px-4 py-3 flex-shrink-0">
        <form method="POST" action="{{ route('chat.store', $chat->id) }}"
              class="flex items-center gap-3" id="chat-form">
            @csrf
            <input type="text" name="message_text" id="message-input"
                   placeholder="Text message"
                   autocomplete="off"
                   class="flex-1 bg-gray-100 rounded-full px-5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 border-none">
            <button type="submit"
                    class="w-10 h-10 bg-blue-500 hover:bg-blue-600 text-white rounded-full flex items-center justify-center transition flex-shrink-0 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5m0 0l-7 7m7-7l7 7"/>
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
    // Auto scroll ke pesan terbawah
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;

    // Kosongkan input setelah kirim
    document.getElementById('chat-form').addEventListener('submit', function() {
        setTimeout(() => document.getElementById('message-input').value = '', 100);
    });
</script>
@endsection