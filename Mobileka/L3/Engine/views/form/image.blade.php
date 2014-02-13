@if ($component->jcropParams)
<script>
	app.showCoords_{{ $component->name }} = function(c) {
		$('#{{ $component->name }}_x').val(c.x);
		$('#{{ $component->name }}_y').val(c.y);
		$('#{{ $component->name }}_w').val(c.w);
		$('#{{ $component->name }}_h').val(c.h);
	};

	app.jcropParams.{{ $component->name }} = {
		setSelect: [0, 0, 100, 50],
		boxWidth: 500,
		boxHeight: 500
	};

	@foreach ($component->jcropParams as $param => $value)
	app.jcropParams.{{ $component->name }}.{{ $param }} = {{ $value }};
	@endforeach

	app.jcropParams.{{ $component->name }}.onSelect = app.showCoords_{{ $component->name }};

</script>
@endif

<div id="{{ $component->name }}_id" data-provides="fileupload" class="fileupload {{ $component->value() ? 'fileupload-exists' : 'fileupload-new' }}" data-image_field="{{ $component->name }}" data-jcrop="{{ $component->jcropParams ? 'true' : 'false' }}">

	<div class="fileupload-new thumbnail {{ $component->div_class }}">
		<img alt="" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image">
	</div>

	<div class="fileupload-preview fileupload-exists thumbnail {{ $component->div_class }}">
		<img alt="" src="{{ $component->value() ? $component->row->getImageSrc($component->name, 'small_thumb', true) : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image'}}">
	</div>

	<div>
		<span class="btn btn-file">
			<span class="fileupload-new">{{ ___('default', 'labels.select_image') }}</span>
			<span class="fileupload-exists">{{ ___('default', 'labels.change_image') }}</span>

			{{ Form::file($component->name, array('class' => 'fileupload-file')) }}
		</span>

		<a data-dismiss="fileupload" class="btn fileupload-exists" href="#">{{ ___('default', 'labels.remove_image') }}</a>
	</div>

	{{ Form::hidden($component->name . '[x]', '', array('id' => $component->name . '_x')) }}
	{{ Form::hidden($component->name . '[y]', '', array('id' => $component->name . '_y')) }}
	{{ Form::hidden($component->name . '[w]', '', array('id' => $component->name . '_w')) }}
	{{ Form::hidden($component->name . '[h]', '', array('id' => $component->name . '_h')) }}

</div>