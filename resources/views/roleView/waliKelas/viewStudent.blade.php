@extends('layouts.customPage.sidebar')

@section('title', 'Lihat Siswa & Nilai | Edudigital')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="border-b border-slate-200 pb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Data Siswa & Nilai Akhir</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar nilai akhir siswa untuk seluruh mata pelajaran pada kelas yang Anda ampu.</p>
        </div>
        
        @if($classroom)
            <div class="inline-flex items-center space-x-2 bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-2">
                <span class="text-indigo-600 text-lg">🏫</span>
                <div>
                    <div class="text-xs text-indigo-500 font-bold uppercase tracking-wider">Kelas Aktif</div>
                    <div class="text-sm font-bold text-indigo-900">{{ $classroom->nama_kelas }}</div>
                </div>
            </div>
        @endif
    </div>

    <!-- Classroom Selector & Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Selector -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 lg:col-span-2 flex flex-col justify-center">
            @if($classrooms->count() > 1)
                <form action="{{ route('wali_kelas.viewStudent') }}" method="GET" class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700">Pilih Kelas yang Diampu</label>
                    <div class="flex gap-3">
                        <select name="classroom_id" class="block w-full rounded-lg border-slate-300 text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors">
                            @foreach($classrooms as $class)
                                <option value="{{ $class->id }}" {{ $classroomId == $class->id ? 'selected' : '' }}>
                                    {{ $class->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Pilih
                        </button>
                    </div>
                </form>
            @else
                <div>
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Kelas yang Diampu</h2>
                    <p class="text-xl font-bold text-slate-900 mt-1">
                        {{ $classroom ? $classroom->nama_kelas : 'Tidak ada kelas yang ditugaskan' }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Stats Card -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 p-6 rounded-xl shadow-md text-white flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Siswa Kelas Ini</p>
                <h3 class="text-3xl font-extrabold mt-1 tracking-tight">{{ $students->count() }}</h3>
                <p class="text-xs text-slate-400 mt-1">Siswa Terdaftar</p>
            </div>
            <div class="h-12 w-12 rounded-lg bg-white/10 flex items-center justify-center text-2xl">
                👥
            </div>
        </div>
    </div>

    <!-- Table Section -->
    @if($classroom && $students->isNotEmpty())
        <div class="space-y-4">
            <div class="flex justify-end">
                <button type="button" onclick="exportExcel()" class="inline-flex justify-center items-center py-2 px-5 border border-slate-300 rounded-lg shadow-sm text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    📥 Ekspor Excel
                </button>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Scrollable Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-700 uppercase font-bold text-xs tracking-wider border-b border-slate-200">
                            <th class="px-4 py-3.5 text-center border-r border-slate-200">NO</th>
                            <th class="px-6 py-3.5 text-left border-r border-slate-200 sticky left-0 bg-slate-50 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">NAMA SISWA / NISN</th>
                            @foreach($subjects as $subject)
                                <th class="px-4 py-3.5 text-center border-r border-slate-200 text-xs font-bold whitespace-normal min-w-[120px]" title="{{ $subject->nama_mapel }}">
                                    {{ $subject->nama_mapel }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-700">
                        @foreach($students as $student)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 text-center border-r border-slate-200 font-semibold text-slate-600">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 border-r border-slate-200 font-medium sticky left-0 bg-white hover:bg-slate-50 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                    <div class="font-bold text-slate-900">{{ $student->nama_siswa }}</div>
                                    <div class="text-xs text-slate-500 font-semibold font-mono mt-0.5">{{ $student->nisn }}</div>
                                </td>
                                @foreach($subjects as $subject)
                                    @php
                                        $score = $student->scores->firstWhere('subject_id', $subject->id);
                                        $nilaiAkhir = $score?->nilai_akhir;
                                        $isTuntas = $nilaiAkhir >= 75;
                                    @endphp
                                    <td class="px-4 py-4 text-center border-r border-slate-200">
                                        @if($nilaiAkhir !== null)
                                            <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-full {{ $isTuntas ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                                {{ $nilaiAkhir }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 font-medium">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer Legend -->
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex flex-wrap gap-4 text-xs font-medium text-slate-500">
                <span class="font-bold text-slate-700">Keterangan:</span>
                <span class="flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 border border-emerald-600"></span> Tuntas (Nilai &ge; 75)
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-rose-500 border border-rose-600"></span> Belum Tuntas (Nilai &lt; 75)
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="text-slate-400 font-bold">-</span> Belum ada nilai
                </span>
            </div>
        </div>
    </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
            <span class="text-4xl">📭</span>
            <h3 class="text-lg font-bold text-slate-800 mt-4">Data Siswa Kosong</h3>
            <p class="text-sm text-slate-500 mt-1">Tidak ditemukan siswa pada kelas yang Anda ampu.</p>
        </div>
    @endif
</div>

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

        // Clean headers
        clone.querySelectorAll('th').forEach(th => {
            let text = th.innerText;
            text = text.replace(/[\u{1F300}-\u{1F9FF}]|[\u{2700}-\u{27BF}]|[\u{2600}-\u{26FF}]/gu, '');
            if (text.includes('\n')) {
                text = text.split('\n')[0];
            }
            th.innerText = text.trim();
        });

        // Clean data cells
        clone.querySelectorAll('td').forEach(td => {
            td.textContent = td.textContent.trim();
        });

        // Add metadata headers
        const thead = clone.querySelector('thead');
        if (thead) {
            const className = "{{ $classroom ? $classroom->nama_kelas : '' }}";
            const maxCols = clone.querySelector('tr').querySelectorAll('th, td').length;

            const metaRows = [
                ['REKAP NILAI SISWA (WALI KELAS)', maxCols],
                ['Kelas: ' + className, maxCols],
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
        
        const classNameClean = "{{ $classroom ? $classroom->nama_kelas : '' }}";
        const filename = "Rekap_Nilai_" + classNameClean.replace(/\s+/g, '_') + ".xlsx";
        
        XLSX.writeFile(wb, filename);
    }
</script>
@endsection