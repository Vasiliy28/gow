<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gow</title>

    <!-- Bootstrap -->
    <link href="{{asset('/bower_components/bootstrap/dist/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('/css/parser.css')}}" rel="stylesheet">
    <link href="{{asset('/bower_components/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('local_css')
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Parser Gow</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right hidden-lg hidden-md">

                @foreach(config('urls') as $url)
                    <li class=" {{Request::is($url['url']) ? "active" : ""}} ">
                        <a href="{{$url['url']}}">
                            {{$url['name']}}
                        </a>
                    </li>
                @endforeach
                fore

            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <h1 class="page-header">Url`s</h1>
            <ul class="nav nav-sidebar">
                @foreach(config('urls') as $url)
                    <li class=" {{Request::is($url['url']) ? "active" : ""}} "><a
                                href="{{$url['url']}}">{{$url['name']}}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            {!!FlashHelper::renderAll()  !!}
            @yield('content')
        </div>
    </div>
</div>


@yield('local_js')
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="{{asset('/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{asset('/bower_components/bootstrap/dist/js/bootstrap.js')}}"></script>
</body>
</html>
