<?php try { ?>

<?php
$uploader = IoC::resolve('Uploader');
$fileTemplate = $uploader::$template;
?>

<div class="plupload" data-thumbnail-url="{{ URL::to_thumbnail(Controller::$route) }}" data-upload-url="{{ URL::to_upload(Controller::$route) }}" data-fieldname="{{$component->name}}" data-modelname="{{ $component->getModelName() }}"></div>
{{ Form::hidden('upload_token[' . $component->name . ']', Input::old('upload_token.' . $component->name, uniqid())) }}

@if ($fis = $component->featuredImageSelector)
	{{ Form::hidden($fis, $component->row->{$fis}, array('id' => $fis)) }}
@endif

<div class="plupload-images {{ $component->featuredImageSelector ? 'plupload-images-featured-selector' : '' }}">
	@foreach ($component->files as $file)
		@include($fileTemplate)
	@endforeach
</div>

<script>

jQuery(function($) {

if (typeof window.PLUPLOAD_FEATURED_HANDLERS === 'undefined') {
	window.PLUPLOAD_FEATURED_HANDLERS = true;

	$('body').on('click', '.plupload-image-delete', function(e) {
		e.preventDefault();

		var url   = $(this).attr('href'),
			thumb = $(this).parent();

		$.ajax({
			url: url,
			type: 'DELETE',
			success: function() {
				if (thumb.hasClass('featured-image') && $(thumb).siblings('.thumbnail-plupload').length) {
					$(thumb).siblings('.thumbnail-plupload').eq(0).find('.featured-image-selector').click();
				}

				thumb.remove();
			}
		});
	});

	$('body').on('click', '.plupload-images-featured-selector .featured-image-selector', function(e) {
		e.preventDefault();

		var id = $(this).data('id');

		$(this).closest('.plupload-images').find('.thumbnail-plupload').removeClass('featured-image');
		$(this).closest('.thumbnail-plupload').addClass('featured-image');

		$('#{{ $component->name }}').val(id);
	});
}
@if ($fis)
	{{-- Select featured image on page load --}}
	$('.featured-image-selector[data-id="{{ $component->row->{$fis} }}"]').trigger('click');
@endif

});

</script>

<?php } catch (\Exception $e) { exit($e->getMessage()); } ?>
