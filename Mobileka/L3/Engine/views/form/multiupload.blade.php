<div class="plupload"></div>
{{ Form::hidden('upload_token', Input::old('upload_token', uniqid())) }}

@if ($fis = $component->featuredImageSelector)
	{{ Form::hidden($fis, $component->row->image_id, array('id' => $fis)) }}
@endif

<div class="plupload-images {{ $component->featuredImageSelector ? 'plupload-images-featured-selector' : '' }}">
	@if (count($component->files))
		@foreach ($component->files as $file)
			@include('crud::form._multiupload_image')
		@endforeach
	@endif
</div>