<div class="subnav">
	<div class="subnav-title">
		<a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>{{ Config::get('application.project_name') }}</span></a>
	</div>
	<ul class="subnav-menu">
		@foreach ($sections as $section)
			<li class="nav-header">{{ Arr::getItem($section, 'label', ___('default', 'no label')) }}</li>
			@foreach (Arr::getItem($section, 'items', array()) as $item)
<?php
	$active = Router::isCurrentRequestId($item['route']) ? ' class="active"' : '';

	if ($query = Arr::getItem($item, 'query', '')) {
		$query = "?$query";
	}
?>
				<li{{ $active }}>
					<a href="{{ route($item['route']).$query }}"><i class="{{ $item['icon'] }}"></i> {{ $item['label'] }}</a>
				</li>
			@endforeach
		@endforeach
	</ul>
</div>
