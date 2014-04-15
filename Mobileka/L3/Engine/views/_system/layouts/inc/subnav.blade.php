<div class="subnav">
	<div class="subnav-title">
		<a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>{{ Config::get('application.project_name') }}</span></a>
	</div>
	<ul class="subnav-menu">
		@foreach ($sections as $section)
			<li class="nav-header">{{ Arr::getItem($section, 'label', ___('default', 'no label')) }}</li>
			@foreach (Arr::getItem($section, 'items', array()) as $item)
<?php
$query = Arr::getItem($item, 'query', '');
$active = \Router::isCurrentRoute($item['route'])
	&& ($query == \Request::foundation()->getQueryString());
if ($query) {
	$query = "?$query";
}
?>
				<li class="{{ $active ? 'active' : '' }}">
					<a href="{{ route($item['route']).$query }}"><i class="{{ $item['icon'] }}"></i> {{ $item['label'] }}</a>
				</li>
			@endforeach
		@endforeach
	</ul>
</div>
