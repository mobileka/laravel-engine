<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<!-- Apple devices fullscreen -->
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<!-- Apple devices fullscreen -->
	<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />

	<title>Михалыч | Войти в панель администратора</title>

	{{ HTML::style('backoffice/css/bootstrap.min.css') }}
	{{ HTML::style('backoffice/css/bootstrap-responsive.min.css') }}
	{{ HTML::style('backoffice/css/plugins/icheck/all.css') }}
	{{ HTML::style('backoffice/css/style.css') }}
	{{ HTML::style('backoffice/css/themes.css') }}

	{{ HTML::script('backoffice/js/jquery.min.js') }}
	{{ HTML::script('backoffice/js/plugins/nicescroll/jquery.nicescroll.min.js') }}
	{{ HTML::script('backoffice/js/plugins/validation/jquery.validate.min.js') }}
	{{ HTML::script('backoffice/js/plugins/validation/additional-methods.min.js') }}
	{{ HTML::script('backoffice/js/plugins/icheck/jquery.icheck.min.js') }}
	{{ HTML::script('backoffice/js/bootstrap.min.js') }}
	{{ HTML::script('backoffice/js/eakroko.js') }}


	{{ Asset::container('header')->styles() }}
	{{ Asset::container('header')->scripts() }}

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body class="login theme-darkblue">
	<div class="wrapper">
		<h1><a href="#"><img src="{{ URL::base() }}/backoffice/img/logo-big.png" alt="" class='retina-ready' width="59" height="49">Михалыч</a></h1>
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