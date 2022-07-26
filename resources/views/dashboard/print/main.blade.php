<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>

<body>
    <header class="text-center p-4">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" width="120" style="position: absolute; left: 20px;">
        <h4>BALAI PENGKAJIAN TEKNOLOGI PERTANIAN</h4>
        <h4>KALIMANTAN SELATAN</h4>
        Jl. Panglima Batur No.4, Loktabat Utara, Kec. Banjarbaru Utara, Kota Banjar Baru, Kalimantan Selatan 70711
        <br>
        Telepon: (0511) 4772346, Website: http://kalsel.litbang.pertanian.go.id/, Email:
        bptp-kalsel@litbang.pertanian.go.id
    </header>
    <div class="d-flex flex-column justify-content-center w-100">
        <div style="width: 100%; border-top: 3px solid black;"></div>
    </div>
    @yield('content')
    <footer></footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
    </script>
    <script>
        window.print();
    </script>
</body>

</html>
