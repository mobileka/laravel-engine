<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<!-- Apple devices fullscreen -->
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<!-- Apple devices fullscreen -->
	<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />

	<title>{{ configValue('application.project_name', 'application.project_name') }} | Войти в панель администратора</title>
	{{ csrf_meta_tag() }}

  {{ Asset::container('engine_assets')->styles() }}
  @yield('styles')
  <script>
    var BASE = "{{ URL::base() }}";
    URL_KEEPER = {
      upload_url                         : '{{ URL::to_upload(Controller::$route) }}'
    };
    var app = {
      URL_BASE: '{{ URL::base() }}',
      jcropParams: {}
    };

    @yield('script_vars')
  </script>
  {{ Asset::container('engine_assets')->scripts() }}
  @yield('plugins')
  @yield('scripts')

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body class="login theme-darkblue">
	<div class="wrapper">
		<h1><a href="#"><img src="{{ URL::base() }}/bundles/engine/admin/img/logo-big.png" alt="" class='retina-ready' width="59" height="49">{{ configValue('application.project_name', 'application.project_name') }}</a></h1>
		<div class="login-body">
			<h2>Войти</h2>

			{{ $content }}

			<div class="forget">
				<a href="#"><span>Забыли пароль?</span></a>
			</div>
		</div>
	</div>

</body>
</html>
