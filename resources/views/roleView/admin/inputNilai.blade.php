@extends('layouts.customPage.sidebar')

@section('title', 'Input Nilai | Edudigital')

@section('content')
<h1>Kelola Input Nilai</h1>
<hr>

@if(session('success'))
    <div style="color: green; padding: 10px; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 15px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="color: red; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 15px;">
        {{ session('error') }}
    </div>
@endif

<form action="{{ route('admin.manageScore') }}" method="GET" class="mb-6">
    <select name="classroom_id" onchange="this.form.submit()">
        <option value="">--- PILIH KELAS ---</option>
        @foreach($classrooms as $class)
            <option value="{{ $class->id }}" {{ $classroomId == $class->id ? 'selected' : '' }}>
                {{ $class->nama_kelas }}
            </option>
        @endforeach
    </select>

    <select name="subject_id" onchange="this.form.submit()">
        <option value="">--- PILIH MAPEL ---</option>
        @foreach($subjects as $subject)
            <option value="{{ $subject->id }}" {{ $subjectId == $subject->id ? 'selected' : '' }}>
                {{ $subject->nama_mapel }}
            </option>
        @endforeach
    </select>
</form>

@if($students->isNotEmpty())
<form action="{{ route('admin.saveScore') }}" method="POST">
    @csrf
    <input type="hidden" name="subject_id" value="{{ $subjectId }}">

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NISN</th>
                <th>NAMA SISWA</th>
                <th>TUGAS 1</th>
                <th>TUGAS 2</th>
                <th>ASTS</th>
                <th>TUGAS 4</th>
                <th>TUGAS 5</th>
                <th>ASAS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                @php
                    $currentScore = $student->scores->first();
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student->nisn }}</td>
                    <td>{{ $student->nama_siswa }}</td>

                    <td><input type="number" name="scores[{{ $student->id }}][tugas1]" value="{{ $currentScore?->tugas1 }}"></td>
                    <td><input type="number" name="scores[{{ $student->id }}][tugas2]" value="{{ $currentScore?->tugas2 }}"></td>
                    <td><input type="number" name="scores[{{ $student->id }}][asts]" value="{{ $currentScore?->asts }}"></td>
                    <td><input type="number" name="scores[{{ $student->id }}][tugas4]" value="{{ $currentScore?->tugas4 }}"></td>
                    <td><input type="number" name="scores[{{ $student->id }}][tugas5]" value="{{ $currentScore?->tugas5 }}"></td>
                    <td><input type="number" name="scores[{{ $student->id }}][asas]" value="{{ $currentScore?->asas }}"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button type="submit">Simpan Semua Nilai</button>
</form>
@endif
@endsection