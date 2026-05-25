<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Komisi — Gem Pearls</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>* { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('assets/gem-pearls-logo.png') }}" alt="Gem Pearls" class="w-16 h-16 object-contain">
            </div>
            <h1 class="text-xl font-bold text-slate-800">Halaman Komisi</h1>
            <p class="text-sm text-slate-400 mt-1">Masuk untuk melihat data komisi</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            @if($errors->any())
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-semibold">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('commission.login.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none text-sm bg-slate-50"
                        placeholder="email@example.com">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none text-sm bg-slate-50"
                        placeholder="••••••••">
                </div>
                <button type="submit"
                    class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition">
                    Masuk
                </button>
            </form>
        </div>
        <p class="text-center text-xs text-slate-400 mt-6">Gem Pearls Jewelry · Lombok, NTB</p>
    </div>
</body>
</html>
