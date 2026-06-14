@extends('layouts.customPage.sidebar')

@section('title', 'Kelola Jurusan | Edudigital')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kelola Jurusan</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar kompetensi keahlian dan jurusan yang tersedia.</p>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-slate-700 uppercase font-bold text-xs tracking-wider border-b border-slate-200">
                        <th class="px-6 py-3.5 text-center border-r border-slate-200">No</th>
                        <th class="px-6 py-3.5 text-left border-r border-slate-200">Kode Jurusan</th>
                        <th class="px-6 py-3.5 text-left">Nama Jurusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    @forelse ($departments as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-center border-r border-slate-200 font-semibold">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 border-r border-slate-200 font-mono text-indigo-600 font-semibold">{{ $item->kode_jurusan }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $item->nama_jurusan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-slate-500 font-medium bg-slate-50">
                                📭 Data jurusan kosong.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection