@extends('layouts.customPage.sidebar')

@section('title', 'Administrator | Edudigital')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-indigo-800 to-indigo-950 p-6 md:p-8 rounded-2xl shadow-sm text-white border border-slate-800">
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ Auth::user()->name }}</h1>
        <p class="text-indigo-200 mt-2 text-sm md:text-base font-medium">Anda masuk sebagai Administrator. Kelola data sekolah, jurusan, siswa, dan input nilai dengan mudah.</p>
    </div>

    <!-- Quick Navigation Cards / Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card 1: Kelola Jurusan -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg text-2xl">💼</div>
                <div>
                    <h3 class="font-bold text-slate-800">Kelola Jurusan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Atur departemen dan keahlian.</p>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.manageDepartment') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                    Buka Jurusan
                    <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Card 2: Kelola Siswa -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg text-2xl">👥</div>
                <div>
                    <h3 class="font-bold text-slate-800">Kelola Siswa</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Atur data profil dan kelas siswa.</p>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.manageStudent') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                    Buka Siswa
                    <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Card 3: Input Nilai -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg text-2xl">📝</div>
                <div>
                    <h3 class="font-bold text-slate-800">Input Nilai</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Masukkan tugas, ujian, dan ASAS.</p>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.manageScore') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                    Mulai Input Nilai
                    <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection