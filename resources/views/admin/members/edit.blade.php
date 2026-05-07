@extends('layouts.app')
@section('title', 'Edit Member')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.members.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        </a>
        <h2 class="text-xl font-bold text-gray-800">Edit Member</h2>
    </div>

    <form method="POST" action="{{ route('admin.members.update', $member) }}">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $member->name) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">No. HP</label>
                <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $member->email) }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    {{ $member->is_active ? 'checked' : '' }}
                    class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                <label for="is_active" class="text-sm text-gray-600">Member Aktif</label>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.members.index') }}"
                class="flex-1 text-center py-3 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition">Batal</a>
            <button type="submit"
                class="flex-1 py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-md">
                Update Member
            </button>
        </div>
    </form>
</div>
@endsection
