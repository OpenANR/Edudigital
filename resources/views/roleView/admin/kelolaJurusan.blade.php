@extends('layouts.customPage.sidebar')

@section('title', 'Kelola Jurusan | Edudigital')

@section('content')

<h1>Kelola Jurusan</h1>
<hr>
<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Jurusan</th>
            <th>Nama Jurusan</th>
        </tr>
    </thead>

    <tbody>
    @forelse ($departments as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kode_jurusan }}</td>
                <td>{{ $item->nama_jurusan }}</td>
            </tr>
    @empty
            <tr>
                <td>DATA KOSONG</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection