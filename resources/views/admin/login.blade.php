<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{ url('assets/images/RKSP_favicon.jpg') }}">
    <link rel="stylesheet" href="{{url("assets/admin/css/compiled.min.css")}}">
    <style>
    body { width: 100wh; height: 90vh;  background: linear-gradient(-45deg, #EE7752, #E73C7E, #23A6D5, #23D5AB);
    background-size: 400% 400%; -webkit-animation: Gradient 15s ease infinite; -moz-animation: Gradient 15s ease infinite; animation:
    Gradient 15s ease infinite; } @-webkit-keyframes Gradient { 0% { background-position: 0% 50% } 50% { background-position:
    100% 50% } 100% { background-position: 0% 50% } } @-moz-keyframes Gradient { 0% { background-position: 0% 50% } 50% { background-position:
    100% 50% } 100% { background-position: 0% 50% } } @keyframes Gradient { 0% { background-position: 0% 50% } 50% { background-position:
    100% 50% } 100% { background-position: 0% 50% } }
    
    </style>
    <title>Admin Login</title>
</head>
<body>
    
<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-12">
            <br><br>
            <div class="col-md-4 offset-md-4 ">

                <section class="form-elegant">

                    <!--Form without header-->
                    <div class="card">
                        <div class="card card-cascade">
                            <div class="view view-cascade gradient-card-header blue-gradient" style="padding: .5rem 1rem;">

                                <!-- Title -->
                                <h2 class="card-header-title mb-1" style="font-weight: 300 !important;">ADMIN LOGIN</h2>
                                <!-- Subtitle -->


                            </div>
                        </div>
                        <div class="card-body mx-4">

                            <!--Header-->



                            <form method="POST" action="{{route('admin.login')}}">
                                @csrf
                                <!--Body-->
                                <div class="md-form">
                                   <i class="fa fa-envelope prefix grey-text"></i>
                                    <input id="email" type="email" class="form-control form-control" name="email" value="{{ old('email') }}" required="" placeholder="Email" autofocus="">
                                @if ($errors->has('email'))

                                <span class="invalid-feedback">
                                 <strong>{{ $errors->first('email') }}</strong>
                                  </span>
                                    @endif
                                   
                                </div>

                                <div class="md-form pb-3">
                                    <i class="fa fa-lock prefix grey-text"></i>
                                    <input id="password" type="password" class="form-control form-control" name="password" required="" placeholder="Password">
                                 @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                     <strong>{{ $errors->first('password') }}</strong>
                                </span> 
                                @endif
                                </div>

                                <div class="text-center mb-3">
                                    <button type="submit" class="btn blue-gradient btn-block btn-rounded z-depth-1a waves-effect waves-light">Login</button>
                                </div>
                                

                            </form>
                        </div>

                    </div>
                    <!--/Form without header-->

                </section>

            </div>

        </div>
    </div>
</div>


</body>
</html>