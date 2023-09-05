<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="expires" content="Fri, 18 Jul 2014 1:00:00 GMT" />
	<meta content='yes' name='apple-mobile-web-app-capable'/>
	<meta content='yes' name='mobile-web-app-capable'/>

    <title>{{ config('app.name', 'Portal de la Fundación Conecta Mayor') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/frontendapp.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/ionicons.min.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/fakeLoader.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/swiper.min.css') }}">

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet" type="text/css">


	<link href="https://cdn.jsdelivr.net/npm/animate.css@3.5.1" rel="stylesheet" type="text/css">

</head>
<body>
    <!-- fakeloader -->
	<div class="fakeLoader"></div>
	<!-- end fakeloader -->
	
	<!-- navbar -->
	<div class="navbar navbar-home">
		<div class="title title-home"><img style="height:55px;" src="{{ asset('frontend/images/logo.jpg') }}" alt=""></div>
	</div>
	<!-- end navbar -->

	<!-- end sidebar -->
    <div id="app">
        @yield('content')
    </div>
    <script>
		window.appUrl = '{{ env('APP_URL') }}';

		window.App = {!! json_encode([
			'appTitle' => env('APP_TITLE')
		]) !!};
	</script>

	<!-- end toolbar bottom -->
    <script src="{{ asset('frontend/js/jquery-3.4.1.min.js') }}"></script>
	<script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('frontend/js/fakeLoader.js') }}"></script>
	<script src="{{ asset('frontend/js/swiper.min.js') }}"></script>
	<script src="{{ asset('frontend/js/jquery.big-slide.js') }}"></script>
	<script src="{{ asset('frontend/js/main.j') }}s"></script>
	<script src="https://cdn.plyr.io/3.6.4/plyr.js"></script>

</body>
</html>
