<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login V9</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />

    <link rel="stylesheet" type="text/css" href="user_login_form/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="user_login_form/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="user_login_form/material-design-iconic-font.min.css">

    <link rel="stylesheet" type="text/css" href="user_login_form/animate.css">

    <link rel="stylesheet" type="text/css" href="user_login_form/hamburgers.min.css">

    <link rel="stylesheet" type="text/css" href="user_login_form/animsition.min.css">

    <link rel="stylesheet" type="text/css" href="user_login_form/select2.min.css">

    <link rel="stylesheet" type="text/css" href="user_login_form/daterangepicker.css">

    <link rel="stylesheet" type="text/css" href="user_login_form/util.css">
    <link rel="stylesheet" type="text/css" href="user_login_form/main.css">

    <meta name="robots" content="noindex, follow">
</head>

<body>
    <div class="container-login100" style="background-image: url('user_login_form/bg-01.jpg');">

        <div class="wrap-login100 p-l-55 p-r-55 p-t-80 p-b-30">
           <h5 align="center"  style="padding-bottom: 20px">Sistem Informasi Rekomendasi Makanan</h5>
            <form class="login100-form validate-form" action="{{url('login_user_gizi')}}" method="POST">
                @csrf
               <p align="center" style="padding-bottom: 20px">Silahkan Masukkan Email & Password Yang Terdaftar</p>
                 @if($message = Session::get('error'))
                    <p align="center" style="color: red"><strong>{{$message}}</strong></p>
                @endif
                <div class="wrap-input100 validate-input m-b-20" data-validate="Masukkan Email">
                    <input class="input100" type="text" name="email" placeholder="Masukkan Email">
                    <span class="focus-input100"></span>
                </div>
                <div class="wrap-input100 validate-input m-b-25" data-validate="Masukkan Password">
                    <input class="input100" type="password" name="password" placeholder="password">
                    <span class="focus-input100"></span>
                </div>
                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" type="submit">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="dropDownSelect1"></div>

    <script src="user_login_form/jquery-3.2.1.min.js"></script>

    <script src="user_login_form/animsition.min.js"></script>

    <script src="user_login_form/popper.js"></script>
    <script src="user_login_form/bootstrap.min.js"></script>

    <script src="user_login_form/select2.min.js"></script>

    <script src="user_login_form/moment.min.js"></script>
    <script src="user_login_form/daterangepicker.js"></script>

    <script src="user_login_form/countdowntime.js"></script>

    <script src="user_login_form/main.js"></script>


</body>

</html>
