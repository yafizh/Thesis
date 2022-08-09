<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css"
        href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Login</title>
</head>

<body>
    <section class="material-half-bg">
        <div class="cover"></div>
    </section>
    <section class="login-content">
        <div class="login-box">
            <form class="login-form" action="/login" method="POST">
                @csrf
                <div class="d-flex justify-content-center pb-3">
                    <img src="/images/logo.png" width="100" alt="">
                </div>
                <h3 class="login-head">BALAI PENGKAJIAN TEKNOLOGI PERTANIAN KALIMANTAN SELATAN</h3>
                @if (session()->has('auth'))
                    <div class="alert alert-dismissible alert-danger">
                        <button class="close" type="button" data-dismiss="alert">×</button>
                        NIP atau Password Salah!
                    </div>
                @endif
                <div class="form-group">
                    <label class="control-label">NIP</label>
                    <input class="form-control" type="text" placeholder="NIP" name="username" autofocus
                        autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label class="control-label">PASSWORD</label>
                    <input class="form-control" type="password" name="password" placeholder="Password"
                        autocomplete="off" required>
                </div>
                <div class="form-group btn-container">
                    <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
                </div>
            </form>
        </div>
    </section>
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
</body>

</html>
