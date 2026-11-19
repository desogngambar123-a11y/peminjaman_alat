@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-red-950 via-maroon-dark to-black p-4">
  <div class="w-full max-w-md bg-maroon-card border border-maroon rounded-2xl p-8 shadow-2xl">
    
    <!-- Header Logo & Judul MUSABA -->
    <div class="text-center mb-6 flex flex-col items-center">
      <!-- Menggunakan logo yang benar dari folder public/images/download.png -->
      <img src="{{ asset('images/LogoMusabaV9.png') }}" alt="Logo MUSABA" class="w-24 h-24 object-contain mb-2 drop-shadow-md">
      
      <h1 class="text-3xl font-black tracking-widest text-teal-400">MUSABA</h1>
      <p class="text-[11px] font-bold text-gold tracking-widest uppercase">SMK MUHAMMADIYAH 1 BANTUL</p>
      <p class="text-xs text-gray-300 mt-1">Sistem Peminjaman Alat</p>
    </div>

    <!-- Alert Notifikasi Error -->
    @if($errors->any())
      <div class="bg-red-900/50 border border-red-500 text-red-200 text-xs p-3 rounded-lg mb-4 flex items-center">
        <i class="fa-solid fa-circle-exclamation mr-2 text-red-400"></i>
        <span>{{ $errors->first() }}</span>
      </div>
    @endif

    <!-- Alert Notifikasi Sukses Registrasi -->
    @if(session('success'))
      <div class="bg-emerald-900/50 border border-emerald-500 text-emerald-200 text-xs p-3 rounded-lg mb-4 flex items-center">
        <i class="fa-solid fa-circle-check mr-2 text-emerald-400"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    <!-- Form Login -->
    <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
      @csrf
      <div>
        <label class="block text-xs text-gray-300 mb-1">Username</label>
        <input type="text" id="username" name="username" required value="{{ old('username') }}" class="w-full px-4 py-2.5 rounded-lg bg-black/40 border border-maroon text-white focus:outline-none focus:border-gold transition" placeholder="Masukkan username">
      </div>
      <div>
        <label class="block text-xs text-gray-300 mb-1">Password</label>
        <input type="password" id="password" name="password" required class="w-full px-4 py-2.5 rounded-lg bg-black/40 border border-maroon text-white focus:outline-none focus:border-gold transition" placeholder="••••••••">
      </div>
      <button type="submit" class="w-full py-3 bg-gold hover:bg-gold-hover text-black font-bold rounded-lg transition duration-200 shadow-lg mt-2">Login</button>
    </form>

    <!-- Tombol Demo Login Cepat -->
    <div class="mt-5 pt-4 border-t border-maroon/60 text-center">
      <p class="text-[11px] text-gray-400 mb-2">Atau uji coba cepat:</p>
      <div class="flex space-x-2">
        <button type="button" onclick="quickFill('admin', '123')" class="flex-1 py-1.5 bg-maroon-dark hover:bg-maroon-hover border border-maroon rounded-lg text-xs text-gold font-semibold transition flex items-center justify-center">
          <i class="fa-solid fa-user-shield mr-1.5"></i> Demo Admin
        </button>
        <button type="button" onclick="quickFill('siswa', '123')" class="flex-1 py-1.5 bg-maroon-dark hover:bg-maroon-hover border border-maroon rounded-lg text-xs text-teal-400 font-semibold transition flex items-center justify-center">
          <i class="fa-solid fa-user-graduate mr-1.5"></i> Demo Siswa
        </button>
      </div>
    </div>

    <!-- Link Ke Halaman Registrasi -->
    <div class="text-center mt-5 text-xs text-gray-300">
      Belum punya akun? <a href="{{ route('register') }}" class="text-gold font-bold hover:underline">Register</a>
    </div>

  </div>
</div>

<script>
  function quickFill(username, password) {
    document.getElementById('username').value = username;
    document.getElementById('password').value = password;
  }
</script>
@endsection