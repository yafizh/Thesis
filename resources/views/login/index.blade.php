<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous" />

    <!-- Custom styles for this template -->
    <link href="/styles/signin.css" rel="stylesheet" />
</head>

<body class="text-center">
    <main class="form-signin bg-white rounded shadow px-4 py-5">
        <form action="/login" method="POST">
            @csrf
            <img class="mb-4" src="/images/logo.png" alt="" width="72" />
            <h1 class="h3 mb-3 fw-normal">Login</h1>

            <div class="form-floating">
                <input type="text" class="form-control" id="username" name="username" placeholder="NIP" autocomplete="off" required />
                <label for="username">NIP</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off"
                    required />
                <label for="password">Password</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Masuk</button>
        </form>
    </main>
</body>

</html>
