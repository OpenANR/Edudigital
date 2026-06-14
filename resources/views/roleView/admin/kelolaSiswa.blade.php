@extends('layouts.customPage.sidebar')

@section('title', 'Kelola Siswa | Edudigital')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kelola Siswa</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar lengkap siswa terdaftar beserta kelas dan jurusan.</p>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-slate-700 uppercase font-bold text-xs tracking-wider border-b border-slate-200">
                        <th class="px-6 py-3.5 text-center border-r border-slate-200">No</th>
                        <th class="px-6 py-3.5 text-left border-r border-slate-200">NISN</th>
                        <th class="px-6 py-3.5 text-left border-r border-slate-200">Nama Siswa</th>
                        <th class="px-6 py-3.5 text-center border-r border-slate-200">Kelas</th>
                        <th class="px-6 py-3.5 text-left">Jurusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    @forelse ($student as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-center border-r border-slate-200 font-semibold">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 border-r border-slate-200 font-mono text-slate-600">{{ $item->nisn }}</td>
                            <td class="px-6 py-4 border-r border-slate-200 font-bold text-slate-900">{{ $item->nama_siswa }}</td>
                            <td class="px-6 py-4 border-r border-slate-200 text-center font-semibold text-indigo-600 bg-indigo-50/20">{{ $item->classrooms?->nama_kelas ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $item->departments->nama_jurusan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium bg-slate-50">
                                📭 Data siswa kosong.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection