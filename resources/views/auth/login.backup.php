<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
</head>
<body style="height: 100vh">

    <div class="row d-flex h-100" >
        <div class="col-md-7" style="background-image: linear-gradient(180deg,  rgba(143, 143, 143, 0.378) 0 40%,rgba(255, 218, 117, 0.449) 60% 100%), url('https://images.unsplash.com/photo-1555243896-c709bfa0b564?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80'); background-size: cover; background-repeat: no-repeat">
            {{-- <h3>Perbaiki Gizi</h3> --}}
        </div>
        <div class="col-md-4 mx-auto my-auto">
            <h3>SignIn</h3>
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" id="email" class="form-control form-control-sm">
                        <label for="">Password</label>
                        <input type="password" id="password" class="form-control form-control-sm">

                        {{-- <div class="text-right"> --}}

                            <button class="btn btn-sm btn-primary mt-3 " id="btn-login" style="width: 100% ">
                                Login
                            </button>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"  crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>


    <script>
        $("#btn-login").click(function(){
            let email = $("#email").val()
            let password = $("#password").val()

            $.ajax({
                url: "/api/login",
                type: "POST",
                data: {
                    email: email,
                    password: password
                },
                success: function(res){
                   console.log(res);
                   Cookies.set('admin_cookie', res.data.token)
                    setTimeout(() => {
                            location.href = '/'
                        }, 2000);
                }
            })
        })
    </script>
</body>
</html>
