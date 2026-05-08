<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Gem Pearls POS') }} — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    [x-cloak] { display: none !important; }
</style>
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 shadow-sm px-6 py-3 flex items-center justify-between sticky top-0 z-50">

        {{-- Kiri: Logo --}}
        <div class="flex items-center gap-3">
            <span class="text-2xl">💎</span>
            <div>
                <h1 class="text-base font-bold text-amber-700 leading-tight">Gem Pearls</h1>
                <p class="text-xs text-gray-400 leading-tight">@yield('subtitle', 'POS System')</p>
            </div>
        </div>

        {{-- Tengah: Tanggal & Jam --}}
        <div class="text-center" x-data="clock()" x-init="start()">
            <p class="text-sm font-semibold text-gray-700" x-text="tanggal"></p>
            <p class="text-xs text-gray-400" x-text="jam"></p>
        </div>

        {{-- Kanan: Profile --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-50 hover:bg-amber-50 border border-gray-200 hover:border-amber-200 transition">
                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 font-bold text-sm">
                    {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'G' }}
                </div>
                <div class="text-left">
                    <p class="text-sm font-medium text-gray-700 leading-tight">
                        {{ auth()->check() ? auth()->user()->name : 'Guest' }}
                    </p>
                    <p class="text-xs text-gray-400 leading-tight">
                        {{ auth()->check() ? ucfirst(auth()->user()->getRoleNames()->first()) : 'Testing' }}
                    </p>
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Dropdown --}}
            <div x-show="open" @click.outside="open = false" x-transition
                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-xs text-gray-400">Login sebagai</p>
                    <p class="text-sm font-semibold text-gray-700">
                        {{ auth()->check() ? auth()->user()->name : 'Guest Mode' }}
                    </p>
                </div>
                @if(auth()->check())
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition flex items-center gap-2">
                        <span>🚪</span> Logout
                    </button>
                </form>
                @else
                <p class="px-4 py-2.5 text-xs text-gray-400">🚧 Testing Mode</p>
                @endif
            </div>
        </div>

    </nav>

    {{-- Content --}}
    <div class="@yield('container_class', 'max-w-7xl mx-auto px-6 py-6')">
        @yield('content')
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>

<script>
    function clock() {
        return {
            tanggal: '',
            jam: '',
            start() {
                this.update();
                setInterval(() => this.update(), 1000);
            },
            update() {
                const now = new Date();
                const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                this.tanggal = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
                this.jam = now.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit', second: '2-digit'});
            }
        }
    }
</script>
</html>
