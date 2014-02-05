<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Mikhalych</title>
<meta name="description" content="">

{{ Asset::container('plugins')->styles() }}
{{ Asset::container('custom')->styles() }}
{{ Asset::container('header_plugins')->scripts() }}
<script>
	mikh.URL_BASE = "{{ URL::base() }}";
	mikh.URL_KEEPER.cart_default_create = "{{ URL::to_route('carts_default_create') }}";
	mikh.URL_KEEPER.cart_default_destroy = "{{ URL::to_route('carts_default_destroy') }}";
	@yield('custom_vars')
</script>
{{ Asset::container('header_custom')->scripts() }}
</head>
<body>

<div id="skrollr-body">

<div class="top-bar">

	<div class="container">
		<div class="row">
			<div class="span9">
				<div class="top-bar-text">
					Интернет-магазин товаров для авто в Казахстане. Позвоните: <strong>8 (727) 234 56 78</strong> или <strong>8 701 234 56 78</strong>
				</div>
			</div> <!-- .span9 -->

			<div class="span3">
				<div class="auth-block">
					@if (!uid())
						{{ HTML::link_to_route('auth_default_login', 'Войти') }}
						{{ HTML::link_to_route('auth_default_register', 'Регистрация') }}
					@else
						{{ userName() }}
						{{ HTML::link_to_action('admin/logout', 'Выйти') }}
					@endif
				</div> <!-- .auth-block -->
			</div> <!-- .span3 -->
		</div> <!-- .row -->
	</div>

</div> <!-- .top-bar -->

<div class="container">

	<div class="row">
		<div class="span3">
			<h1 class="mikhalych-logo-main">{{ HTML::link_to_action('/', 'Mikhalych') }}</h1>
			<small class="site-title">Автомобилю должно быть комфортно!</small>
		</div> <!-- .span3 -->

		<div class="span6">
			{{ Form::open(URL::to_route('models_default_search'), 'GET') }}
				<div class="search-block">
					{{ Form::text('query', Input::get('query', ''), array('placeholder' => 'Поиск', 'class' => 'form-control')) }}
					{{ Form::submit('Найти', array('class' => 'btn btn-search')) }}
				</div>

				<div class="search-hint">Например: видеорегистраторы</div>
			{{ Form::close() }}
		</div> <!-- .span6 -->

		<div class="span3 nopadding">
			<div class="mikhCart">
				{{ HTML::link_to_route('cart', 'Корзина', array(), array('class' => 'mikhCart_icon', 'title' => ___('default', 'cart'))) }}

				<div>
					<span class="mikhCart_label">Корзина</span>
					<a href="{{ URL::to_route('cart') }}" class="mikhCart_link">
						<span class="mikhCart_checkout">Оформить</span>
					</a>
				</div>

				<div class="mikhCart_items">Товаров: <span id="cart_items" class="text-bold">{{ numberOfCartItems() }}</span></div>
				<div class="mikhCart_total">Сумма заказа: <span id="cart_total" class="text-bold">{{ cartTotal() }} тг.</span></div>
			</div> <!-- .mikh-cart -->
		</div> <!-- .span3 -->
	</div> <!-- .row -->

	<div class="row">
		<div class="span12 marketing">

			<div class="row">

				<div class="span3 marketing_block">
					<div class="marketing_block_delivery"></div>
					<div class="marketing_block_label">Доставка по всему Казахстану</div>
				</div> <!-- .span3 -->

				<div class="span3 marketing_block">
					<div class="marketing_block_quality"></div>
					<div class="marketing_block_label">Проверенное качество каждого товара</div>
				</div> <!-- .span3 -->

				<div class="span3 marketing_block">
					<div class="marketing_block_support"></div>
					<div class="marketing_block_label">Подготовленная и адекватная поддержка</div>
				</div> <!-- .span3 -->

				<div class="span3 marketing_block">
					<div class="marketing_block_phone"></div>
					<div class="marketing_block_label marketing_block__large">8 (727) 234 56 78</div>
				</div> <!-- .span3 -->

			</div>
		</div>
	</div> <!-- .row -->