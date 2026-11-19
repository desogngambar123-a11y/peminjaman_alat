@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-red-950 via-maroon-dark to-black p-4">
  <div class="w-full max-w-md bg-maroon-card border border-maroon rounded-2xl p-8 shadow-2xl">
    <div class="text-center mb-6 flex flex-col items-center">
      <img src="{{ asset('images/LogoMusabaV9.png') }}" alt="Logo MUSABA" class="w-32 h-32 object-contain mb-2">
      <h2 class="text-2xl font-bold text-gold">Daftar Akun Baru</h2>
      <p class="text-xs text-gray-300 mt-1">Sistem Peminjaman Alat MUSABA</p>
    </div>

    @if ($errors->any())
      <div class="bg-red-900/50 border border-red-500 text-red-200 text-xs p-3 rounded mb-4" role="alert">
        <ul class="list-inside list-disc">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
      @csrf
      <div>
        <label class="block text-xs text-gray-300 mb-1">Username</label>
        <input type="text" name="username" value="{{ old('username') }}" required maxlength="255" autocomplete="username" class="w-full px-4 py-2.5 rounded-lg bg-black/40 border border-maroon text-white focus:outline-none focus:border-gold" placeholder="Masukkan username baru">
      </div>
      <div>
        <label class="block text-xs text-gray-300 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" class="w-full px-4 py-2.5 rounded-lg bg-black/40 border border-maroon text-white focus:outline-none focus:border-gold" placeholder="Masukkan email">
      </div>
      <div>
        <label class="block text-xs text-gray-300 mb-1">Password</label>
        <input type="password" name="password" required minlength="3" autocomplete="new-password" class="w-full px-4 py-2.5 rounded-lg bg-black/40 border border-maroon text-white focus:outline-none focus:border-gold" placeholder="Minimal 3 karakter">
      </div>
      <div>
        <label class="block text-xs text-gray-300 mb-1">Peran / Role</label>
        <div class="w-full px-4 py-2.5 rounded-lg bg-black/40 border border-maroon text-gray-300">Siswa / Peminjam</div>
      </div>
      <button type="submit" class="w-full py-3 bg-gold hover:bg-gold-hover text-black font-bold rounded-lg transition shadow-lg mt-2">Daftar Akun</button>
    </form>
    <div class="text-center mt-6 text-xs text-gray-300">
      Sudah memiliki akun? <a href="{{ route('login') }}" class="text-gold font-bold hover:underline">Login</a>
    </div>
  </div>
</div>
@endsection