<div class="text-center">
	<a href="{{ URL::to_route($component->route, $component->params) }}" {{ HTML::attributes($component->attributes) }}>{{ $component->label }}</a>
</div>