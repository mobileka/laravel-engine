<div class="thumbnail thumbnail-plupload {{ (isset($component) && $component->featuredImageSelector && $component->row->image_id == $file->id) ? 'featured-image' : '' }}">
	<a href="{{ URL::to_route('uploads_admin_default_destroy', $file->id) }}" class="plupload-image-delete"><i class="glyphicon-circle_remove"></i></a>
	@if ($file->isImage())
		<img src="{{ $file->proudct_block_sidebar }}" class="featured-image-selector" data-id="{{ $file->id }}">
	@else
		<div class="file-attachment-extension">{{ $file->extension }}</div>
	@endif
</div>