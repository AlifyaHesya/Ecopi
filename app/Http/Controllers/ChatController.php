<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;

class ChatController extends Controller
{
    // Buka atau temukan chat langsung dengan pendonasi

public function mulai(User $user, Item $item)
{
    // Tidak bisa chat dengan diri sendiri
    if ($user->id === Auth::id()) {
        return back()->with('error', 'Kamu tidak bisa chat dengan dirimu sendiri.');
    }

    // Cek apakah chat sudah ada antara dua user untuk barang ini
    $chat = Chat::where('item_id', $item->id)
        ->whereNull('pengajuan_id')
        ->where(function ($q) use ($user) {
            $q->where(function ($q2) use ($user) {
                $q2->where('sender_id', Auth::id())
                   ->where('receiver_id', $user->id);
            })->orWhere(function ($q2) use ($user) {
                $q2->where('sender_id', $user->id)
                   ->where('receiver_id', Auth::id());
            });
        })->first();

    // Kalau belum ada, buat baru
    if (!$chat) {
        $chat = Chat::create([
            'item_id'      => $item->id,
            'pengajuan_id' => null,
            'sender_id'    => $user->id,   // pendonasi
            'receiver_id'  => Auth::id(),  // yang bertanya
        ]);
    }

    return redirect()->route('chat.show', $chat->id);
}

    // Isi percakapan satu chat
    public function show(Chat $chat)
    {
        // Pastikan hanya peserta chat yang bisa akses
        if ($chat->sender_id !== Auth::id() && $chat->receiver_id !== Auth::id()) {
            abort(403);
        }

        $chat->load('messages.sender', 'item', 'pengajuan');

        // Tandai pesan sebagai sudah dibaca
        $idKey = $chat->chat_id ?? $chat->id;
        Message::where('chat_id', $idKey)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat.show', compact('chat'));
    }

    // Kirim pesan baru
    public function store(Request $request, Chat $chat)
    {
        if ($chat->sender_id !== Auth::id() && $chat->receiver_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'message_text' => 'required|string|max:1000',
        ]);

        $idKey = $chat->chat_id ?? $chat->id;
        Message::create([
            'chat_id'      => $idKey,
            'sender_id'    => Auth::id(),
            'message_text' => $request->message_text,
            'is_read'      => false,
            'timestamp'    => now(),
        ]);

        return back();
    }

    public function index()
    {
        $user = auth()->user();

        $chats = \App\Models\Chat::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->latest()
            ->get();

        return view('chat.index', compact('chats'));
    }
        public function openChat($id, $item_id)
        {
            if ($id == Auth::id()) {
                return back()->with('error', 'Anda tidak dapat menghubungi barang milik sendiri.');
            }

            $chat = Chat::where('sender_id', Auth::id())
                ->where('receiver_id', $id)
                ->where('item_id', $item_id)
                ->first();

            if (!$chat) {
                $chat = Chat::create([
                    'sender_id'   => Auth::id(),
                    'receiver_id' => $id,
                    'item_id'     => $item_id,
                ]);
            }

            return redirect()->route('chat.show', $chat->id);
        }
}
