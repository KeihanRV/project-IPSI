<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#3d3400] leading-tight">
            Profil Saya
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#f5f5f0] py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 rounded-[2rem] bg-[#fffbed] p-8 shadow-xl shadow-slate-200">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-[#7a702b]">Akun</p>
                        <h1 class="mt-2 text-3xl font-semibold text-[#3d3400]">Halaman Profile</h1>
                    </div>
                    <p class="rounded-full bg-[#e9e1c3] px-4 py-2 text-sm font-medium text-[#5a4a0a] shadow-inner">
                        Terdaftar sejak {{ $user->created_at->translatedFormat('j F Y') }}
                    </p>
                </div>
            </div>

            <form id="form-profile-update" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="hidden">
                @csrf
                @method('patch')
            </form>

            <div class="w-full min-w-0 grid gap-8 md:grid-cols-[360px_minmax(0,1fr)]">
                
                <aside class="space-y-6">
                    
                    <div class="rounded-[2rem] bg-[#5a4a0a] p-8 text-[#fffdfa] shadow-xl shadow-slate-300">
                        <div class="flex flex-col items-center text-center">
                            <div class="relative mx-auto h-64 w-64 max-w-[20rem]">
                                <img
                                    src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=5a4a0a&color=ffffff&size=256' }}"
                                    alt="Foto Profil"
                                    class="h-full w-full rounded-full object-cover shadow-inner ring-4 ring-[#f5f5f0]"
                                />
                                <label for="profile_picture" class="absolute bottom-3 right-3 inline-flex h-12 w-12 cursor-pointer items-center justify-center rounded-full bg-[#fffdfa] text-[#5a4a0a] shadow-lg transition hover:bg-[#f2e8c1]">
                                    <i class="fa-solid fa-camera"></i>
                                    <span class="sr-only">Ubah foto profil</span>
                                </label>
                            </div>
                            <input form="form-profile-update" id="profile_picture" name="profile_picture" type="file" accept="image/*" class="hidden" />
                            <!-- <div class="mt-6 space-y-2 text-left">
                                <p class="text-sm text-[#e3d9b0]">Foto profil saat ini</p>
                                <p class="text-lg font-semibold text-[#fffdf0]">{{ $user->name }}</p>
                                <p class="text-sm text-[#e3d9b0]">{{ $user->email }}</p>
                            </div> -->
                        </div>
                        <div class="mt-8 rounded-[1.5rem] bg-[#f4ecd1] p-5 text-[#5a4a0a] shadow-inner">
                            <!-- <p class="text-sm font-semibold uppercase tracking-[0.2em]">Tip</p> -->
                            <p class="mt-2 text-sm leading-6">Unggah foto profil terbaru dengan ukuran maksimal 2MB. Format JPG, PNG, atau WEBP.</p>
                        </div>
                    </div>

                    <div class="rounded-[2rem] bg-[#5a4a0a] p-8 text-[#fffdfa] shadow-xl shadow-slate-300">
                        <h2 class="text-xl font-semibold">Reset Password</h2>
                        <p class="mt-2 text-sm text-[#d4c88a]">Ubah kata sandi akun Anda.</p>
                        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-4">
                            @csrf
                            @method('put')
                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-[#f4ecd1]">Password Saat Ini</label>
                                <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-semibold text-[#f4ecd1]">Password Baru</label>
                                <input id="password" name="password" type="password" autocomplete="new-password" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-[#f4ecd1]">Konfirmasi Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-3xl bg-[#d4b95f] px-6 py-3 text-sm font-semibold uppercase tracking-[0.15em] text-[#3d3400] transition hover:bg-[#e3ca75]">
                                    Reset Password
                                </button>
                                @if (session('status') === 'password-updated')
                                    <p class="text-sm text-[#d9d4b2]">Password berhasil diperbarui.</p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="rounded-[2rem] bg-[#5a4a0a] p-8 text-[#fffdfa] shadow-xl shadow-slate-300">
                        <h2 class="text-xl font-semibold">Hapus Akun</h2>
                        <p class="mt-2 text-sm text-[#d4c88a]">Tindakan ini tidak dapat dibatalkan.</p>
                        <div class="mt-6">
                            <button
                                type="button"
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                                class="inline-flex w-full items-center justify-center rounded-3xl bg-[#e74c3c] px-6 py-3 text-sm font-semibold uppercase tracking-[0.15em] text-white transition hover:bg-[#ce3b34]"
                            >
                                Hapus Akun
                            </button>
                        </div>
                        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                @csrf
                                @method('delete')
                                <h2 class="text-lg font-semibold text-[#3d3400]">Anda yakin ingin menghapus akun?</h2>
                                <p class="mt-2 text-sm text-[#5a4a0a]">Semua data akan dihapus secara permanen. Masukkan kata sandi untuk konfirmasi.</p>
                                <div class="mt-6">
                                    <label for="delete_password" class="sr-only">{{ __('Password') }}</label>
                                    <input
                                        id="delete_password"
                                        name="password"
                                        type="password"
                                        class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]"
                                        placeholder="Password"
                                    />
                                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-sm text-[#f8d4c3]" />
                                </div>
                                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                                    <button type="button" x-on:click="$dispatch('close')" class="inline-flex items-center justify-center rounded-3xl bg-[#e9e1c3] px-6 py-3 text-sm font-semibold text-[#5a4a0a] transition hover:bg-[#f3e8c2]">
                                        Batal
                                    </button>
                                    <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-[#e74c3c] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#ce3b34]">
                                        Hapus Akun
                                    </button>
                                </div>
                            </form>
                        </x-modal>
                    </div>

                    <div class="rounded-[2rem] bg-[#5a4a0a] p-8 text-[#fffdfa] shadow-xl shadow-slate-300">
                        <div class="space-y-4">
                            <div>
                                <h2 class="text-xl font-semibold">Aksi Profil</h2>
                                <p class="mt-1 text-sm text-[#d4c88a]">Simpan perubahan informasi profil.</p>
                            </div>
                            <button form="form-profile-update" type="submit" class="inline-flex w-full items-center justify-center rounded-3xl bg-[#d4b95f] px-6 py-3 text-sm font-semibold uppercase tracking-[0.15em] text-[#3d3400] transition hover:bg-[#e3ca75]">
                                Simpan Profile
                            </button>
                            @if (session('status') === 'profile-updated')
                                <p class="text-sm text-[#d9d4b2]">Perubahan tersimpan.</p>
                            @endif
                        </div>
                    </div>

                </aside>

                <section class="min-w-0 space-y-6">
                    
                    <div class="rounded-[2rem] bg-[#5a4a0a] p-8 text-[#fffdfa] shadow-xl shadow-slate-300">
                        <h2 class="text-xl font-semibold">Informasi Utama</h2>
                        <p class="mt-2 text-sm text-[#d4c88a]">Data dasar akun yang dapat diperbarui.</p>
                        <div class="mt-8 grid gap-4 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-[#f4ecd1]">Nama Lengkap</label>
                                <input form="form-profile-update" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-sm font-semibold text-[#f4ecd1]">Email</label>
                                <input form="form-profile-update" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                            <div class="sm:col-span-2">
                                <label for="role" class="block text-sm font-semibold text-[#f4ecd1]">Role</label>
                                <input id="role" type="text" value="{{ $user->role }}" disabled class="mt-2 w-full rounded-3xl border-0 bg-[#ece3c7] px-4 py-3 text-[#3d3400] shadow-inner" />
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2rem] bg-[#5a4a0a] p-8 text-[#fffdfa] shadow-xl shadow-slate-300">
                        <h2 class="text-xl font-semibold">Informasi Pribadi</h2>
                        <p class="mt-2 text-sm text-[#d4c88a]">Data kontak dan identitas tambahan.</p>
                        <div class="mt-8 grid gap-4 sm:grid-cols-3">
                            <div class="sm:col-span-3">
                                <label for="phone_number" class="block text-sm font-semibold text-[#f4ecd1]">Nomor HP</label>
                                <input form="form-profile-update" id="phone_number" name="phone_number" type="text" value="{{ old('phone_number', $user->phone_number) }}" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]" />
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                            <div>
                                <label for="birth_date" class="block text-sm font-semibold text-[#f4ecd1]">Tanggal Lahir</label>
                                <input form="form-profile-update" id="birth_date" name="birth_date" type="date" value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d')) }}" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]" />
                                <x-input-error :messages="$errors->get('birth_date')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                            <div>
                                <label for="birthplace" class="block text-sm font-semibold text-[#f4ecd1]">Tempat Lahir</label>
                                <input form="form-profile-update" id="birthplace" name="birthplace" type="text" value="{{ old('birthplace', $user->birthplace) }}" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]" />
                                <x-input-error :messages="$errors->get('birthplace')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2rem] bg-[#5a4a0a] p-8 text-[#fffdfa] shadow-xl shadow-slate-300">
                        <h2 class="text-xl font-semibold">Alamat</h2>
                        <p class="mt-2 text-sm text-[#d4c88a]">Lengkapi data alamat untuk pengalaman checkout yang lebih baik.</p>
                        <div class="mt-8 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="province" class="block text-sm font-semibold text-[#f4ecd1]">Provinsi</label>
                                <input form="form-profile-update" id="province" name="province" type="text" value="{{ old('province', $user->province) }}" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]" />
                                <x-input-error :messages="$errors->get('province')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-semibold text-[#f4ecd1]">Kota</label>
                                <input form="form-profile-update" id="city" name="city" type="text" value="{{ old('city', $user->city) }}" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]" />
                                <x-input-error :messages="$errors->get('city')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                            <div>
                                <label for="district" class="block text-sm font-semibold text-[#f4ecd1]">Kecamatan</label>
                                <input form="form-profile-update" id="district" name="district" type="text" value="{{ old('district', $user->district) }}" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]" />
                                <x-input-error :messages="$errors->get('district')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                            <div>
                                <label for="postal_code" class="block text-sm font-semibold text-[#f4ecd1]">Kode Pos</label>
                                <input form="form-profile-update" id="postal_code" name="postal_code" type="text" value="{{ old('postal_code', $user->postal_code) }}" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]" />
                                <x-input-error :messages="$errors->get('postal_code')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                            <div class="sm:col-span-2">
                                <label for="address" class="block text-sm font-semibold text-[#f4ecd1]">Alamat Lengkap</label>
                                <textarea form="form-profile-update" id="address" name="address" rows="4" class="mt-2 w-full rounded-3xl border-0 bg-[#f4ecd1] px-4 py-3 text-[#3d3400] shadow-inner focus:outline-none focus:ring-4 focus:ring-[#e7d99d]">{{ old('address', $user->address) }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2 text-sm text-[#f8d4c3]" />
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </div>
    </div>
</x-app-layout>