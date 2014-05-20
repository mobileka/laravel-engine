<?php try { ?>

<div id="{{ $component->name }}">

	<div class="simple_file_thumbnail">

		<div class="file-edit-thumbnail">
			<img src="{{ $component->value() }}"> 
		</div>

	</div> <!-- .thumbnail -->

</div>

<input type="file" name="{{ $component->name }}">

<?php } catch(\Exception $e) { exit($e->getMessage()); } ?>