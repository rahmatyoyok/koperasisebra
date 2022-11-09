<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<html lang="en">

    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title> {{ strip_tags(config('app.name')) }} | Locked</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="{{ strip_tags(config('app.desc')) }}" name="description" />
        <meta content="{{ strip_tags(config('app.author')) }}" name="author" />

        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
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
        <link href="{{ assets('layouts/layout/css/lock/lock.css') }}" rel="stylesheet" type="text/css" />
        {{-- <link href="{{ assets('layouts/layout/css/lock/lock-'.config('app.color').'.css') }}" rel="stylesheet" type="text/css" /> --}}
        <!-- END PAGE LEVEL STYLES -->

        <link rel="shortcut icon" href="favicon.ico" />
    </head>

    <body>
        <div class="page-lock">
            <div class="logo">
                <a href="{{url('/')}}">
                    {{-- <img src="{{ assets('pages/img/logo-big.png') }}" alt="" /> --}}
                </a>
            </div>

            <div class="page-body">
                <div class="lock-head"> LOCKED </div>
                <div class="lock-body">
                    <div class="pull-left lock-avatar-block">
                        <img src="{{Avatar::create($user->name)->toBase64()}}" class="lock-avatar">
                    </div>
                    {!! Form::open(['url' => 'unlock', 'class' => 'lock-form pull-left']) !!}
                        <h4>{{$user->name}}</h4>
                        <div class="form-group">
                            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" />
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn red uppercase">Unlock</button>
                        </div>
                    {!! Form::close() !!}
                </div>
                <div class="lock-bottom">
                    <a href="logout">Login dengan akun lain?</a>
                </div>
            </div>
        </div>

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

        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{ assets('global/scripts/app.js') }}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->

        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{ assets('pages/scripts/lock.js') }}" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
    </body>

</html>