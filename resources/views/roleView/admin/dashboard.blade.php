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
        {{-- <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition">
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
        </div> --}}

        <!-- Card 2: Kelola Siswa -->
        {{-- <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition">
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
        </div> --}}

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

    <!-- Statistics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 lg:col-span-2 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Statistik Input Nilai Kelas</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Persentase kelengkapan pengisian nilai untuk setiap kelas.</p>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $totalCompletedClasses }} / {{ $totalClasses }} Kelas Selesai
                    </span>
                </div>
                <!-- Canvas Container -->
                <div class="relative w-full h-[380px]">
                    <canvas id="completionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Completion List Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-1">Daftar Status Kelas</h2>
                <p class="text-xs text-slate-500 mb-4">Detail kemajuan input data per kelas.</p>
                
                <div class="space-y-3 max-h-[340px] overflow-y-auto pr-1">
                    @foreach($classStats as $stat)
                        <div class="flex items-center justify-between p-2.5 rounded-lg border {{ $stat['is_complete'] ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50/50 border-slate-100' }}">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-bold text-slate-700 truncate">{{ $stat['name'] }}</span>
                                    @if($stat['is_complete'])
                                        <span class="text-emerald-600 text-xs font-bold">✅</span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-slate-400 font-medium mt-0.5">
                                    {{ $stat['actual_scores'] }} / {{ $stat['expected_scores'] }} Nilai Terisi
                                </div>
                            </div>
                            <div class="text-right ml-3 flex flex-col items-end">
                                <span class="text-xs font-extrabold {{ $stat['is_complete'] ? 'text-emerald-700' : 'text-indigo-600' }}">
                                    {{ $stat['percentage'] }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('completionChart').getContext('2d');
        
        const classNames = [
            @foreach($classStats as $stat)
                '{{ $stat['name'] }}',
            @endforeach
        ];
        
        const percentages = [
            @foreach($classStats as $stat)
                {{ $stat['percentage'] }},
            @endforeach
        ];

        const backgroundColors = percentages.map(percent => {
            return percent >= 100 ? 'rgba(16, 185, 129, 0.85)' : 'rgba(99, 102, 241, 0.85)';
        });

        const borderColors = percentages.map(percent => {
            return percent >= 100 ? 'rgb(16, 185, 129)' : 'rgb(99, 102, 241)';
        });

        const config = {
            type: 'bar',
            data: {
                labels: classNames,
                datasets: [{
                    label: 'Persentase Selesai (%)',
                    data: percentages,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1.5,
                    borderRadius: 6,
                    barThickness: 14
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` Kelengkapan: ${context.raw}%`;
                            }
                        },
                        backgroundColor: '#0f172a',
                        titleFont: { size: 11, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        min: 0,
                        max: 100,
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            },
                            color: '#64748b',
                            font: {
                                size: 10,
                                weight: '500'
                            }
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#475569',
                            font: {
                                size: 11,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        };

        new Chart(ctx, config);
    });
</script>
@endsection