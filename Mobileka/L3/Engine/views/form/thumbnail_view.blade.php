<?php try { ?>

<?php
	$fisClass = (isset($component) && $component->featuredImageSelector && $component->row->image_id == $file->id)
		? ' featured-image'
		: ''
	;

	$destroyFileUrl = URL::to_route(Router::requestId(Controller::$route, 'destroy_file'), $file->id);
?>

<div class="thumbnail thumbnail-plupload{{ $fisClass }}">
	<a href="{{ $destroyFileUrl }}" class="plupload-image-delete"><i class="glyphicon-circle_remove"></i></a>
	@if ($file->isImage())
		<img src="{{ $file->multiupload_thumb }}" class="featured-image-selector" data-id="{{ $file->id }}">
	@else
		<div class="file-attachment-extension">{{ $file->extension }}</div>
	@endif
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
