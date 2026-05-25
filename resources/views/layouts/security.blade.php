<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Gem Pearls') }} - @yield('title', 'Security')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-inv.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="bg-slate-50 antialiased min-h-screen">

    <nav class="bg-white border-b border-gray-200 shadow-sm px-6 py-3 flex items-center justify-between sticky top-0 z-50">

        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7.5 3v5.25c0 4.74-3.02 9.03-7.5 10.5-4.48-1.47-7.5-5.76-7.5-10.5V6L12 3z" />
                </svg>
            </div>
            <div>
                <h1 class="text-base font-bold text-blue-700 leading-tight">Gem Pearls Security</h1>
                <p class="text-xs text-gray-400 leading-tight">@yield('subtitle', 'Visitor Management')</p>
            </div>
        </div>

        <div class="text-center" x-data="clock()" x-init="start()">
            <p class="text-sm font-semibold text-gray-700" x-text="tanggal"></p>
            <p class="text-xs text-gray-400" x-text="jam"></p>
        </div>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 transition">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm">
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

            <div x-show="open" @click.outside="open = false" x-transition
                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-xs text-gray-400">Login sebagai</p>
                    <p class="text-sm font-semibold text-gray-700">
                        {{ auth()->check() ? auth()->user()->name : 'Guest Mode' }}
                    </p>
                </div>

                @if(auth()->check())
                    @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin'))
                        <a href="{{ route('dashboard.admin') }}"
                           class="block px-4 py-2.5 text-sm text-blue-600 hover:bg-blue-50 transition">
                            Kembali ke Dashboard
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition">
                            Logout
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </nav>

    <main class="@yield('container_class', 'max-w-7xl mx-auto px-6 py-6')">
        @yield('content')
    </main>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
                    this.jam = now.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                }
            }
        }
    </script>
</body>
</html>
