<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Gem Pearls POS') }} — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased">

    {{-- Sidebar --}}
    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col shadow-sm">
            {{-- Logo --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="text-xl font-bold text-amber-700 tracking-tight">💎 Gem Pearls</h1>
                <p class="text-xs text-gray-400 mt-0.5">Point of Sale System</p>
            </div>

            {{-- User Info --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-amber-50">
                <p class="text-sm font-semibold text-gray-700">
                    {{ auth()->check() ? auth()->user()->name : 'Guest Mode' }}
                </p>
                <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">
                    {{ auth()->check() ? ucfirst(auth()->user()->getRoleNames()->first()) : 'Testing' }}
                </span>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">

                @if (!auth()->check() || auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin'))
                    <a href="{{ route('dashboard.admin') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-amber-50 hover:text-amber-700 transition {{ request()->routeIs('dashboard.admin') ? 'bg-amber-50 text-amber-700' : '' }}">
                        <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg></span> Dashboard Admin
                    </a>
                @endif

                @if (!auth()->check() || auth()->user()->hasRole('kasir') || auth()->user()->hasRole('owner'))
                    <a href="{{ route('dashboard.kasir') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-amber-50 hover:text-amber-700 transition {{ request()->routeIs('dashboard.kasir') ? 'bg-amber-50 text-amber-700' : '' }}">
                        <span>🛒</span> Kasir / POS
                    </a>
                @endif

                @if (!auth()->check() || auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin'))
                    <div class="pt-3">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Manajemen</p>
                        <a href="{{ route('admin.products.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-amber-50 hover:text-amber-700 transition {{ request()->routeIs('admin.products*') ? 'bg-amber-50 text-amber-700' : '' }}">
                            <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                </svg></span> Produk & Stok
                        </a>
                        <a href="{{ route('admin.partners.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-amber-50 hover:text-amber-700 transition {{ request()->routeIs('admin.partners*') ? 'bg-amber-50 text-amber-700' : '' }}">
                            <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg></span> Mitra
                        </a>
                        <a href="{{ route('admin.members.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-amber-50 hover:text-amber-700 transition {{ request()->routeIs('admin.members*') ? 'bg-amber-50 text-amber-700' : '' }}">
                            <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg></span> Member
                        </a>
                        <a href="{{ route('admin.reports.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-amber-50 hover:text-amber-700 transition">
                            <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                </svg></span> Laporan
                        </a>
                    </div>
                @endif

                @if (!auth()->check() || auth()->user()->hasRole('security') || auth()->user()->hasRole('owner'))
                    <div class="pt-3">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Security</p>
                        <a href="{{ route('dashboard.security') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-amber-50 hover:text-amber-700 transition">
                            <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                </svg></span> Data Tamu
                        </a>
                    </div>
                @endif

            </nav>

            {{-- Logout --}}
            <div class="px-4 py-4 border-t border-gray-100">
                @if (auth()->check())
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 transition">
                            <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                </svg></span> Logout
                        </button>
                    </form>
                @else
                    <p class="text-xs text-gray-400 text-center">🚧 Testing Mode</p>
                @endif
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto">
            {{-- Top Bar --}}
            <div
                class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-10 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-700">@yield('title', 'Dashboard')</h2>
                <div class="text-sm text-gray-400">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
            </div>

            {{-- Page Content --}}
            <div class="px-8 py-6">
                @yield('content')
            </div>
        </main>

    </div>

</body>

</html>
