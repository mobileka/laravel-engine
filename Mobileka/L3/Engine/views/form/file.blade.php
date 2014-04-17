<?php try { ?>

<div id="{{ $component->name }}_file" class="fileupload">

	<div class="thumbnail">

		<div class="file-edit-thumbnail">
			{{ $component->value() }}
		</div>

	</div> <!-- .thumbnail -->

</div>

<input id="fileupload" type="file" name="{{ $component->name }}">

<?php } catch(\Exception $e) { exit($e->getMessage()); } ?>