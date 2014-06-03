<?php try { ?>

<div id="{{ $component->name }}_image" class="fileupload">
	<div class="progress"></div>
	<div class="thumbnail">

		<img class="jcrop-image image-edit-thumbnail" data-placeholder-src="{{ imagePlaceholder() }}" src="{{ $component->value() ?: imagePlaceholder() }}">

	</div> <!-- .thumbnail -->

	<div>

		<input id="fileupload" type="file" name="{{ $component->name }}" data-url="{{ URL::to_upload(Controller::$route) }}">

	</div>

	<div>

		<a href="{{ URL::to_route(Router::requestId(Controller::$route, 'destroy_file'), $component->upload_id()) }}" class="btn btn-lightred btn-image-delete {{ $component->value() ? '' : 'hidden' }}">

			{{ formLang($component->languageFile, 'delete') }}

		</a>

	</div>

	{{ Form::hidden('upload_token[' . $component->name . ']', Input::old('upload_token.' . $component->name, uniqid())) }}

	{{ Form::hidden($component->name . '[x]', '', array('id' => $component->name . '_x')) }}
	{{ Form::hidden($component->name . '[y]', '', array('id' => $component->name . '_y')) }}
	{{ Form::hidden($component->name . '[w]', '', array('id' => $component->name . '_w')) }}
	{{ Form::hidden($component->name . '[h]', '', array('id' => $component->name . '_h')) }}

	<div class="alert alert-error hide" id="cropError"></div>

</div>

<script>
var component = {
	showCoords_{{ $component->name }} : function(c) {
		$('#{{ $component->name }}_x').val(c.x);
		$('#{{ $component->name }}_y').val(c.y);
		$('#{{ $component->name }}_w').val(c.w);
		$('#{{ $component->name }}_h').val(c.h);
	},
	jcrop : {
		setSelect: [0, 0, 4000, 4000],
		boxWidth: 500,
		boxHeight: 500
	}
};

<?php $jcrop = is_array($component->jcrop) ? $component->jcrop : array(); ?>

@foreach ($jcrop as $param => $value)
component.jcrop.{{ $param }} = {{ $value }};
@endforeach

component.jcrop.onSelect = component.showCoords_{{ $component->name }};


$(document).ready(function()
{
	$('[name={{ $component->name }}]').fileupload({
		type: 'post',
		singleFileUploads: true,
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#{{$component->name}}_image .progress').text(progress + '%');
		},
		formData: function(form)
		{
			return [
				{
					name: "upload_token",
					value: form.find('[name=upload_token\\[{{ $component->name }}\\]]').val()
				},
				{
					name: "fieldName",
					value: "{{ $component->name }}"
				},
				{
					name: 'modelName',
					value: "{{ $component->getModelName() }}"
				},
				{
					name: 'single',
					value: true
				}
			];
		},
		dataType: 'json',
		done: function (e, data) {
			$('#cropError').hide();
			if (data.result.status !== 'error')
			{
				var appendTo = $('[name={{ $component->name }}]').parent(),
					jcrop = {{ $component->jcrop ? 1 : 0 }},
					img = $('div#{{ $component->name }}_image .jcrop-image').removeClass('image-edit-thumbnail')
						.attr('src', data.result.data.url);

				img.load(function() {
					$(this).css('max-width', 'none');
					$(this).css('width', 'auto');
					$(this).css('height', 'auto');
					crop_width = this.width;
					crop_height = this.height;

					if (jcrop)
					{
						component.jcrop.trueSize = [crop_width, crop_height];
						img.Jcrop(component.jcrop);
					}
				});
			} else {
				var errorString = (data.result.errors) ? data.result.errors.join('<br>') : 'Произошла ошибка при загрузке файла';
				$('#cropError').text(errorString).show();
			}
		}
	});

	$('body').on('click', '.btn-image-delete', function(e) {
		e.preventDefault();

		var _this = $(this),
			url   = _this.attr('href'),
			thumb = _this.closest('.fileupload').find('.jcrop-image');

		$.ajax({
			url: url,
			type: 'DELETE',
			success: function() {
				thumb.attr('src', thumb.data('placeholder-src'));
				_this.addClass('hidden');
			}
		});
	});
});
</script>

<?php } catch(\Exception $e) { exit($e->getMessage()); } ?>