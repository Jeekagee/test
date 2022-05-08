<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>DS Office</title>

  <!-- Font Awesome Icons -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">	
		<link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>

  <body>
  <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                              <a class="nav-link" href="{{ url('/') }}"> {{ __('Logout') }} </a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </div>
		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
				<div class="custom-menu"> </div>
        <ul class="list-unstyled">
          <li class="active">
            <a href="#"><span class="fa fa-home"></span> Homepage</a>
          </li>
          <li class="active">
            <a href="history"><span class="fa fa-search"></span> Customer History</a>
          </li>
        </ul>
    	</nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5 pt-5">
      <div class='container'>
            @yield('content')
          </div>
      </div>
		</div>
  </body>
</html>