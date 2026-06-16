@extends('layouts.customPage.sidebar')

@section('title', 'Input Nilai Guru | Edudigital')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kelola Input Nilai (Guru)</h1>
        <p class="text-sm text-slate-500 mt-1">Masukkan dan perbarui nilai siswa kelas dan mata pelajaran yang Anda ampu.</p>
    </div>

    <!-- Session Notifications -->
    @if(session('success'))
        <div class="flex items-center p-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-lg shadow-sm">
            <span class="text-lg mr-2">✅</span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center p-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-lg shadow-sm">
            <span class="text-lg mr-2">❌</span>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Lock Notifications -->
    @if(isset($isTugasOpen) && isset($isAsasOpen))
        @if(!$isTugasOpen && !$isAsasOpen)
            <div class="flex items-start p-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-lg shadow-sm">
                <span class="text-lg mr-3">🔒</span>
                <div>
                    <h4 class="font-bold text-sm">Akses Penilaian Ditutup!</h4>
                    <p class="text-xs mt-1 text-rose-600">Waktu pengisian nilai telah berakhir atau belum dimulai. Anda hanya dapat melihat data (Read Only).</p>
                </div>
            </div>
        @elseif(!$isTugasOpen)
            <div class="flex items-start p-4 text-yellow-800 bg-yellow-50 border border-yellow-200 rounded-lg shadow-sm">
                <span class="text-lg mr-3">⚠️</span>
                <div>
                    <h4 class="font-bold text-sm">Akses Pengeditan Tugas & ASTS Ditutup!</h4>
                    <p class="text-xs mt-1 text-yellow-600">Anda hanya dapat mengedit nilai ASAS Genap. Kolom Tugas & ASTS dikunci (Read Only).</p>
                </div>
            </div>
        @elseif(!$isAsasOpen)
            <div class="flex items-start p-4 text-yellow-800 bg-yellow-50 border border-yellow-200 rounded-lg shadow-sm">
                <span class="text-lg mr-3">⚠️</span>
                <div>
                    <h4 class="font-bold text-sm">Akses Pengeditan ASAS Genap Ditutup!</h4>
                    <p class="text-xs mt-1 text-yellow-600">Anda hanya dapat mengedit nilai Tugas & ASTS. Kolom ASAS Genap dikunci (Read Only).</p>
                </div>
            </div>
        @endif
    @endif

    <!-- Filter Card -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <form action="{{ route('guru.manageScore') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Kelas</label>
                <select name="classroom_id" class="block w-full rounded-lg border-slate-300 text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">--- PILIH KELAS ---</option>
                    @foreach($classrooms as $class)
                        <option value="{{ $class->id }}" {{ $classroomId == $class->id ? 'selected' : '' }}>
                            {{ $class->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Mata Pelajaran</label>
                <select name="subject_id" class="block w-full rounded-lg border-slate-300 text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">--- PILIH MAPEL ---</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $subjectId == $subject->id ? 'selected' : '' }}>
                            {{ $subject->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                    Tampilkan Data
                </button>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    @if($students->isNotEmpty())
    <div class="space-y-4">
        <!-- Mode & Info Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-slate-100 p-4 rounded-xl border border-slate-200 gap-4">
            <div>
                <span class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Kelas: {{ $selectedSubject ? $students->first()->classrooms->nama_kelas ?? '' : '' }}</span>
                <span class="mx-2 text-slate-400">|</span>
                <span class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Mapel: {{ $selectedSubject?->nama_mapel }}</span>
            </div>
            
            @if($selectedSubject && $selectedSubject->kelompok_mapel === 'regular')
                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <label for="global-asas-mode" class="text-sm font-semibold text-slate-700 shrink-0">Pilih Mode Asas:</label>
                    <select id="global-asas-mode" onchange="recalculateAll()" {{ !$isAsasOpen ? 'disabled' : '' }} class="block w-full sm:w-auto rounded-lg border-slate-300 text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="FastTrack" selected>Fast Track (Ketik Jumlah Benar)</option>
                        <option value="Benar">Ketik Nomor Benar (Koma)</option>
                        <option value="Salah">Ketik Nomor Salah (Koma)</option>
                    </select>
                </div>
            @endif
        </div>

        <form action="{{ route('guru.saveScore') }}" method="POST">
            @csrf
            <input type="hidden" name="subject_id" value="{{ $subjectId }}">
            @if($selectedSubject && $selectedSubject->kelompok_mapel === 'regular')
                <input type="hidden" name="mode" id="form-mode" value="FastTrack">
            @endif

            <!-- Scrollable Table Container -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 text-slate-700 uppercase font-bold text-xs tracking-wider border-b border-slate-200">
                                <th class="px-4 py-3 text-center border-r border-slate-200">NO</th>
                                <th class="px-6 py-3 text-left border-r border-slate-200 sticky left-0 bg-slate-50 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">NAMA SISWA / NISN</th>
                                <th class="px-3 py-3 text-center border-r border-slate-200 bg-slate-100">TUGAS 1 {{ !$isTugasOpen ? '🔒' : '' }}</th>
                                <th class="px-3 py-3 text-center border-r border-slate-200 bg-slate-100">TUGAS 2 {{ !$isTugasOpen ? '🔒' : '' }}</th>
                                <th class="px-4 py-3 text-center border-r border-slate-200 bg-slate-100">ASTS {{ !$isTugasOpen ? '🔒' : '' }}</th>
                                <th class="px-3 py-3 text-center border-r border-slate-200 bg-yellow-50">TUGAS 4 {{ !$isTugasOpen ? '🔒' : '' }}</th>
                                <th class="px-3 py-3 text-center border-r border-slate-200 bg-yellow-50">TUGAS 5 {{ !$isTugasOpen ? '🔒' : '' }}</th>
                                @if($selectedSubject && $selectedSubject->kelompok_mapel === "regular")
                                    <th class="px-4 py-3 text-center border-r border-slate-200 bg-sky-50">INPUT PG ASAS GENAP {{ !$isAsasOpen ? '🔒' : '' }}</th>
                                    <th class="px-4 py-3 text-center border-r border-slate-200 bg-sky-50">
                                        <div>INPUT ESSAI (PER SOAL) {{ !$isAsasOpen ? '🔒' : '' }}</div>
                                        <div class="text-[9px] text-slate-500 font-normal mt-1 normal-case">Pilih skor: 8 (Benar) | 4 (Sebagian) | 2 (Ongkos) | 0</div>
                                    </th>
                                    <th class="px-4 py-3 text-center border-r border-slate-200 bg-sky-100">MURNI ASAS GENAP</th>
                                    <th class="px-4 py-3 text-center border-r border-slate-200 bg-rose-50">PERBAIKAN {{ !$isAsasOpen ? '🔒' : '' }}</th>
                                @else
                                    <th class="px-4 py-3 text-center border-r border-slate-200 bg-sky-50">NILAI ASAS {{ !$isAsasOpen ? '🔒' : '' }}</th>
                                @endif
                                <th class="px-4 py-3 text-center border-r border-slate-200 bg-slate-100">KETUNTASAN</th>
                                <th class="px-4 py-3 text-center bg-emerald-50">NILAI AKHIR</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-slate-700">
                            @foreach($students as $student)
                                @php
                                    $currentScore = $student->scores->first();
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3.5 text-center border-r border-slate-200 font-semibold">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-3.5 border-r border-slate-200 sticky left-0 bg-white hover:bg-slate-50 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                        <div class="font-bold text-slate-900">{{ $student->nama_siswa }}</div>
                                        <div class="text-xs text-slate-500 font-medium mt-0.5">{{ $student->nisn }}</div>
                                    </td>

                                    <!-- Tasks and ASTS -->
                                    <td class="px-3 py-3.5 border-r border-slate-200 bg-slate-50/50">
                                        <input type="number" name="scores[{{ $student->id }}][tugas1]" id="t1-{{ $student->id }}" value="{{ $currentScore?->tugas1 }}" {{ !$isTugasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} oninput="this.value = this.value.replace(/[^0-9]/g, ''); calculateAkhir('{{ $student->id }}')" onkeydown="handleEnter(event, 't1', {{ $loop->index }})" min="0" class="block w-16 mx-auto rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center text-sm font-semibold p-1.5">
                                    </td>
                                    <td class="px-3 py-3.5 border-r border-slate-200 bg-slate-50/50">
                                        <input type="number" name="scores[{{ $student->id }}][tugas2]" id="t2-{{ $student->id }}" value="{{ $currentScore?->tugas2 }}" {{ !$isTugasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} oninput="this.value = this.value.replace(/[^0-9]/g, ''); calculateAkhir('{{ $student->id }}')" onkeydown="handleEnter(event, 't2', {{ $loop->index }})" min="0" class="block w-16 mx-auto rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center text-sm font-semibold p-1.5">
                                    </td>
                                    <td class="px-3 py-3.5 border-r border-slate-200 bg-slate-50/50">
                                        <input type="number" name="scores[{{ $student->id }}][asts]" id="asts-{{ $student->id }}" value="{{ $currentScore?->asts }}" {{ !$isTugasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} oninput="this.value = this.value.replace(/[^0-9]/g, ''); calculateAkhir('{{ $student->id }}')" onkeydown="handleEnter(event, 'asts', {{ $loop->index }})" min="0" class="block w-16 mx-auto rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center text-sm font-bold p-1.5">
                                    </td>
                                    <td class="px-3 py-3.5 border-r border-slate-200 bg-yellow-50/20">
                                        <input type="number" name="scores[{{ $student->id }}][tugas4]" id="t4-{{ $student->id }}" value="{{ $currentScore?->tugas4 }}" {{ !$isTugasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} oninput="this.value = this.value.replace(/[^0-9]/g, ''); calculateAkhir('{{ $student->id }}')" onkeydown="handleEnter(event, 't4', {{ $loop->index }})" min="0" class="block w-16 mx-auto rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center text-sm font-semibold p-1.5">
                                    </td>
                                    <td class="px-3 py-3.5 border-r border-slate-200 bg-yellow-50/20">
                                        <input type="number" name="scores[{{ $student->id }}][tugas5]" id="t5-{{ $student->id }}" value="{{ $currentScore?->tugas5 }}" {{ !$isTugasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} oninput="this.value = this.value.replace(/[^0-9]/g, ''); calculateAkhir('{{ $student->id }}')" onkeydown="handleEnter(event, 't5', {{ $loop->index }})" min="0" class="block w-16 mx-auto rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center text-sm font-semibold p-1.5">
                                    </td>

                                    <!-- ASAS Fields -->
                                    @if($selectedSubject && $selectedSubject->kelompok_mapel === "regular")
                                        <td class="px-3 py-3.5 border-r border-slate-200 bg-sky-50/20">
                                            <input type="text" name="scores[{{ $student->id }}][pg_asas]" id="pg-{{ $student->id }}" value="{{ $currentScore?->pg_asas }}" {{ !$isAsasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} oninput="calculateMurni('{{ $student->id }}')" onkeydown="handleEnter(event, 'pg', {{ $loop->index }})" placeholder="Cth: 24" class="block w-24 mx-auto rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center text-sm p-1.5">
                                        </td>
                                        <td class="px-3 py-3.5 border-r border-slate-200 bg-sky-50/20">
                                            @php
                                                $n1 = $currentScore?->n1 ?? 0;
                                                $n2 = $currentScore?->n2 ?? 0;
                                                $n3 = $currentScore?->n3 ?? 0;
                                                $n4 = $currentScore?->n4 ?? 0;
                                                $n5 = $currentScore?->n5 ?? 0;
                                            @endphp
                                            <div class="flex items-center justify-center gap-2">
                                                <div class="flex flex-col items-center">
                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">N1</span>
                                                    <select name="scores[{{ $student->id }}][n1]" id="n1-{{ $student->id }}" {{ !$isAsasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} onchange="calculateMurni('{{ $student->id }}')" class="block w-14 rounded border-slate-300 text-xs font-semibold py-1 px-1.5 focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer text-left pl-3.5 pr-4">
                                                        <option value="0" {{ $n1 == 0 ? 'selected' : '' }}>0</option>
                                                        <option value="2" {{ $n1 == 2 ? 'selected' : '' }}>2</option>
                                                        <option value="4" {{ $n1 == 4 ? 'selected' : '' }}>4</option>
                                                        <option value="8" {{ $n1 == 8 ? 'selected' : '' }}>8</option>
                                                    </select>
                                                </div>
                                                <div class="flex flex-col items-center">
                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">N2</span>
                                                    <select name="scores[{{ $student->id }}][n2]" id="n2-{{ $student->id }}" {{ !$isAsasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} onchange="calculateMurni('{{ $student->id }}')" class="block w-14 rounded border-slate-300 text-xs font-semibold py-1 px-1.5 focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer text-left pl-3.5 pr-4">
                                                        <option value="0" {{ $n2 == 0 ? 'selected' : '' }}>0</option>
                                                        <option value="2" {{ $n2 == 2 ? 'selected' : '' }}>2</option>
                                                        <option value="4" {{ $n2 == 4 ? 'selected' : '' }}>4</option>
                                                        <option value="8" {{ $n2 == 8 ? 'selected' : '' }}>8</option>
                                                    </select>
                                                </div>
                                                <div class="flex flex-col items-center">
                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">N3</span>
                                                    <select name="scores[{{ $student->id }}][n3]" id="n3-{{ $student->id }}" {{ !$isAsasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} onchange="calculateMurni('{{ $student->id }}')" class="block w-14 rounded border-slate-300 text-xs font-semibold py-1 px-1.5 focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer text-left pl-3.5 pr-4">
                                                        <option value="0" {{ $n3 == 0 ? 'selected' : '' }}>0</option>
                                                        <option value="2" {{ $n3 == 2 ? 'selected' : '' }}>2</option>
                                                        <option value="4" {{ $n3 == 4 ? 'selected' : '' }}>4</option>
                                                        <option value="8" {{ $n3 == 8 ? 'selected' : '' }}>8</option>
                                                    </select>
                                                </div>
                                                <div class="flex flex-col items-center">
                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">N4</span>
                                                    <select name="scores[{{ $student->id }}][n4]" id="n4-{{ $student->id }}" {{ !$isAsasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} onchange="calculateMurni('{{ $student->id }}')" class="block w-14 rounded border-slate-300 text-xs font-semibold py-1 px-1.5 focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer text-left pl-3.5 pr-4">
                                                        <option value="0" {{ $n4 == 0 ? 'selected' : '' }}>0</option>
                                                        <option value="2" {{ $n4 == 2 ? 'selected' : '' }}>2</option>
                                                        <option value="4" {{ $n4 == 4 ? 'selected' : '' }}>4</option>
                                                        <option value="8" {{ $n4 == 8 ? 'selected' : '' }}>8</option>
                                                    </select>
                                                </div>
                                                <div class="flex flex-col items-center">
                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">N5</span>
                                                    <select name="scores[{{ $student->id }}][n5]" id="n5-{{ $student->id }}" {{ !$isAsasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} onchange="calculateMurni('{{ $student->id }}')" class="block w-14 rounded border-slate-300 text-xs font-semibold py-1 px-1.5 focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer text-left pl-3.5 pr-4">
                                                        <option value="0" {{ $n5 == 0 ? 'selected' : '' }}>0</option>
                                                        <option value="2" {{ $n5 == 2 ? 'selected' : '' }}>2</option>
                                                        <option value="4" {{ $n5 == 4 ? 'selected' : '' }}>4</option>
                                                        <option value="8" {{ $n5 == 8 ? 'selected' : '' }}>8</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3.5 border-r border-slate-200 bg-sky-100/50">
                                            <input type="number" id="murni-{{ $student->id }}" value="{{ $currentScore?->murni_asas_genap ?? 0 }}" readonly class="block w-16 mx-auto rounded-md border-transparent bg-slate-100 text-slate-700 text-center text-sm font-bold p-1.5 cursor-not-allowed">
                                        </td>
                                        <td class="px-3 py-3.5 border-r border-slate-200 bg-rose-50/20">
                                            <input type="number" name="scores[{{ $student->id }}][perbaikan]" id="perbaikan-{{ $student->id }}" value="{{ $currentScore?->perbaikan }}" {{ !$isAsasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} oninput="this.value = this.value.replace(/[^0-9]/g, ''); calculateAkhir('{{ $student->id }}')" onkeydown="handleEnter(event, 'perbaikan', {{ $loop->index }})" min="0" class="block w-16 mx-auto rounded-md border-slate-300 text-rose-600 shadow-sm focus:border-rose-500 focus:ring-rose-500 text-center text-sm font-bold p-1.5">
                                        </td>
                                    @else
                                        <td class="px-3 py-3.5 border-r border-slate-200 bg-sky-50/20">
                                            <input type="number" name="scores[{{ $student->id }}][murni_asas_genap]" id="murni-asas-{{ $student->id }}" value="{{ $currentScore?->murni_asas_genap }}" {{ !$isAsasOpen ? 'disabled bg-slate-100 cursor-not-allowed text-slate-400' : '' }} oninput="this.value = this.value.replace(/[^0-9]/g, ''); calculateAkhir('{{ $student->id }}')" onkeydown="handleEnter(event, 'murni-asas', {{ $loop->index }})" min="0" class="block w-16 mx-auto rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center text-sm font-semibold p-1.5">
                                        </td>
                                    @endif

                                    <!-- Status & Final Grade -->
                                    <td class="px-4 py-3.5 text-center border-r border-slate-200 bg-slate-50/50">
                                        <span id="status-{{ $student->id }}" class="text-xs font-bold px-2.5 py-1.5 rounded-full inline-block tracking-wide bg-slate-200 text-slate-700">-</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center bg-emerald-50/30">
                                        <span id="akhir-{{ $student->id }}" class="text-lg font-extrabold text-slate-800">0</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer Summary Bar inside Table Card -->
                <div class="bg-slate-50 p-6 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                    <div class="text-xs font-medium text-slate-500 space-y-1">
                        <p class="text-rose-600 font-semibold flex items-center">
                            <span class="mr-1.5">⚠️</span> * Tugas 1, 2, & ASTS Diambil Otomatis dari Admin
                        </p>
                        <p class="text-indigo-600">Untuk PG bisa ketik 'salah semua' or 'benar semua'.</p>
                        <p class="text-slate-500">Gunakan tombol 'Enter' untuk berpindah antar baris dengan cepat.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <button type="button" onclick="exportExcel()" class="w-full sm:w-auto inline-flex justify-center items-center py-3 px-6 border border-slate-300 rounded-lg shadow-sm text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            📥 Ekspor Excel
                        </button>
                        @if($isTugasOpen || $isAsasOpen)
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center py-3 px-8 border border-transparent rounded-lg shadow-md text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                Simpan Seluruh Nilai
                            </button>
                        @else
                            <button type="button" disabled class="w-full sm:w-auto inline-flex justify-center items-center py-3 px-8 border border-transparent rounded-lg shadow-md text-sm font-semibold text-slate-400 bg-slate-200 cursor-not-allowed">
                                Penyimpanan Dikunci
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endif
</div>

<script>
    const studentIds = [
        @foreach($students as $student)
            '{{ $student->id }}',
        @endforeach
    ];

    const isMath = {{ stripos($selectedSubject?->nama_mapel ?? '', 'matematika') !== false ? 'true' : 'false' }};
    const maxPG = isMath ? 25 : 30;
    const bobotPG = isMath ? 2.4 : 2;

    function handleEnter(event, prefix, index) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const nextId = studentIds[index + 1];
            if (nextId) {
                const nextEl = document.getElementById(prefix + '-' + nextId);
                if (nextEl) {
                    nextEl.focus();
                    if (typeof nextEl.select === 'function') nextEl.select();
                }
            }
        }
    }

    function calculateMurni(studentId) {
        const modeEl = document.getElementById('global-asas-mode');
        const mode = modeEl ? modeEl.value : 'FastTrack';

        const pgInputEl = document.getElementById('pg-' + studentId);
        if (!pgInputEl) return;
        const pgInputRaw = pgInputEl.value.trim();
        const pgInput = pgInputRaw.toLowerCase();
        
        let finalPG = 0;
        if (pgInput === 'benar semua') {
            finalPG = maxPG * bobotPG;
        } else if (pgInput === 'salah semua' || pgInput === '') {
            finalPG = 0;
        } else if (mode === 'FastTrack') {
            let countPG = parseInt(pgInput) || 0;
            if (countPG > maxPG) countPG = maxPG;
            if (countPG < 0) countPG = 0;
            finalPG = countPG * bobotPG;
        } else {
            const parts = pgInputRaw.split(',').filter(v => v.trim() !== '');
            let countPG = parts.length;
            if (countPG > maxPG) countPG = maxPG;

            if (mode === 'Benar') {
                finalPG = countPG * bobotPG;
            } else if (mode === 'Salah') {
                finalPG = (maxPG - countPG) * bobotPG;
            }
        }

        // Sum essay scores
        let finalES = 0;
        for (let i = 1; i <= 5; i++) {
            const selectEl = document.getElementById(`n${i}-` + studentId);
            if (selectEl) {
                finalES += parseInt(selectEl.value) || 0;
            }
        }
        if (finalES > 40) finalES = 40;

        let murniAsas = Math.round(finalPG + finalES);
        if (murniAsas > 100) murniAsas = 100;
        if (murniAsas < 0) murniAsas = 0;

        const murniEl = document.getElementById('murni-' + studentId);
        if (murniEl) {
            murniEl.value = murniAsas;
        }

        calculateAkhir(studentId);
    }

    function calculateAkhir(studentId) {
        const getVal = (id) => {
            const el = document.getElementById(id);
            return el ? parseFloat(el.value) || 0 : 0;
        };

        const t1 = getVal('t1-' + studentId);
        const t2 = getVal('t2-' + studentId);
        const asts = getVal('asts-' + studentId);
        const t4 = getVal('t4-' + studentId);
        const t5 = getVal('t5-' + studentId);

        // Determine effective ASAS
        let asas = 0;
        const isRegular = {{ $selectedSubject && $selectedSubject->kelompok_mapel === 'regular' ? 'true' : 'false' }};
        if (isRegular) {
            const perbaikanEl = document.getElementById('perbaikan-' + studentId);
            const perbaikanVal = perbaikanEl ? perbaikanEl.value.trim() : '';
            if (perbaikanVal !== '') {
                asas = parseFloat(perbaikanVal) || 0;
            } else {
                asas = getVal('murni-' + studentId);
            }
        } else {
            // Praktik uses NILAI ASAS directly
            asas = getVal('murni-asas-' + studentId);
        }

        const nilaiAkhir = Math.round((t1 + t2 + asts + t4 + t5 + asas) / 6);

        const akhirEl = document.getElementById('akhir-' + studentId);
        if (akhirEl) {
            akhirEl.innerText = nilaiAkhir;
        }

        const statusEl = document.getElementById('status-' + studentId);
        if (statusEl) {
            if (nilaiAkhir >= 75) {
                statusEl.innerText = 'TUNTAS';
                statusEl.className = 'text-xs font-bold px-2.5 py-1.5 rounded-full inline-block tracking-wide bg-emerald-100 text-emerald-800 border border-emerald-200';
            } else {
                statusEl.innerText = 'TIDAK TUNTAS';
                statusEl.className = 'text-xs font-bold px-2.5 py-1.5 rounded-full inline-block tracking-wide bg-rose-100 text-rose-800 border border-rose-200';
            }
        }
    }

    function recalculateAll() {
        const modeEl = document.getElementById('global-asas-mode');
        const mode = modeEl ? modeEl.value : 'FastTrack';
        
        const formModeEl = document.getElementById('form-mode');
        if (formModeEl) {
            formModeEl.value = mode;
        }

        studentIds.forEach(id => {
            calculateMurni(id);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        studentIds.forEach(id => {
            calculateAkhir(id);
        });
    });
</script>

<!-- SheetJS CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportExcel() {
        const table = document.querySelector('table');
        if (!table) {
            alert('Data tabel tidak ditemukan!');
            return;
        }

        const clone = table.cloneNode(true);
        const originalRows = table.querySelectorAll('tr');

        clone.querySelectorAll('tr').forEach((row, rowIndex) => {
            const originalRow = originalRows[rowIndex];
            if (!originalRow) return;
            const originalCells = originalRow.querySelectorAll('td, th');
            row.querySelectorAll('td, th').forEach((cell, cellIndex) => {
                const originalCell = originalCells[cellIndex];
                if (!originalCell) return;

                if (cell.tagName.toLowerCase() === 'td') {
                    const inputs = originalCell.querySelectorAll('input');
                    const selects = originalCell.querySelectorAll('select');
                    
                    if (inputs.length > 0) {
                        cell.textContent = Array.from(inputs).map(inp => inp.value).join(', ');
                    } else if (selects.length > 0) {
                        cell.textContent = Array.from(selects).map(sel => sel.value).join(' | ');
                    } else {
                        cell.textContent = originalCell.textContent.trim();
                    }
                }
            });
        });

        // Clean headers
        clone.querySelectorAll('th').forEach(th => {
            let text = th.innerText;
            text = text.replace(/[\u{1F300}-\u{1F9FF}]|[\u{2700}-\u{27BF}]|[\u{2600}-\u{26FF}]/gu, '');
            if (text.includes('\n')) {
                text = text.split('\n')[0];
            }
            th.innerText = text.trim();
        });

        // Add metadata headers
        const thead = clone.querySelector('thead');
        if (thead) {
            const className = "{{ $selectedSubject ? ($students->first()->classrooms->nama_kelas ?? '') : '' }}";
            const subjectName = "{{ $selectedSubject?->nama_mapel ?? '' }}";
            const maxCols = clone.querySelector('tr').querySelectorAll('th, td').length;

            const metaRows = [
                ['LAPORAN NILAI SISWA (GURU)', maxCols],
                ['Kelas: ' + className, maxCols],
                ['Mata Pelajaran: ' + subjectName, maxCols],
                ['Tanggal Ekspor: ' + new Date().toLocaleDateString('id-ID'), maxCols],
                ['', maxCols]
            ];

            metaRows.reverse().forEach(meta => {
                const tr = document.createElement('tr');
                const th = document.createElement('th');
                th.setAttribute('colspan', meta[1]);
                th.textContent = meta[0];
                th.style.textAlign = 'left';
                tr.appendChild(th);
                thead.insertBefore(tr, thead.firstChild);
            });
        }

        const wb = XLSX.utils.table_to_book(clone, { raw: true });
        
        const classNameClean = "{{ $selectedSubject ? ($students->first()->classrooms->nama_kelas ?? '') : '' }}";
        const subjectNameClean = "{{ $selectedSubject?->nama_mapel ?? '' }}";
        const filename = "Nilai_" + classNameClean.replace(/\s+/g, '_') + "_" + subjectNameClean.replace(/\s+/g, '_') + ".xlsx";
        
        XLSX.writeFile(wb, filename);
    }
</script>
@endsection
