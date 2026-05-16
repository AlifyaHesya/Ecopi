<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecopi - @yield('title', 'Donasi Barang')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        .font-logo {
            font-family: 'Playfair Display', serif;
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #0D1B5E;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-[#F8F9FD] min-h-screen flex flex-col justify-between">

    {{-- Navbar Utama Ecopi --}}
    <nav class="bg-[#0D1B5E] text-white px-8 py-4 flex items-center justify-between shadow-md">
        
        <div class="flex items-center gap-6">
            <a href="{{ route('items.index') }}" class="text-3xl font-bold font-logo tracking-wide">
                <span class="text-white">Eco</span><span class="text-[#8DE474]">pi</span>
            </a>
            @auth
            <a href="{{ route('items.create') }}"
               class="border-2 border-white/40 hover:border-white text-white text-sm font-semibold px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all bg-white/5 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Donasikan Barangmu
            </a>
            @endauth
        </div>

        <div class="flex-1 max-w-2xl mx-8">
            <form action="{{ route('items.search') }}" method="GET" class="relative flex items-center">
                <input type="text" name="search" placeholder="Cari barang yang kamu perlukan"
                       value="{{ request('search') }}"
                       class="w-full pl-6 pr-14 py-3 rounded-xl text-gray-800 text-sm outline-none placeholder:text-gray-400 font-medium shadow-inner">
                <button type="submit" class="absolute right-2 bg-[#0D1B5E] p-2 rounded-lg text-white hover:bg-blue-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>

        <div class="flex items-center gap-6 font-medium">
            @auth
                <a href="{{ route('chat.index') }}" class="flex flex-col items-center gap-0.5 group text-white/80 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-105 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span class="text-xs">Chat</span>
                </a>

                <a href="{{ route('notifications.index') }}" class="flex flex-col items-center gap-0.5 group text-white/80 hover:text-white transition-colors relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-105 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v1.341C7.67 7.165 7 8.388 7 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="text-xs">Notifikasi</span>
                </a>

                <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-0.5 group text-white/80 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-105 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-xs">Profil</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="ml-2 border-l border-white/20 pl-4">
                    @csrf
                    <button type="submit" class="text-xs bg-red-500/20 hover:bg-red-500/40 text-red-300 hover:text-white px-3 py-1.5 rounded-lg transition-all font-semibold">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="flex flex-col items-center gap-0.5 group text-white/80 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-105 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span class="text-xs">Masuk</span>
                </a>
            @endauth
        </div>
    </nav>

    {{-- Flash Toast Notification (Pop-up mengambang agar tidak merusak layout halaman) --}}
    @if(session('success') || session('error'))
        <div class="fixed bottom-5 right-5 z-50 max-w-sm w-full animate-bounce">
            @if(session('success'))
                <div class="bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3">
                    <span>✨</span> <p class="font-medium text-sm">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-rose-600 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3">
                    <span>⚠️</span> <p class="font-medium text-sm">{{ session('error') }}</p>
                </div>
            @endif
        </div>
    @endif

    {{-- Container Utama Konten Web --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Footer Ecopi Sesuai Beranda.jpg --}}
    <footer class="bg-[#0D1B5E] text-white text-center py-6 text-xl font-bold tracking-wide border-t border-white/10">
        @Ecopi 2026
    </footer>

</body>
</html>