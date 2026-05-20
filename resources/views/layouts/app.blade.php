<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Gem Pearls') }} - @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-inv.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            font-family: 'Poppins', sans-serif ;
        }

        [x-cloak] {
            display: none !important;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            transition: all 0.15s;
            text-decoration: none;
        }

        .nav-link:hover {
            background: #eff6ff;
            color: #2563eb;
        }

        .nav-link.active {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        .nav-link .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .nav-section {
            font-size: 10px;
            font-weight: 700;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 12px;
            margin: 14px 0 4px;
        }
    </style>
</head>

<body class="bg-slate-50 antialiased" style="font-family:'Poppins',sans-serif;">
    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside class="w-60 bg-white border-r border-slate-100 flex flex-col shadow-sm flex-shrink-0">

            {{-- Logo --}}
            <div class="px-5 py-5 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="white" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold text-slate-800 leading-tight">Gem Pearls</h1>
                        <p class="text-xs text-slate-400 leading-tight">POS System</p>
                    </div>
                </div>
            </div>

            {{-- User Info --}}
            <div class="px-5 py-3 border-b border-slate-100 bg-blue-50/50">
                <p class="text-xs font-semibold text-slate-700">
                    {{ auth()->check() ? auth()->user()->name : 'Guest Mode' }}</p>
                <span
                    class="inline-block mt-0.5 text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-semibold">
                    {{ auth()->check() ? ucfirst(auth()->user()->getRoleNames()->first()) : 'Testing' }}
                </span>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-3 overflow-y-auto space-y-0.5">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard.admin') }}"
                    class="nav-link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="nav-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>

                {{-- Manajemen Toko --}}
                <p class="nav-section">Manajemen Toko</p>

                <a href="{{ route('admin.products.index') }}"
                    class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="nav-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                    Produk & Stok
                </a>

                <a href="{{ route('admin.members.index') }}"
                    class="nav-link {{ request()->routeIs('admin.members*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="nav-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    Member
                </a>

                <a href="{{ route('admin.receipts.index') }}"
                    class="nav-link {{ request()->routeIs('admin.receipts*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="nav-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    Struk
                </a>

                <a href="{{ route('admin.reports.index') }}"
                    class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="nav-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                    Laporan
                </a>
                <a href="{{ route('admin.sales-staff.index') }}" class="nav-link {{ request()->routeIs('admin.sales-staff*') ? 'active' : '' }}">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"/>
    </svg>
    Data Sales
</a>

                <a href="{{ route('admin.tax.index') }}"
                    class="nav-link {{ request()->routeIs('admin.taxes*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="nav-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Pajak
                </a>
                <a href="{{ route('admin.customers.index') }}"
                    class="nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="nav-icon text-slate-400">
                        <path fill-rule="evenodd"
                            d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z"
                            clip-rule="evenodd" />
                    </svg>
                    Data Customer
                </a>

                {{-- Partner & Kunjungan --}}
                <p class="nav-section">Partner & Kunjungan</p>

                <a href="{{ route('admin.partners.index') }}"
                    class="nav-link {{ request()->routeIs('admin.partners*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="nav-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    Mitra
                </a>

                <a href="{{ route('admin.visits.index') }}"
                    class="nav-link {{ request()->routeIs('admin.visits*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="nav-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                    Kunjungan
                </a>

                <a href="{{ route('admin.commissions.index') }}"
                    class="nav-link {{ request()->routeIs('admin.commissions*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="nav-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8.25v-1.5m0 1.5c-1.036 0-1.875.84-1.875 1.875S10.964 12 12 12s1.875.84 1.875 1.875S13.036 15.75 12 15.75m0-7.5c1.036 0 1.875.84 1.875 1.875M12 15.75v1.5m-7.5-6h15" />
                    </svg>
                    Komisi
                </a>

            </nav>

            {{-- Settings --}}
            <p class="nav-section">Pengaturan</p>
            <a href="{{ route('admin.users.index') }}"
                class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="nav-icon">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                Kelola User
            </a>
            <a href="{{ route('admin.upload-users.index') }}" class="nav-link {{ request()->routeIs('admin.upload-users*') ? 'active' : '' }}">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
    </svg>
    Akun Upload
</a>



            {{-- Logout --}}
            <div class="px-3 py-3 border-t border-slate-100">
                @if (auth()->check())
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="nav-link w-full text-red-400 hover:bg-red-50 hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="nav-icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                            </svg>
                            Logout
                        </button>
                    </form>
                @else
                    <p class="text-xs text-slate-400 text-center py-2">Testing Mode</p>
                @endif
            </div>

        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto">
            <div
                class="bg-white border-b border-slate-100 px-8 py-4 flex items-center justify-between sticky top-0 z-10 shadow-sm">
                <h2 class="text-base font-bold text-slate-700">@yield('title', 'Dashboard')</h2>
                <div class="text-xs text-slate-400 font-medium">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
            </div>
            <div class="px-8 py-6">
                @yield('content')
            </div>
        </main>

    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>

</html>
