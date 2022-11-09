<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<html lang="en">

    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title> {{ strip_tags(config('app.name')) }} | Login</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="{{ strip_tags(config('app.desc')) }}" name="description" />
        <meta content="{{ strip_tags(config('app.author')) }}" name="author" />

        <!-- BEGIN GLOBAL MANDATORY STYLES -->

        <link href="{{ assets('global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ assets('global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ assets('global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ assets('global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->

        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{ assets('global/css/components.css') }}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ assets('global/css/plugins.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->

        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="{{ assets('layouts/layout/css/login/login-'.config('app.color').'.css') }}" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->

        <link rel="shortcut icon" href="favicon.ico" />
        <style>


    * { margin: 0; padding: 0; }

    body {
      background: url("{{ url('/') }}/application/public/BGREV.jpg") no-repeat center center fixed;
      background-position: center;
      background-size: contain;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
    }

    #page-wrap { width: 400px; margin: 50px auto; padding: 20px; background: white; -moz-box-shadow: 0 0 20px black; -webkit-box-shadow: 0 0 20px black; box-shadow: 0 0 20px black; }
    p { font: 15px/2 Georgia, Serif; margin: 0 0 30px 0; text-indent: 40px; }

        </style>
    </head>
    <!-- END HEAD -->

    <body class=" login">

        <!-- BEGIN LOGIN -->
        <div class="content">

            <!-- BEGIN LOGIN FORM -->
            {!! Form::open(['url' => 'login', 'class' => 'login-form']) !!}

                <h3 class="form-title">LOGIN</h3>

                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span> Username atau password tidak boleh kosong. </span>
                </div>

                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('username') ? 'has-error' : '' }}">
                    {!! Form::text('username', null, ['class' => 'form-control', 'id' => 'username']) !!}
                    <label for="username">Username</label>
                    <span class="help-block">{{ $errors->has('username') ? $errors->first('username') : 'Masukkan Username' }}</span>
                </div>

                <div class="form-group form-md-line-input form-md-floating-label">
                    {!! Form::password('password', ['class' => 'form-control', 'id' => 'password']) !!}
                    <label for="password">Password</label>
                    <span class="help-block">{{ $errors->has('password') ? $errors->first('password') : 'Masukkan Password' }}</span>
                </div>

                {{-- <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('captcha') ? 'has-error' : ''}}">
                            {!! Form::text('captcha', null, ['class' => 'form-control', 'id' => 'captcha']) !!}
                            <label for="captcha">Captcha</label>
                            <span class="help-block">{{ $errors->has('captcha') ? $errors->first('captcha') : 'Masukkan Captcha' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 text-center" style="margin-top: -5px; margin-bottom: 20px;">
                        <a href="javascript:void(0)" id="captchaImg" title="Klik untuk refresh captcha" class="tooltips">{!! captcha_img() !!}</a>
                    </div>
                </div> --}}

                <div class="row">
                    <div class="col-md-6">
                        <label class="rememberme check">
                            <div class="md-checkbox">
                            <input type="checkbox" id="cb1" name="remember" class="md-check" value="1" {{!old('remember') && $errors->has('username') ? '' : 'checked'}}>
                                <label for="cb1">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Remember Me
                                </label>
                            </div>
                        </label>
                    </label>
                    </div>
                    {{-- <div class="col-md-6">
                        <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
                    </div> --}}
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn uppercase">Login</button>
                </div>

            {!! Form::close() !!}
            <!-- END LOGIN FORM -->

            <!-- BEGIN FORGOT PASSWORD FORM -->
            {{-- <form class="forget-form" action="index.html" method="post">
                <h3 class="font-green">Forget Password ?</h3>
                <p> Enter your e-mail address below to reset your password. </p>

                <div class="form-group">
                    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" />
                </div>

                <div class="form-actions">
                    <button type="button" id="btn" class="btn green btn-outline"></button>
                    <button type="submit" class="btn btn-success uppercase pull-right">Submit</button>
                </div>
            </form> --}}
            <!-- END FORGOT PASSWORD FORM -->

        </div>
        <div class="copyright"> 2019{{ date('Y') > 2019 ? ' - ' . date('Y') : '' }} © {{ config('app.name') }}. </div>

        @if(preg_match('/MSIE\s(?P<v>\d+)/i', @$_SERVER['HTTP_USER_AGENT'], $B) && $B['v'] <= 9)
        <script src="{{ assets('global/plugins/respond.min.js') }}"></script>
        <script src="{{ assets('global/plugins/excanvas.min.js') }}"></script>
        <script src="{{ assets('global/plugins/ie8.fix.min.js') }}"></script>
        @endif

        <!-- BEGIN CORE PLUGINS -->
        <script src="{{ assets('global/plugins/jquery.min.js') }}" type="text/javascript"></script>
        <script src="{{ assets('global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ assets('global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
        <script src="{{ assets('global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
        <script src="{{ assets('global/plugins/jquery-nicescroll/jquery.nicescroll.js') }}" type="text/javascript"></script>
        <script src="{{ assets('global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
        <script src="{{ assets('global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->

        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{ assets('global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->

        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{ assets('global/scripts/app.js') }}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->

        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{ assets('pages/scripts/login.js') }}" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
    </body>

</html>
