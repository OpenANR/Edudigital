@extends('layouts.customPage.sidebar')

@section('title', 'Dashboard | Edudigital')

@section('content')
<h1>Selamat Datang, {{ Auth::user()->name }}</h1>
<hr>
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">LOGOUT</button>
</form>
@endsection