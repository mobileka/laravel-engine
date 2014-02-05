<div class="row">
	<div class="span12 breadcrumbs">
		<a href="{{ URL::to_route('home') }}">{{___('default', 'main_page')}}</a> &gt;
		@foreach ($breadcrumbs as $breadcrumb)
		<a href="{{ $breadcrumb->url }}">{{ $breadcrumb->text }}</a> &gt;
		@endforeach
		{{ $item->name }}
	</div>
</div> <!-- .row -->