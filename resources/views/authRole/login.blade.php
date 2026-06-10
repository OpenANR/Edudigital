<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Penilaian</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-between">

    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-screen w-full relative overflow-hidden bg-slate-900">
        <!-- Background Image with Cover (Shared across entire screen) -->
        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-10000 ease-out transform scale-105 hover:scale-100"
             style="background-image: url('{{ asset('image/backgroundLogin.jpg') }}');">
        </div>
        
        <!-- Sleek Overlay (Shared across entire screen) -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-950/90 via-blue-900/80 to-slate-950/90"></div>

        <!-- LEFT COLUMN: Form Login -->
        <div class="lg:col-span-6 flex flex-col justify-between p-8 sm:p-12 md:p-16 bg-white shadow-2xl z-10 lg:rounded-r-[2.5rem] relative">
            <!-- Top Header (Logo for Mobile) -->
            <div class="w-full">
                <!-- Mobile Logo -->
                <div class="flex flex-col items-center justify-center mb-8 lg:hidden">
                    <div class="p-2.5 bg-blue-50 rounded-2xl mb-2">
                        <img src="{{ asset('logo/logoSmk.png') }}" alt="Logo SMK" class="w-12 h-12 object-contain">
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Portal Penilaian</h2>
                    <p class="text-xs text-slate-500 font-medium">SMK Digital</p>
                </div>
            </div>

            <!-- Main Form Card Container -->
            <div class="w-full max-w-md mx-auto my-auto py-8">
                <!-- Title & Greeting -->
                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">
                        Selamat Datang
                    </h1>
                    <p class="text-slate-500 font-medium">
                        Silakan masuk dengan menggunakan Kode Guru dan password Anda.
                    </p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-xl text-rose-800 shadow-sm">
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="font-bold text-sm">Gagal Masuk</span>
                        </div>
                        <ul class="list-disc pl-5 text-xs space-y-1 font-medium text-left">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Kode Guru Input -->
                    <div class="space-y-2 text-left">
                        <label for="kode_guru" class="block text-sm font-semibold text-slate-700">Kode Guru</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input type="text" id="kode_guru" name="kode_guru" value="{{ old('kode_guru') }}" required autofocus
                                placeholder="Contoh: G-12345"
                                class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-800 placeholder-slate-400 transition-all duration-200 text-sm font-medium outline-none">
                        </div>
                    </div>

                    <!-- Role Select Input -->
                    <div class="space-y-2 text-left">
                        <label for="role" class="block text-sm font-semibold text-slate-700">Masuk Sebagai</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <select id="role" name="role" required
                                class="block w-full pl-11 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-800 placeholder-slate-400 transition-all duration-200 text-sm font-medium outline-none appearance-none">
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Hak Akses</option>
                                <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>👨‍🏫 Guru Pengajar</option>
                                <option value="wali_kelas" {{ old('role') == 'wali_kelas' ? 'selected' : '' }}>📋 Wali Kelas</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>⚙️ Administrator</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Password / Tgl Lahir Input -->
                    <div class="space-y-2 text-left" x-data="{ showPassword: false }">
                        <div class="flex justify-between items-center">
                            <label for="password" class="block text-sm font-semibold text-slate-700">Password / Tgl Lahir</label>
                        </div>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                placeholder="Contoh: 15081985"
                                class="block w-full pl-11 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-800 placeholder-slate-400 transition-all duration-200 text-sm font-medium outline-none">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors duration-200">
                                <!-- Eye Icon -->
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <!-- Eye Off Icon -->
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Masuk Aplikasi
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="w-full text-center mt-8">
                <p class="text-xs text-slate-400 font-medium">
                    &copy; {{ date('Y') }} Portal Penilaian Edudigital. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>

        <!-- RIGHT COLUMN: Content Showcase (Desktop only) -->
        <div class="hidden lg:col-span-6 lg:flex relative justify-center items-center z-10">
            <!-- Glassmorphism Container for Branding -->
            <div class="relative z-10 max-w-md p-10 mx-6 rounded-3xl border border-white/10 backdrop-blur-md bg-white/5 shadow-2xl text-center flex flex-col items-center">
                <!-- Logo Frame -->
                <div class="mb-6 p-4 rounded-2xl bg-white/10 backdrop-blur-sm shadow-inner inline-block border border-white/20">
                    <img src="{{ asset('logo/logoSmk.png') }}" alt="Logo SMK" class="w-28 h-28 object-contain filter drop-shadow-lg">
                </div>
                
                <!-- Brand Info -->
                <h2 class="text-3xl font-extrabold text-white tracking-tight mb-3">
                    Portal Penilaian
                </h2>
                
                <div class="w-16 h-1 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mb-6"></div>
                
                <p class="text-slate-200/90 leading-relaxed text-sm font-medium">
                    Platform Penilaian Digital Terintegrasi untuk Kemudahan Evaluasi, Pemantauan Hasil Belajar, dan Pelaporan Akademik secara Real-time dan Profesional.
                </p>
            </div>

            <!-- Top Corner Accent -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <!-- Bottom Corner Accent -->
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl -ml-20 -mb-20"></div>
        </div>
    </div>

</body>
</html>