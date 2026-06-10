<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Edudigital')</title>
</head>
<body>
    <header>
        <div><h1>Edudigital</h1></div>
        <nav>
            <ul>
                <li><a href="#">Beranda</a></li>
                <li><a href="#">Kelola Siswa</a></li>
                <li><a href="#">Input Nilai</a></li>
                <li><a href="#">Kelola Kelas</a></li>
            </ul>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        <hr>
        <center><h3>Footer</h3></center>
    </footer>
</body>
</html>