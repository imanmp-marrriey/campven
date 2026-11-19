@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Admin Campven')

@section('content')
<div class="min-h-screen bg-slate-50/70 flex">

    @include('partials.admin-sidebar', ['activeAdmin' => 'users'])

    <!-- ===== MAIN CONTENT AREA ===== -->
    <div class="flex-grow md:ml-64 flex flex-col min-h-screen w-full">

        <!-- Top Operational Header -->
        <header class="h-20 bg-white/95 backdrop-blur-md border-b border-slate-200/90 px-4 sm:px-8 flex items-center justify-between gap-3 sticky top-0 z-30">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <button type="button" onclick="toggleAdminSidebar()" class="md:hidden w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center flex-shrink-0" aria-label="Buka Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="relative w-full max-w-sm">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="userSearchInput" onkeyup="filterUsersTable()" placeholder="Cari nama, email, role..." class="w-full pl-10 pr-12 py-2 rounded-full bg-slate-100 border border-transparent focus:border-slate-300 focus:bg-white text-xs text-slate-800 placeholder-slate-400 focus:outline-none transition">
                </div>
            </div>

            <a href="{{ route('profile') }}" class="flex items-center gap-2.5 pl-2 flex-shrink-0">
                <div class="w-9 h-9 rounded-full overflow-hidden bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                    @if(Auth::user()->foto && Storage::disk('public')->exists(Auth::user()->foto))
                        <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    @endif
                </div>
                <div class="text-left hidden sm:block">
                    <span class="block text-xs font-bold text-slate-900 leading-tight">{{ Auth::user()->nama }}</span>
                    <span class="text-[10px] font-medium text-slate-400">Administrator</span>
                </div>
            </a>
        </header>

        <!-- Dashboard Body Container -->
        <div class="p-4 sm:p-8 space-y-8 max-w-7xl w-full mx-auto">

            <!-- Welcome Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-2">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-slate-200 text-[11px] font-bold text-slate-600 mb-2 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        <span>Otoritas Pengguna Kampus</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Manajemen Akun &amp; Hak Akses
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xl">
                        Kelola akun administrator, organisasi penyelenggara acara, serta akun mahasiswa.
                    </p>
                </div>

                <button type="button" onclick="openCreateUserModal()" class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition btn-spring flex items-center gap-2 self-start lg:self-auto">
                    <i class="fas fa-user-plus"></i>
                    <span>Tambah Pengguna Baru</span>
                </button>
            </div>

            <!-- 4 KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">TOTAL PENGGUNA</span>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $total_users }} <span class="text-xs font-semibold text-slate-400">Akun</span></div>
                    <div class="text-[11px] text-slate-500 pt-1 border-t border-slate-100">Keseluruhan akun sistem</div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">ADMINISTRATOR</span>
                    <div class="text-3xl font-black text-amber-700 tracking-tight">{{ $total_admin }} <span class="text-xs font-semibold text-slate-400">Admin</span></div>
                    <div class="text-[11px] text-slate-500 pt-1 border-t border-slate-100">Hak akses pengelola sistem</div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">PENYELENGGARA</span>
                    <div class="text-3xl font-black text-emerald-700 tracking-tight">{{ $total_penyelenggara }} <span class="text-xs font-semibold text-slate-400">Ormawa</span></div>
                    <div class="text-[11px] text-slate-500 pt-1 border-t border-slate-100">BEM, Himpunan &amp; UKM</div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">MAHASISWA</span>
                    <div class="text-3xl font-black text-indigo-700 tracking-tight">{{ $total_mahasiswa }} <span class="text-xs font-semibold text-slate-400">Peserta</span></div>
                    <div class="text-[11px] text-slate-500 pt-1 border-t border-slate-100">Peserta kegiatan</div>
                </div>
            </div>

            <!-- Users Table Container -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="p-6 pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Daftar Seluruh Pengguna</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Kelola identitas, role akses, dan informasi kontak pengguna</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-bold text-xs">
                        {{ count($users) }} Akun
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600" id="usersTable">
                        <thead class="bg-slate-50/70 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">ID &amp; PENGGUNA</th>
                                <th class="px-4 py-3.5">EMAIL</th>
                                <th class="px-4 py-3.5">NO. TELEPON</th>
                                <th class="px-4 py-3.5">ROLE AKSES</th>
                                <th class="px-6 py-3.5 text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $u)
                            @php
                                $roleBadge = [
                                    'admin' => 'bg-amber-50 text-amber-800 border-amber-200',
                                    'penyelenggara' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                    'mahasiswa' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition user-row">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-2xl bg-slate-900 text-white font-bold flex items-center justify-center text-xs flex-shrink-0">
                                            @if($u->foto && Storage::disk('public')->exists($u->foto))
                                                <img src="{{ Storage::url($u->foto) }}" alt="Foto" class="w-full h-full object-cover rounded-2xl">
                                            @else
                                                {{ strtoupper(substr($u->nama, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block text-xs">
                                                {{ $u->nama }}
                                                @if((int) $u->id === (int) Auth::id())
                                                    <span class="text-[10px] text-indigo-600 font-extrabold">(Anda)</span>
                                                @endif
                                            </span>
                                            <span class="text-[10px] text-slate-400 font-mono">#ID-{{ $u->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 font-semibold text-slate-700">
                                    {{ $u->email }}
                                </td>
                                <td class="px-4 py-4 text-slate-500">
                                    {{ $u->no_telp ?? '-' }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border {{ $roleBadge[$u->role] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" onclick="openEditUserModal({{ $u->id }}, '{{ addslashes($u->nama) }}', '{{ addslashes($u->email) }}', '{{ addslashes($u->no_telp ?? '') }}', '{{ $u->role }}', {{ (int) $u->id === (int) Auth::id() ? 'true' : 'false' }})" class="p-2 rounded-xl bg-slate-100 text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 transition" title="Edit Pengguna">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>

                                        @if($u->role !== 'admin' && (int) $u->id !== (int) Auth::id())
                                            <button type="button" onclick="openDeleteModal('{{ route('admin.user.delete', $u->id) }}', '{{ addslashes($u->nama) }}')" class="p-2 rounded-xl bg-slate-100 text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus Pengguna">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        @else
                                            <span class="text-[10px] font-bold text-slate-400 px-2 py-1 rounded bg-slate-100">
                                                Dilindungi
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ===== MODAL TAMBAH USER ===== -->
<div id="createUserModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="createUserBackdrop" onclick="closeCreateUserModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>

    <div id="createUserCard" class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fas fa-user-plus text-indigo-600"></i>
                <span>Tambah Pengguna Baru</span>
            </h3>
            <button type="button" onclick="closeCreateUserModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm hover:bg-slate-200 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.user.store') }}" class="space-y-4 text-xs">
            @csrf

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" required placeholder="Contoh: BEM Polindra atau Nama Mahasiswa" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Alamat Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" required placeholder="email@campven.com" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Kata Sandi Akun <span class="text-rose-500">*</span></label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Nomor Telepon / WA</label>
                    <input type="text" name="no_telp" placeholder="08123456789" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Role Hak Akses <span class="text-rose-500">*</span></label>
                    <select name="role" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                        <option value="mahasiswa">Mahasiswa / Peserta</option>
                        <option value="penyelenggara">Penyelenggara / Ormawa</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCreateUserModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/20 transition btn-spring">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL EDIT USER ===== -->
<div id="editUserModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div id="editUserBackdrop" onclick="closeEditUserModal()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>

    <div id="editUserCard" class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fas fa-user-edit text-indigo-600"></i>
                <span>Edit Pengguna</span>
            </h3>
            <button type="button" onclick="closeEditUserModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm hover:bg-slate-200 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="editUserForm" method="POST" action="" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" id="editNama" name="nama" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Alamat Email <span class="text-rose-500">*</span></label>
                <input type="email" id="editEmail" name="email" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Kata Sandi Baru (Opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Nomor Telepon / WA</label>
                    <input type="text" id="editTelp" name="no_telp" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Role Hak Akses <span class="text-rose-500">*</span></label>
                    <select id="editRole" name="role" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                        <option value="mahasiswa">Mahasiswa / Peserta</option>
                        <option value="penyelenggara">Penyelenggara / Ormawa</option>
                        <option value="admin">Administrator</option>
                    </select>
                    <input type="hidden" id="editRoleFallback" name="role" value="admin" disabled>
                    <span id="editRoleSelfNotice" class="hidden text-[10px] text-amber-600 font-semibold">Role akun sendiri terkunci sebagai Administrator.</span>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditUserModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/20 transition btn-spring">
                    Perbarui Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const userModal = document.getElementById('createUserModal');
    const userBackdrop = document.getElementById('createUserBackdrop');
    const userCard = document.getElementById('createUserCard');

    function openCreateUserModal() {
        userModal.classList.remove('hidden');
        userModal.classList.add('flex');
        setTimeout(() => {
            userBackdrop.classList.remove('opacity-0');
            userCard.classList.remove('opacity-0', 'scale-95');
            userCard.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeCreateUserModal() {
        userBackdrop.classList.add('opacity-0');
        userCard.classList.add('opacity-0', 'scale-95');
        userCard.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => {
            userModal.classList.add('hidden');
            userModal.classList.remove('flex');
        }, 300);
    }

    const editUserModal = document.getElementById('editUserModal');
    const editUserBackdrop = document.getElementById('editUserBackdrop');
    const editUserCard = document.getElementById('editUserCard');

    function openEditUserModal(id, nama, email, telp, role, isSelf) {
        document.getElementById('editUserForm').action = '/admin/user/' + id;
        document.getElementById('editNama').value = nama;
        document.getElementById('editEmail').value = email;
        document.getElementById('editTelp').value = telp;

        const roleSelect = document.getElementById('editRole');
        const roleFallback = document.getElementById('editRoleFallback');
        const notice = document.getElementById('editRoleSelfNotice');
        roleSelect.value = role;

        if (isSelf) {
            roleSelect.disabled = true;
            roleFallback.disabled = false;
            roleFallback.value = role;
            notice.classList.remove('hidden');
        } else {
            roleSelect.disabled = false;
            roleFallback.disabled = true;
            notice.classList.add('hidden');
        }

        editUserModal.classList.remove('hidden');
        editUserModal.classList.add('flex');
        setTimeout(() => {
            editUserBackdrop.classList.remove('opacity-0');
            editUserCard.classList.remove('opacity-0', 'scale-95');
            editUserCard.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeEditUserModal() {
        editUserBackdrop.classList.add('opacity-0');
        editUserCard.classList.add('opacity-0', 'scale-95');
        editUserCard.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => {
            editUserModal.classList.add('hidden');
            editUserModal.classList.remove('flex');
        }, 300);
    }

    function filterUsersTable() {
        const query = document.getElementById('userSearchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#usersTable tbody tr');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
        });
    }
</script>
@endpush
