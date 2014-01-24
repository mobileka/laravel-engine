<div class="plupload"></div>
{{ Form::hidden('upload_token', Input::old('upload_token', uniqid())) }}

@if ($component->featuredImageSelector)
	{{ Form::hidden('image_id', $component->row->image_id, array('id' => 'image_id')) }}
@endif

<div class="plupload-images {{ $component->featuredImageSelector ? 'plupload-images-featured-selector' : '' }}">
	@if (count($component->files))
		@foreach ($component->files as $file)
			{{--include('engine::form._multiupload_image')--}}

			<div class="thumbnail thumbnail-plupload {{ ($component->featuredImageSelector && $component->row->image_id == $file->id) ? 'featured-image' : '' }}">
				<a href="{{ URL::to_route($component->requestId.'_destroy_file', $file->id) }}" class="plupload-image-delete"><i class="glyphicon-circle_remove"></i></a>
				@if ($file->isImage())
					<img src="{{ $file->proudct_block_sidebar }}" class="featured-image-selector" data-id="{{ $file->id }}">
				@else
					<div class="file-attachment-extension">{{ $file->extension }}</div>
				@endif
			</div>

		@endforeach
	@endif
</div>