<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<!-- Apple devices fullscreen -->
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<!-- Apple devices fullscreen -->
	<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	<title>{{ Config::get('application.projectName') . ' | ' . $title }}</title>

	{{ Asset::container('plugins')->styles() }}

	@yield('styles')

	{{ Asset::container('custom')->styles() }}

	<script>
		var BASE = "{{ URL::base() }}";
			URL_KEEPER = {
				upload_url                         : '{{ URL::to_upload(Controller::$route) }}',
				upload_thumbnail                   : '{{ URL::to_route('uploads_admin_default_view') }}',
				properties_admin_values_destroy    : '{{ URL::to_route('properties_admin_values_destroy') }}',
				properties_admin_values_create     : '{{ URL::to_route('properties_admin_values_create') }}',
				properties_admin_values_step2_data : '{{ URL::to_route('properties_admin_values_step2_data') }}'
			};

		@yield('script_vars')
	</script>

	{{ Asset::container('plugins')->scripts() }}

	@yield('plugins')

	{{ Asset::container('custom')->scripts() }}

	@yield('scripts')

	<!-- Favicon -->
	<link rel="shortcut icon" href="img/favicon.ico" />
	<!-- Apple devices Homescreen icon -->
	<link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-precomposed.png" />

</head>

<body class="theme-darkblue" data-theme="theme-darkblue">
	<div class="container-fluid iframe" id="content">
		<div id="main">
			<div class="container-fluid">

				<div class="row-fluid">
					<div class="span12">

						{{ notifications() }}

						{{ $content }}

					</div>
				</div>

			</div>
		</div>
	</div>

</body>

</html>