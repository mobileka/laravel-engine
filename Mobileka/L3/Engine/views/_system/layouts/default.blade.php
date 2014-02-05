<!doctype html>
<html>
<head>
	<title>{{ $title }}</title>
	<meta charset="utf8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	{{ Asset::scripts() }}
	{{ Asset::styles() }}
	<script>
		$(document).ready(function() {
			$(document).bind('authresult', function(data) {
				console.warn($data);
			});

			Chocoaccount.server = "{{ Config::get('chocoauth::config.fullServerPath') }}/";
			Chocoaccount.check();
		});
	</script>
</head>

<body>
	<div class="row">
		<div class="span12">
			{{ notifications() }}
			{{ $content }}
		</div>
	</div>

	<footer id="footer">
		
	</footer>
</body>
</html>