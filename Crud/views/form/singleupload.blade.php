<div class="fileupload fileupload-new" data-provides="fileupload">
	<span class="btn btn-file">
		<span class="fileupload-new">{{ ___('default', 'labels.select_file') }}</span>
		<span class="fileupload-exists">{{ ___('default', 'labels.change') }}</span>
		<input type="file" name="{{ $component->name }}">
	</span>
	<span class="fileupload-preview"></span>
	<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
</div>