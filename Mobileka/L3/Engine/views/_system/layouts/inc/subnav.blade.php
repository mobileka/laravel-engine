<div class="subnav">
	<div class="subnav-title">
		<a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>{{ Config::get('application.projectName') }}</span></a>
	</div>
	<ul class="subnav-menu">
		@foreach ($sections as $section)
			<li class="nav-header">{{ Arr::getItem($section, 'label', ___('default', 'no label')) }}</li>
			@foreach (Arr::getItem($section, 'items', array()) as $item)
				<?php $class = (\Router::isCurrentRoute($item['route'])) ? ' class="active"' : ''; ?>
				<li{{ $class }}>
					<a href="{{ route($item['route']).Arr::getItem($item, 'suffix', '') }}"><i class="{{ $item['icon'] }}"></i> {{ $item['label'] }}</a>
				</li>
			@endforeach
		@endforeach
	</ul>
</div>
