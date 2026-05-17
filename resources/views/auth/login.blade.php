<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Gem Pearls POS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('logo-inv.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm">

        {{-- Logo & Brand --}}
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('assets/gem-pearls-logo.png') }}" alt="Gem Pearls" class="w-20 h-20 object-contain">
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Gem Pearls POS</h1>
            <p class="text-sm text-slate-400 mt-1">Masuk ke POS System</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">

            {{-- Session Status --}}
            @if(session('status'))
                <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        required autofocus autocomplete="username"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 text-sm transition bg-slate-50 text-slate-800"
                        placeholder="email@example.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Password</label>
                    <input id="password" type="password" name="password"
                        required autocomplete="current-password"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 text-sm transition bg-slate-50 text-slate-800"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember_me"
                            class="rounded border-slate-300 text-blue-500 focus:ring-blue-400">
                        <span class="text-xs text-slate-500 font-medium">Ingat saya</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs text-blue-500 hover:text-blue-700 font-semibold transition">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition shadow-sm mt-2">
                    Masuk
                </button>

            </form>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            Gem Pearls Jewelry · Lombok, NTB
        </p>

    </div>

</body>
</html>
