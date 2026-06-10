@extends('layouts.customPage.sidebar')

@section('title', 'Kelola Siswa | Edudigital')

@section('content')

<h1>Kelola Siswa</h1>
<hr>
<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>NISN</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Jurusan</th>
        </tr>
    </thead>

    <tbody>
    @forelse ($student as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nisn }}</td>
                <td>{{ $item->nama_siswa }}</td>
                <td>{{ $item->classrooms?->nama_kelas }}</td>
                <td>{{ $item->departments->nama_jurusan }}</td>
            </tr>
    @empty
            <tr>
                <td>DATA KOSONG</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection