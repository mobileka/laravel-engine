<?php try { ?>

<div id="{{ $component->name }}">

	<div class="simple_file_thumbnail">

		<div class="file-edit-thumbnail">
			<a href="{{ $component->url() }}" download>
				<img src="{{ $component->value() }}">
			</a>
		</div>

	</div> <!-- .thumbnail -->

</div>

<input type="file" name="{{ $component->name }}">

<?php } catch(\Exception $e) { exit($e->getMessage()); } ?>