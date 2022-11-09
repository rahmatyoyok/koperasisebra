<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>401 Unauthorized</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{assets('global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{assets('global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{assets('global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{assets('global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />

        <link href="{{assets('global/css/components-md.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{assets('global/css/plugins-md.css')}}" rel="stylesheet" type="text/css" />

        <link href="{{assets('pages/css/error.css')}}" rel="stylesheet" type="text/css" />

        <link rel="shortcut icon" href="favicon.ico" />
    </head>

    <body class=" page-500-full-page">
        <div class="row">
            <div class="col-md-12 page-500">
                <div class=" number font-red"> 401 </div>
                <div class=" details">
                    <h3>Oops! We can't find the page you looking for.</h3>
                    <p>
                        <a href="{{ url('') }}" class="btn red btn-outline"> Return home </a>
                        <br>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>