@extends('layouts.customPage.sidebar')

@section('title', 'Dashboard Guru | Edudigital')

@section('content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard Guru</h1>
        <p class="text-sm text-slate-500 mt-1">Selamat Datang, {{ Auth::user()->name }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <h2 class="text-lg font-bold text-slate-800 mb-4">Informasi Akun</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-600">
            <div>
                <span class="font-semibold text-slate-700">Nama Lengkap:</span> {{ Auth::user()->name }}
            </div>
            <div>
                <span class="font-semibold text-slate-700">Kode Guru:</span> {{ Auth::user()->kode_guru }}
            </div>
            <div>
                <span class="font-semibold text-slate-700">Tanggal Lahir:</span> {{ \Carbon\Carbon::parse(Auth::user()->tanggal_lahir)->format('d M Y') }}
            </div>
            <div>
                <span class="font-semibold text-slate-700">Hak Akses:</span> Guru / Pengajar
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
                        <h2 class="text-lg font-bold text-slate-900">Statistik Input Nilai Seluruh Kelas</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Persentase kelengkapan pengisian nilai di sekolah. Kelas Anda ditandai lebih tebal.</p>
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
                <div class="mb-4">
                    <h2 class="text-lg font-bold text-slate-900">Kelas yang Anda Ampu</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Kemajuan input nilai kelas yang Anda ampu.</p>
                    <div class="mt-2 text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-lg py-1 px-2.5 inline-block">
                        {{ $teacherCompletedClassesCount }} / {{ $teacherClassesCount }} Selesai
                    </div>
                </div>
                
                <div class="space-y-3 max-h-[320px] overflow-y-auto pr-1">
                    @php $hasTeacherClass = false; @endphp
                    @foreach($classStats as $stat)
                        @if($stat['is_teacher_class'])
                            @php $hasTeacherClass = true; @endphp
                            <div class="flex items-center justify-between p-3 rounded-lg border bg-indigo-50/30 border-indigo-100">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs font-bold text-slate-800 truncate">{{ $stat['name'] }}</span>
                                        @if($stat['is_complete'])
                                            <span class="text-emerald-600 text-xs font-bold">✅</span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-medium mt-0.5">
                                        {{ $stat['actual_scores'] }} / {{ $stat['expected_scores'] }} Nilai Terisi
                                    </div>
                                </div>
                                <div class="text-right ml-3 flex flex-col items-end">
                                    <span class="text-xs font-extrabold text-indigo-700">
                                        {{ $stat['percentage'] }}%
                                    </span>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if(!$hasTeacherClass)
                        <div class="p-8 text-center text-slate-400 text-xs font-medium border border-dashed border-slate-200 rounded-lg bg-slate-50/50">
                            📭 Anda belum ditugaskan untuk mengampu kelas manapun.
                        </div>
                    @endif
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

        const isTeacherClass = [
            @foreach($classStats as $stat)
                {{ $stat['is_teacher_class'] ? 'true' : 'false' }},
            @endforeach
        ];

        const backgroundColors = percentages.map((percent, idx) => {
            if (isTeacherClass[idx]) {
                return percent >= 100 ? 'rgba(16, 185, 129, 0.95)' : 'rgba(99, 102, 241, 0.95)';
            }
            return percent >= 100 ? 'rgba(16, 185, 129, 0.35)' : 'rgba(148, 163, 184, 0.35)';
        });

        const borderColors = percentages.map((percent, idx) => {
            if (isTeacherClass[idx]) {
                return percent >= 100 ? 'rgb(16, 185, 129)' : 'rgb(99, 102, 241)';
            }
            return percent >= 100 ? 'rgba(16, 185, 129, 0.4)' : 'rgba(148, 163, 184, 0.4)';
        });

        const borderWidths = percentages.map((percent, idx) => {
            return isTeacherClass[idx] ? 2.5 : 1;
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
                    borderWidth: borderWidths,
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
                                const index = context.dataIndex;
                                const isYour = isTeacherClass[index] ? ' (Kelas Anda)' : '';
                                return ` Kelengkapan: ${context.raw}%${isYour}`;
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