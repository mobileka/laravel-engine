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

	<!-- Favicon -->
	<link rel="shortcut icon" href="img/favicon.ico" />
	<!-- Apple devices Homescreen icon -->
	<link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-precomposed.png" />

</head>

<body class="theme-darkblue" data-theme="theme-darkblue">
	<div id="navigation">
		<div class="container-fluid">
			<a href="#" class="toggle-nav" rel="tooltip" data-placement="bottom" title="Toggle navigation"><i class="icon-reorder"></i></a>
			<a href="{{ URL::to_route('users_admin_default_index') }}" id="brand">{{ Config::get('application.projectName') . ' | ' . $title }} | Панель администратора</a>

			<div class="user">
				<ul class="icon-nav">
					<li>
						<a href="{{ URL::to_action('/' ) }}" title="{{ ___('default', 'go_to_website') }}" rel="tooltip" data-placement="bottom"><i class="icon-share-alt"></i></a>
					</li>
				</ul>
				<div class="dropdown">
					<a href="#" class="dropdown-toggle dropdown-toggle-username" data-toggle="dropdown">
						{{ user()->name }}
					</a>

					<ul class="dropdown-menu pull-right">
						<li>
							{{ HTML::link_to_route('auth_admin_default_logout', "Выйти") }}
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" id="content">
		<div id="left">
			<form action="search-results.html" method="GET" class='search-form'>
				<div class="search-pane">
					<input type="text" name="search" placeholder="Search here...">
					<button type="submit"><i class="icon-search"></i></button>
				</div>
			</form>

			{{ View::make('engine::_system.layouts.inc.subnav', get_defined_vars() )->render() }}
		</div>
		<div id="main">
			<div class="container-fluid">
				<div class="page-header">
					<div class="pull-left">
						<h1>{{ $title }}</h1>
					</div>
					<div class="pull-right">
						<ul class="stats">
							<li class='lightred'>
								<i class="icon-calendar"></i>
								<div class="details">
									<span class="big">{{ date('d/m/Y') }}</span>
									<span>{{ date('l') }}</span>
								</div>
							</li>
						</ul>
					</div>
				</div>

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
