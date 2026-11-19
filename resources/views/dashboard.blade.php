@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-maroon-dark via-black to-maroon-dark md:flex">
  <aside class="flex w-full flex-col border-b border-maroon bg-black/40 p-4 md:min-h-screen md:w-64 md:border-b-0 md:border-r">
    <a href="{{ route('dashboard') }}" class="mb-6 flex items-center gap-3">
      <img src="{{ asset('images/LogoMusabaV9.png') }}" alt="Logo MUSABA" class="h-12 w-12 object-contain">
      <span>
        <span class="block text-lg font-black tracking-wider text-teal-400">MUSABA</span>
        <span class="block text-[10px] text-gray-400">PEMINJAMAN ALAT</span>
      </span>
    </a>

    <p class="mb-2 text-[10px] font-bold uppercase tracking-wider text-gray-500">Menu utama</p>
    <nav class="flex gap-2 overflow-x-auto md:flex-col" aria-label="Menu utama">
      <a href="{{ route('dashboard', ['section' => 'dashboard']) }}" class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-semibold {{ $section === 'dashboard' ? 'bg-teal-900/40 text-teal-200' : 'text-gray-300 hover:bg-black/20' }}">
        <i class="fa-solid fa-table-columns mr-2 w-4"></i> Dashboard
      </a>
      <a href="{{ route('dashboard', ['section' => 'alat']) }}" class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-semibold {{ $section === 'alat' ? 'bg-teal-900/40 text-teal-200' : 'text-gray-300 hover:bg-black/20' }}">
        <i class="fa-solid fa-boxes-stacked mr-2 w-4"></i> Daftar Alat
      </a>
      <a href="{{ route('dashboard', ['section' => 'pengajuan']) }}" class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-semibold {{ $section === 'pengajuan' ? 'bg-teal-900/40 text-teal-200' : 'text-gray-300 hover:bg-black/20' }}">
        <i class="fa-solid fa-clipboard-check mr-2 w-4"></i> Pengajuan
        @if ($statMenunggu > 0)
          <span class="ml-1 rounded-full bg-amber-500 px-2 py-0.5 text-[10px] font-bold text-black">{{ $statMenunggu }}</span>
        @endif
      </a>
      <a href="{{ route('dashboard', ['section' => 'riwayat']) }}" class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-semibold {{ $section === 'riwayat' ? 'bg-teal-900/40 text-teal-200' : 'text-gray-300 hover:bg-black/20' }}">
        <i class="fa-solid fa-clock-rotate-left mr-2 w-4"></i> Riwayat Peminjaman
      </a>
    </nav>

    <div class="mt-6 flex items-center gap-3 border-t border-maroon pt-4 md:mt-auto">
      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-teal-700 font-bold text-white" aria-hidden="true">
        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
      </div>
      <div class="min-w-0">
        <p class="truncate text-sm font-bold text-white">{{ Auth::user()->username }}</p>
        <p class="text-xs text-gray-400">{{ Auth::user()->role === 'Admin' ? 'Admin' : 'Siswa' }}</p>
      </div>
    </div>
  </aside>

  <div class="min-w-0 flex-1">
    <header class="flex flex-wrap items-center justify-between gap-4 border-b border-maroon bg-black/20 px-4 py-4 sm:px-6">
      <div>
        <h1 class="text-xl font-bold text-white">
          {{ $section === 'alat' ? 'Daftar Alat' : ($section === 'pengajuan' ? 'Pengajuan Peminjaman' : ($section === 'riwayat' ? 'Riwayat Peminjaman' : 'Dashboard Peminjaman')) }}
        </h1>
        <p class="mt-1 text-xs text-gray-400">SMK Muhammadiyah 1 Bantul</p>
      </div>
      <div class="flex items-center gap-2">
        <button type="button" onclick="document.getElementById('modal-password').classList.remove('hidden')" class="rounded-lg border border-maroon bg-maroon-dark px-3 py-2 text-xs font-bold text-gray-200 shadow transition">
          <i class="fa-solid fa-key mr-1.5"></i> Ganti Password
        </button>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="rounded-lg p-2 text-gray-400 hover:text-white" title="Keluar" aria-label="Keluar">
            <i class="fa-solid fa-right-from-bracket text-lg"></i>
          </button>
        </form>
      </div>
    </header>

    <main class="mx-auto w-full max-w-7xl space-y-6 p-4 sm:p-6">
      @if (session('success'))
        <div class="rounded-lg border border-emerald-500/50 bg-emerald-900/30 px-4 py-3 text-sm text-emerald-200" role="status">
          {{ session('success') }}
        </div>
      @endif
      @if ($errors->any())
        <div class="rounded-lg border border-red-500/50 bg-red-900/30 px-4 py-3 text-sm text-red-200" role="alert">
          <p class="font-bold">Periksa kembali data yang dimasukkan:</p>
          <ul class="mt-1 list-inside list-disc">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if ($section === 'alat')
        <section>
          <div class="mb-4">
            <h2 class="text-lg font-bold text-white">Galeri & Daftar Alat</h2>
            <p class="mt-1 text-sm text-gray-400">Pilih alat untuk mengisi formulir peminjaman.</p>
          </div>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($equipmentList as $item)
              @php($photo = $equipmentPhotos->get($item['name'])?->foto_alat)
              @php($activeCount = $activeLoanCounts->get($item['name'], 0))
              <article class="overflow-hidden rounded-xl border border-maroon bg-maroon-card shadow-lg">
                @if ($photo)
                  <img src="{{ str_starts_with($photo, 'http') ? $photo : asset('storage/'.$photo) }}" alt="{{ $item['name'] }}" class="h-40 w-full object-cover">
                @else
                  <div class="flex h-40 items-center justify-center bg-black/30 text-5xl text-gold" aria-label="Belum ada foto alat">
                    <i class="fa-solid fa-box"></i>
                  </div>
                @endif
                <div class="space-y-3 p-4">
                  <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-teal-300">{{ $item['category'] }}</p>
                    <h3 class="mt-1 font-bold text-white">{{ $item['name'] }}</h3>
                  </div>
                  <p class="text-xs text-gray-300">
                    {{ $activeCount > 0 ? $activeCount.' peminjaman masih aktif' : 'Tidak ada peminjaman aktif' }}
                  </p>
                  <button type="button" onclick="openLoanModal(@js($item['name']))" class="w-full rounded-lg bg-gold px-3 py-2 text-sm font-bold text-black transition hover:bg-gold-hover">
                    <i class="fa-solid fa-plus mr-1"></i> Input Peminjaman
                  </button>
                </div>
              </article>
            @endforeach
          </div>
        </section>
      @else
        @if ($section === 'dashboard')
          <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5" aria-label="Statistik peminjaman">
            <div class="flex items-center justify-between rounded-xl border border-maroon bg-maroon-card p-4">
              <div><p class="text-xs text-gray-300">Total Peminjaman</p><h2 class="mt-1 text-3xl font-extrabold">{{ $totalPeminjaman }}</h2></div>
              <div class="rounded-lg bg-black/30 p-3 text-gold"><i class="fa-solid fa-boxes-stacked text-2xl"></i></div>
            </div>
            <div class="flex items-center justify-between rounded-xl border border-maroon bg-maroon-card p-4">
              <div><p class="text-xs text-gray-300">Menunggu Verifikasi</p><h2 class="mt-1 text-3xl font-extrabold text-amber-300">{{ $statMenunggu }}</h2></div>
              <div class="rounded-lg bg-black/30 p-3 text-amber-300"><i class="fa-solid fa-hourglass-half text-2xl"></i></div>
            </div>
            <div class="flex items-center justify-between rounded-xl border border-maroon bg-maroon-card p-4">
              <div><p class="text-xs text-gray-300">Sedang Dipinjam</p><h2 class="mt-1 text-3xl font-extrabold">{{ $statDipinjam }}</h2></div>
              <div class="rounded-lg bg-black/30 p-3 text-amber-400"><i class="fa-solid fa-hand-holding text-2xl"></i></div>
            </div>
            <div class="flex items-center justify-between rounded-xl border border-maroon bg-maroon-card p-4">
              <div><p class="text-xs text-gray-300">Sudah Dikembalikan</p><h2 class="mt-1 text-3xl font-extrabold text-emerald-400">{{ $statDikembalikan }}</h2></div>
              <div class="rounded-lg bg-black/30 p-3 text-emerald-400"><i class="fa-solid fa-calendar-check text-2xl"></i></div>
            </div>
            <div class="flex items-center justify-between rounded-xl border border-maroon bg-maroon-card p-4">
              <div><p class="text-xs text-gray-300">Terlambat</p><h2 class="mt-1 text-3xl font-extrabold text-red-400">{{ $statTerlambat }}</h2></div>
              <div class="rounded-lg bg-black/30 p-3 text-red-500"><i class="fa-solid fa-clock-rotate-left text-2xl"></i></div>
            </div>
          </section>
        @endif

        <section class="rounded-xl border border-maroon bg-maroon-card p-4 shadow-xl sm:p-5">
          <div class="mb-4 flex flex-wrap items-center gap-2 border-b border-maroon pb-3">
            <a href="{{ route('dashboard', ['section' => 'dashboard', 'search' => $search]) }}" class="rounded-lg px-4 py-2 text-xs font-bold {{ $section === 'dashboard' ? 'bg-gold text-black' : 'border border-maroon bg-black/40 text-gray-300' }}">
              <i class="fa-solid fa-list-check mr-1.5"></i> Peminjaman Aktif
            </a>
            <a href="{{ route('dashboard', ['section' => 'pengajuan', 'search' => $search]) }}" class="rounded-lg px-4 py-2 text-xs font-bold {{ $section === 'pengajuan' ? 'bg-gold text-black' : 'border border-maroon bg-black/40 text-gray-300' }}">
              <i class="fa-solid fa-clipboard-check mr-1.5"></i> Pengajuan Menunggu
            </a>
            <a href="{{ route('dashboard', ['section' => 'riwayat', 'search' => $search]) }}" class="rounded-lg px-4 py-2 text-xs font-bold {{ $section === 'riwayat' ? 'bg-gold text-black' : 'border border-maroon bg-black/40 text-gray-300' }}">
              <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Riwayat Peminjaman
            </a>
          </div>

          <form method="GET" action="{{ route('dashboard') }}" class="mb-4 flex gap-2">
            <input type="hidden" name="section" value="{{ $section }}">
            <label for="loan-search" class="sr-only">Cari alat atau peminjam</label>
            <input id="loan-search" type="search" name="search" value="{{ $search }}" placeholder="Cari Alat / Peminjam..." class="min-w-0 flex-1 rounded-lg border border-maroon bg-black/40 px-4 py-2 text-xs text-white focus:border-gold focus:outline-none">
            <button type="submit" class="rounded-lg bg-gold px-3 py-2 text-xs font-bold text-black" aria-label="Cari"><i class="fa-solid fa-search"></i></button>
          </form>

          <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] text-left text-xs">
              <thead class="border-b border-maroon bg-black/40 uppercase text-gray-300">
                <tr>
                  <th class="p-3">Foto</th>
                  <th class="p-3">Alat</th>
                  <th class="p-3">Peminjam</th>
                  <th class="p-3">Tanggal Pinjam</th>
                  <th class="p-3">Status</th>
                  <th class="p-3 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-maroon/40 text-gray-200">
                @forelse ($loans as $loan)
                  <tr class="hover:bg-black/20">
                    <td class="p-3">
                      @php($foto = $loan->foto_alat ?? null)
                      @if ($foto)
                        <img src="{{ str_starts_with($foto, 'http') ? $foto : asset('storage/'.$foto) }}" class="h-10 w-10 rounded-lg border border-maroon object-cover" alt="{{ $loan->nama_alat }}">
                      @else
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-maroon bg-black/30 text-gold" aria-label="Tidak ada foto"><i class="fa-solid fa-box"></i></div>
                      @endif
                    </td>
                    <td class="p-3 font-semibold">{{ $loan->nama_alat }}</td>
                    <td class="p-3 text-gray-300">{{ $loan->borrower }}</td>
                    <td class="p-3 text-gray-400">{{ $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                    @php($status = $loan->status ?? 'Dipinjam')
                    @php($statusLabel = $status === 'Dipinjam' ? 'Disetujui oleh Admin' : $status)
                    @php($statusClass = match ($status) {
                      'Menunggu Verifikasi' => 'bg-amber-500/20 text-amber-200',
                      'Dipinjam', 'Disetujui oleh Admin' => 'bg-teal-700 text-white',
                      'Terlambat' => 'badge-terlambat',
                      'Dikembalikan' => 'badge-dikembalikan',
                      'Ditolak' => 'bg-red-900 text-red-200',
                      default => 'badge-dipinjam',
                    })
                    <td class="p-3">
                      <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $statusClass }}">{{ $statusLabel }}</span>
                      @if ($loan->catatan_admin)
                        <p class="mt-2 max-w-48 whitespace-pre-line text-[10px] text-gray-400">{{ $loan->catatan_admin }}</p>
                      @endif
                    </td>
                    <td class="p-3 text-center">
                      <div class="flex flex-col items-center justify-center gap-2">
                        @if (Auth::user()->role === 'Admin')
                          @if ($status === 'Menunggu Verifikasi')
                            <form action="{{ route('loans.approve', $loan) }}" method="POST" class="space-y-1">
                              @csrf
                              @method('PATCH')
                              <input type="text" name="catatan_admin" maxlength="1000" class="w-40 rounded border border-maroon bg-black/40 px-2 py-1 text-[10px] text-white" placeholder="Catatan (opsional)">
                              <button type="submit" class="w-40 rounded bg-emerald-700 px-2 py-1 text-[10px] font-bold text-white hover:bg-emerald-600">Setujui Pengajuan</button>
                            </form>
                            <form action="{{ route('loans.reject', $loan) }}" method="POST" class="space-y-1" onsubmit="return confirm('Tolak pengajuan peminjaman ini?')">
                              @csrf
                              @method('PATCH')
                              <input type="text" name="catatan_admin" maxlength="1000" class="w-40 rounded border border-maroon bg-black/40 px-2 py-1 text-[10px] text-white" placeholder="Alasan penolakan (opsional)">
                              <button type="submit" class="w-40 rounded bg-red-700 px-2 py-1 text-[10px] font-bold text-white hover:bg-red-600">Tolak Pengajuan</button>
                            </form>
                          @elseif (in_array($status, ['Dipinjam', 'Disetujui oleh Admin', 'Terlambat'], true))
                            <form action="{{ route('loans.return', $loan) }}" method="POST">
                              @csrf
                              @method('PATCH')
                              <button type="submit" class="rounded bg-emerald-700 px-2 py-1 text-[10px] font-bold text-white hover:bg-emerald-600">Kembalikan</button>
                            </form>
                          @endif
                          <form action="{{ route('loans.destroy', $loan) }}" method="POST" onsubmit="return confirm('Hapus data peminjaman ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded bg-red-700 px-2 py-1 text-[10px] font-bold text-white hover:bg-red-600" aria-label="Hapus peminjaman"><i class="fa-solid fa-trash"></i></button>
                          </form>
                        @endif
                      </div>
                      @if (Auth::user()->role === 'Admin' || $loan->borrower === Auth::user()->username)
                        <details class="mt-2 text-left" {{ $errors->has('text') && old('loan_id') == $loan->id ? 'open' : '' }}>
                          <summary class="cursor-pointer text-xs font-bold text-gold">Feedback / Tanggapan</summary>
                          <div class="mt-2 min-w-64 space-y-4 rounded-lg border border-maroon bg-black/20 p-4">
                            <div class="max-h-40 space-y-3 overflow-y-auto">
                              @forelse ($loan->feedbacks as $feedback)
                                <div class="rounded border border-maroon/60 bg-black/20 p-3">
                                  <div class="mb-1 flex items-center justify-between gap-2 text-xs">
                                    <span class="font-bold {{ $feedback->role === 'Admin' ? 'text-amber-300' : 'text-teal-300' }}">{{ $feedback->user }}@if ($feedback->role) ({{ $feedback->role }})@endif</span>
                                    <span class="shrink-0 text-gray-500">{{ $feedback->created_at->diffForHumans() }}</span>
                                  </div>
                                  <p class="whitespace-pre-line text-sm text-gray-200">{{ $feedback->pesan }}</p>
                                </div>
                              @empty
                                <p class="text-sm text-gray-400">Belum ada feedback atau tanggapan.</p>
                              @endforelse
                            </div>
                            <form action="{{ route('feedbacks.store', $loan) }}" method="POST" class="space-y-3 border-t border-maroon/60 pt-4">
                              @csrf
                              <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                              <label for="feedback-{{ $loan->id }}" class="block text-sm text-gray-300">{{ Auth::user()->role === 'Admin' ? 'Tulis tanggapan admin' : 'Tulis feedback untuk admin' }}</label>
                              <textarea id="feedback-{{ $loan->id }}" name="text" rows="3" maxlength="1000" required placeholder="Maksimal 1000 karakter..." class="w-full rounded-lg border border-maroon bg-black/40 px-3 py-2 text-sm text-white focus:border-gold focus:outline-none">{{ old('loan_id') == $loan->id ? old('text') : '' }}</textarea>
                              @if ($errors->has('text') && old('loan_id') == $loan->id)
                                <p class="text-xs text-red-300">{{ $errors->first('text') }}</p>
                              @endif
                              <div class="flex justify-end">
                                <button type="submit" class="rounded-lg bg-gold px-3 py-2 text-xs font-bold text-black"><i class="fa-solid fa-paper-plane mr-1"></i> Kirim</button>
                              </div>
                            </form>
                          </div>
                        </details>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="p-6 text-center text-gray-400">Tidak ada data peminjaman.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </section>
      @endif
    </main>
  </div>
</div>

<div id="modal-peminjaman" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/70 p-4 {{ $errors->has('alat') || $errors->has('alat_baru') || $errors->has('borrower') || $errors->has('tanggal_pinjam') || $errors->has('foto') || $errors->has('foto_url') ? '' : 'hidden' }}">
  <div class="my-auto w-full max-w-md space-y-4 rounded-xl border border-maroon bg-maroon-card p-6 shadow-2xl">
    <div class="flex items-center justify-between border-b border-maroon pb-3">
      <h3 class="text-lg font-bold text-gold">Input Peminjaman Alat</h3>
      <button type="button" onclick="document.getElementById('modal-peminjaman').classList.add('hidden')" class="text-gray-400 hover:text-white" aria-label="Tutup"><i class="fa-solid fa-xmark text-lg"></i></button>
    </div>
    <form action="{{ route('loans.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
      @csrf
      <div>
        <label for="alat-pilihan" class="mb-1 block text-gray-300">Pilih Alat</label>
        <select id="alat-pilihan" name="alat" required onchange="toggleAlatBaru(this.value)" class="w-full rounded border border-maroon bg-black/40 px-3 py-2 text-white focus:border-gold focus:outline-none">
          <option value="" disabled {{ old('alat') === '' ? 'selected' : '' }}>Pilih alat peminjaman</option>
          @foreach ($katalogAlat as $kategori => $alatKategori)
            <optgroup label="{{ $kategori }}">
              @foreach ($alatKategori as $namaAlat)
                <option value="{{ $namaAlat }}" {{ old('alat') === $namaAlat ? 'selected' : '' }}>{{ $namaAlat }}</option>
              @endforeach
            </optgroup>
          @endforeach
          @php($alatLama = $daftarAlat->diff(collect(\App\Support\EquipmentCatalog::names())))
          @if ($alatLama->isNotEmpty())
            <optgroup label="Alat yang pernah dipinjam">
              @foreach ($alatLama as $namaAlat)
                <option value="{{ $namaAlat }}" {{ old('alat') === $namaAlat ? 'selected' : '' }}>{{ $namaAlat }}</option>
              @endforeach
            </optgroup>
          @endif
          <option value="__baru__" {{ old('alat') === '__baru__' ? 'selected' : '' }}>Alat baru...</option>
        </select>
        <input id="alat-baru" type="text" name="alat_baru" value="{{ old('alat_baru') }}" maxlength="255" class="mt-2 hidden w-full rounded border border-maroon bg-black/40 px-3 py-2 text-white focus:border-gold focus:outline-none" placeholder="Masukkan nama alat baru">
      </div>
      <div>
        <label for="foto" class="mb-1 block text-gray-300">Upload Foto / URL Foto</label>
        <input id="foto" type="file" name="foto" accept="image/*" class="mb-1 w-full rounded border border-maroon bg-black/40 px-3 py-1 text-[11px] text-gray-300">
        <input type="url" name="foto_url" value="{{ old('foto_url') }}" class="w-full rounded border border-maroon bg-black/40 px-3 py-2 text-white focus:border-gold focus:outline-none" placeholder="Atau URL Foto">
      </div>
      <div>
        <label for="borrower" class="mb-1 block text-gray-300">Nama Peminjam</label>
        <input id="borrower" type="text" value="{{ Auth::user()->username }}" readonly class="w-full rounded border border-maroon bg-black/40 px-3 py-2 text-gray-300">
        <p class="mt-1 text-[10px] text-gray-400">Pengajuan akan dikirim atas nama akun Anda.</p>
      </div>
      <div>
        <label for="tanggal-pinjam" class="mb-1 block text-gray-300">Tanggal Pinjam</label>
        <input id="tanggal-pinjam" type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required class="w-full rounded border border-maroon bg-black/40 px-3 py-2 text-white focus:border-gold focus:outline-none">
      </div>
      <div class="flex justify-end space-x-2 pt-2">
        <button type="button" onclick="document.getElementById('modal-peminjaman').classList.add('hidden')" class="rounded border border-maroon bg-black/40 px-4 py-2 text-gray-300">Batal</button>
        <button type="submit" class="rounded bg-gold px-4 py-2 font-bold text-black">Simpan Peminjaman</button>
      </div>
    </form>
  </div>
</div>

<div id="modal-password" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 {{ $errors->has('password') ? '' : 'hidden' }}">
  <div class="w-full max-w-md space-y-4 rounded-xl border border-maroon bg-maroon-card p-6 shadow-2xl">
    <div class="flex items-center justify-between border-b border-maroon pb-3">
      <h3 class="text-lg font-bold text-gold">Ganti Password Akun</h3>
      <button type="button" onclick="document.getElementById('modal-password').classList.add('hidden')" class="text-gray-400 hover:text-white" aria-label="Tutup"><i class="fa-solid fa-xmark text-lg"></i></button>
    </div>
    <form action="{{ route('password.update') }}" method="POST" class="space-y-3 text-xs">
      @csrf
      <div>
        <label for="password-baru" class="mb-1 block text-gray-300">Password Baru</label>
        <input id="password-baru" type="password" name="password" required minlength="6" class="w-full rounded border border-maroon bg-black/40 px-3 py-2 text-white focus:border-gold focus:outline-none" placeholder="Minimal 6 karakter">
      </div>
      <div class="flex justify-end space-x-2 pt-2">
        <button type="button" onclick="document.getElementById('modal-password').classList.add('hidden')" class="rounded border border-maroon bg-black/40 px-4 py-2 text-gray-300">Batal</button>
        <button type="submit" class="rounded bg-gold px-4 py-2 font-bold text-black">Simpan Password</button>
      </div>
    </form>
  </div>
</div>
<script>
  function toggleAlatBaru(value) {
    const input = document.getElementById('alat-baru');
    const isAlatBaru = value === '__baru__';
    input.classList.toggle('hidden', !isAlatBaru);
    input.required = isAlatBaru;
  }

  function openLoanModal(name) {
    const modal = document.getElementById('modal-peminjaman');
    const select = document.getElementById('alat-pilihan');
    select.value = name;
    toggleAlatBaru(select.value);
    modal.classList.remove('hidden');
  }

  toggleAlatBaru(document.getElementById('alat-pilihan').value);
</script>
@endsection
