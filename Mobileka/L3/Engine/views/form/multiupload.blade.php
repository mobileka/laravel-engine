<div class="plupload"></div>
{{ Form::hidden('upload_token', Input::old('upload_token', uniqid())) }}

@if ($component->featuredImageSelector)
	{{ Form::hidden('image_id', $component->row->image_id, array('id' => 'image_id')) }}
@endif

<div class="plupload-images {{ $component->featuredImageSelector ? 'plupload-images-featured-selector' : '' }}">
	@if (count($component->files))
		@foreach ($component->files as $file)

			@include('engine::form._multiupload_image')

		@endforeach
	@endif
</div>
