/*
	FLAT Theme v.1.4
	*/

/*
	* eakroko.js - Copyright 2013 by Ernst-Andreas Krokowski
	* Framework for themeforest themes

	* Date: 2013-01-01
	*/
	(function( $ ){
		$.fn.retina = function(retina_part) {
		// Set default retina file part to '-2x'
		// Eg. some_image.jpg will become some_image-2x.jpg
		var settings = {'retina_part': '-2x'};
		if(retina_part) jQuery.extend(settings, { 'retina_part': retina_part });
		if(window.devicePixelRatio >= 2) {
			this.each(function(index, element) {
				if(!$(element).attr('src')) return;

				var checkForRetina = new RegExp("(.+)("+settings['retina_part']+"\\.\\w{3,4})");
				if(checkForRetina.test($(element).attr('src'))) return;

				var new_image_src = $(element).attr('src').replace(/(.+)(\.\w{3,4})$/, "$1"+ settings['retina_part'] +"$2");
				$.ajax({url: new_image_src, type: "HEAD", success: function() {
					$(element).attr('src', new_image_src);
				}});
			});
		}
		return this;
	};
})( jQuery );
function icheck(){
	if($(".icheck-me").length > 0){
		$(".icheck-me").each(function(){
			var $el = $(this);
			var skin = ($el.attr('data-skin') !== undefined) ? "_"+$el.attr('data-skin') : "",
			color = ($el.attr('data-color') !== undefined) ? "-"+$el.attr('data-color') : "";

			var opt = {
				checkboxClass: 'icheckbox' + skin + color,
				radioClass: 'iradio' + skin + color,
				increaseArea: "10%"
			};
			$el.iCheck(opt);
			if ($el.hasClass('icheck-me-trigger')) {
				var hidden_checkbox = $el.closest('.check-line').find('.checkbox-value'),
					checkedValue = hidden_checkbox.data('checked-value'),
					uncheckedValue = hidden_checkbox.data('unchecked-value')
				;

				$el.on('is.Changed', function() {
					var value = $el.prop('checked') ? checkedValue : uncheckedValue;
					hidden_checkbox.val(value);
				});
			}

		});
	}
}

var valid_dimensions = {
	products: [540, 600]
};

$(document).ready(function() {
	var mobile = false,
	tooltipOnlyForDesktop = true,
	notifyActivatedSelector = 'button-active';

	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
		mobile = true;
	}

	icheck();

	// Round charts (easypie)
	if($(".chart").length > 0)
	{
		$(".chart").each(function(){
			var color = "#881302",
			$el = $(this);
			var trackColor = $el.attr("data-trackcolor");
			if($el.attr('data-color'))
			{
				color = $el.attr('data-color');
			}
			else
			{
				if(parseInt($el.attr("data-percent")) <= 25)
				{
					color = "#046114";
				}
				else if(parseInt($el.attr("data-percent")) > 25 && parseInt($el.attr("data-percent")) < 75)
				{
					color = "#dfc864";
				}
			}
			$el.easyPieChart({
				animate: 1000,
				barColor: color,
				lineWidth: 5,
				size: 80,
				lineCap: 'square',
				trackColor: trackColor
			});
		});
	}

	// Calendar (fullcalendar)
	if($('.calendar').length > 0)
	{
		$('.calendar').fullCalendar({
			header: {
				left: '',
				center: 'prev,title,next',
				right: 'month,agendaWeek,agendaDay,today'
			},
			buttonText:{
				today:'Today'
			},
			editable: true
		});
		$(".fc-button-effect").remove();
		$(".fc-button-next .fc-button-content").html("<i class='icon-chevron-right'></i>");
		$(".fc-button-prev .fc-button-content").html("<i class='icon-chevron-left'></i>");
		$(".fc-button-today").addClass('fc-corner-right');
		$(".fc-button-prev").addClass('fc-corner-left');
	}

	// Tooltips (only for desktop) (bootstrap tooltips)
	if(tooltipOnlyForDesktop)
	{
		if(!mobile)
		{
			$('[rel=tooltip]').tooltip();
		}
	}


	// Notifications
	$(".notify").click(function(){
		var $el = $(this);
		var title = $el.attr('data-notify-title'),
		message = $el.attr('data-notify-message'),
		time = $el.attr('data-notify-time'),
		sticky = $el.attr('data-notify-sticky'),
		overlay = $el.attr('data-notify-overlay');

		$.gritter.add({
			title: 	(typeof title !== 'undefined') ? title : 'Message - Head',
			text: 	(typeof message !== 'undefined') ? message : 'Body',
			image: 	(typeof image !== 'undefined') ? image : null,
			sticky: (typeof sticky !== 'undefined') ? sticky : false,
			time: 	(typeof time !== 'undefined') ? time : 3000
		});
	});

	// masked input
	if($('.mask_date').length > 0){
		$(".mask_date").mask("9999/99/99");
	}
	if($('.mask_phone').length > 0){
		$(".mask_phone").mask("(999) 999-9999");
	}
	if($('.mask_serialNumber').length > 0){
		$(".mask_serialNumber").mask("9999-9999-99");
	}
	if($('.mask_productNumber').length > 0){
		$(".mask_productNumber").mask("aaa-9999-a");
	}
	// tag-input
	if($(".tagsinput").length > 0){
		$(".tagsinput").each(function(index, element) {
			$(element).tagsInput({width:'auto', height:'auto'});
		});
	}

	// datepicker
	if($('.datepick').length > 0){
		$('.datepick').datepicker({
			format: 'yyyy-mm-dd',
			weekStart: 1,
			language: 'ru',
		}).on('changeDate', function(ev) {
			var id = $(this).attr('id'),
				value = $(this).val();

			$('#' + id + '_from').val(value + ' 00:00:00');
			$('#' + id + '_to').val(value + ' 23:59:59');
		});
	}
	// timepicker
	if($('.timepick').length > 0){
		$('.timepick').timepicker({
			defaultTime: 'current',
			minuteStep: 1,
			disableFocus: true,
			template: 'dropdown'
		});
	}
	// colorpicker
	if($('.colorpick').length > 0){
		$('.colorpick').colorpicker();
	}
	// uniform
	if($('.uniform-me').length > 0){
		$('.uniform-me').uniform({
			radioClass : 'uni-radio',
			buttonClass : 'uni-button'
		});
	}
	// Chosen (chosen)
	if($('.chosen-select').length > 0)
	{
		$('.chosen-select').each(function(){
			var $el = $(this);
			var search = ($el.attr("data-nosearch") === "true") ? true : false,
			opt = {
				disable_search_threshold: 10,
				placeholder_text_multiple: 'Введите или выберите значения',
				no_results_text: "Ничего не найдено"
			};
			if(search) opt.disable_search_threshold = 9999999;
			$el.chosen(opt);
		});
	}

	if ($(".select2-me").length > 0){
		function format(state) {
			if (!state.id) return state.text; // optgroup
			var filename = colors_filenames['color_' + state.id];
			return "<img class='flag' src='" + BASE + "/uploads/products_colors/product_color_icon/" + filename + "'> " + state.text;
		}

		$(".select2-me").select2({
			formatResult: format,
			formatSelection: format,
			escapeMarkup: function(m) { return m; }
		});
	}

	// multi-select
	if ($('.multiselect').length) {
		$(".multiselect").each(function() {
			var $el = $(this);
			var selectableHeader = $el.data('selectableheader'),
				selectionHeader	 = $el.data('selectionheader')
				selectableId		 = 'selectable' + $el.attr('id'),
				selectionId			= 'selection' + $el.attr('id');

			$el.multiSelect({
				selectableHeader : '<input id="' +	selectableId + '" type="text" class="search-input input-multiselect-heading" autocomplete="off" placeholder="' + selectableHeader + '">',
				selectionHeader	: '<input id="' +	selectionId	+ '" type="text" class="search-input input-multiselect-heading" autocomplete="off" placeholder="' + selectionHeader	+ '">',
				afterInit: function(ms) {
					var that									 = this,
						$selectableSearch			= that.$selectableUl.prev(),
						$selectionSearch			 = that.$selectionUl.prev(),
						selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
						selectionSearchString	= '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

					that.qs1 = $selectableSearch.quicksearch(selectableSearchString);
					that.qs2 = $selectionSearch.quicksearch(selectionSearchString);
				},
				afterSelect: function() {
					this.qs1.cache();
					this.qs2.cache();
				},
				afterDeselect: function() {
					this.qs1.cache();
					this.qs2.cache();
				}
			});
		});
	}

	// spinner
	if($('.spinner').length > 0){
		$('.spinner').spinner();
	}

	// dynatree
	if($(".filetree").length > 0){
		$(".filetree").each(function(){
			var $el = $(this),
			opt = {};
			opt.debugLevel = 0;
			if($el.hasClass("filetree-callbacks")){
				opt.onActivate = function(node){
					console.log(node.data);
					$(".activeFolder").text(node.data.title);
					$(".additionalInformation").html("<ul style='margin-bottom:0;'><li>Key: "+node.data.key+"</li><li>is folder: "+node.data.isFolder+"</li></ul>");
				};
			}
			if($el.hasClass("filetree-checkboxes")){
				opt.checkbox = true;

				opt.onSelect = function(select, node){
					var selNodes = node.tree.getSelectedNodes();
					var selKeys = $.map(selNodes, function(node){
						return "[" + node.data.key + "]: '" + node.data.title + "'";
					});
					$(".checkboxSelect").text(selKeys.join(", "));
				};
			}

			if ($el.hasClass('filetree-menu')) {
				opt.dnd = {
					onDragStart: function(node) {
						return true;
					},
					onDragStop: function(node) {

					},

					preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.

					onDragEnter: function(node, sourceNode) {
						return true;
					},

					onDragOver: function(node, sourceNode, hitMode) {
						// Prevent dropping a parent below it's own child
						//
						if (node.isDescendantOf(sourceNode)) {
							return false;
						}
						// Prohibit creating childs in non-folders (only sorting allowed)
						if( !node.data.isFolder && hitMode === "over" ){
							return "after";
						}
					},

					onDrop: function(node, sourceNode, hitMode, ui, draggable) {

						if (hitMode === 'over') {
							node.data.isFolder = true;
							sourceNode.expand(true);
						}

						sourceNode.move(node, hitMode);

						$.post( $el.data('move-url'), {
							node_id   : sourceNode.data.id,
							target_id : node.data.id,
							mode      : hitMode
						}, function(data) {
							if (data.result == 'error') {
								console.log(data.result);
							}
						});

					},

					onDragLeave: function(node, sourceNode) {

					}
				};
			}

			opt.fx = {
				height: "toggle", duration: 200
			};

			if ($el.hasClass('filetree-menu')) {
				opt.onActivate = function(node) {
					// window.location.href = node.data.url;
				};
			}

			$el.dynatree(opt);
		});
	}

	if($(".colorbox-image").length > 0){
		$(".colorbox-image").colorbox({
			maxWidth: "90%",
			maxHeight: "90%",
			rel: $(this).attr("rel")
		});
	}

	// PlUpload
	if ($('.plupload').length > 0) {
		plupload.addI18n({
		'Select files' : 'Выберите файлы',
		'Add files to the upload queue and click the start button.' : 'Добавьте файлы в очередь и нажмите кнопку "Загрузить файлы".',
		'Filename' : 'Имя файла',
		'Status' : 'Статус',
		'Size' : 'Размер',
		'Add files' : 'Добавить файлы',
		'Stop current upload' : 'Остановить загрузку',
		'Start uploading queue' : 'Загрузить файлы',
		'Uploaded %d/%d files': 'Загружено %d/%d',
		'N/A' : 'N/D',
		'Drag files here.' : 'Перетащите файлы сюда.',
		'File extension error.': 'Неправильное расширение файла.',
		'File size error.': 'Неправильный размер файла.',
		'Init error.': 'Ошибка инициализации.',
		'Start upload': 'Загрузить',
		'HTTP Error.': 'Ошибка HTTP.',
		'Security error.': 'Ошибка безопасности.',
		'Generic error.': 'Общая ошибка.',
		'IO error.': 'Ошибка ввода-вывода.'
	});

		$(".plupload").each(function(){
			var $el = $(this),
				fieldname = $el.data('fieldname'),
				uploadUrl = $el.data('upload-url'),
				thumbnailUrl = $el.data('thumbnail-url'),
				modelName = $el.data('modelname');

			$el.pluploadQueue({
				runtimes : 'html5,gears,flash,silverlight,browserplus',
				url : uploadUrl,
				file_data_name: fieldname,
				max_file_size : '50mb',
				chunk_size : '10mb',
				unique_names : true,
				/*filters : [
					{title : "Image files", extensions : "jpg,gif,png"},
					{title : "Zip files", extensions : "zip"}
				],*/
				flash_swf_url : 'js/plupload/plupload.flash.swf',
				silverlight_xap_url : 'js/plupload/plupload.silverlight.xap'
			});

			$(".plupload_header").remove();
			var upload = $el.pluploadQueue();
			upload.settings.multipart_params = {
				'csrf_token' : $('head [name=csrf_token]').attr('content'),
				'upload_token' : $('[name="upload_token[' + fieldname + ']"]').val(),
				'fieldName': fieldname,
				'modelName' : modelName,
				'single' : 0
			};

			if($el.hasClass("pl-sidebar")){
				$(".plupload_filelist_header,.plupload_progress_bar,.plupload_start").remove();
				$(".plupload_droptext").html("<span>Перетащите картинки сюда</span>");
				$(".plupload_progress").remove();
				$(".plupload_add").text("Or click here...");

				upload.bind('FilesAdded', function(up, files) {
					setTimeout(function () {
						up.start();
					}, 500);
				});
				upload.bind("QueueChanged", function(up){
					$(".plupload_droptext").html("<span>Drop files to upload</span>");
				});
				upload.bind("StateChanged", function(up){
					$(".plupload_upload_status").remove();
					$(".plupload_buttons").show();
				});
			} else {
				$(".plupload_progress_container").addClass("progress").addClass('progress-striped');
				$(".plupload_progress_bar").addClass("bar");

				upload.bind('FileUploaded', function(up, file, response) {
					response = $.parseJSON(response.response);

					if (response.error) {
						$('.modal-dimensions-error').modal('show');
					}
					else {
						var url = thumbnailUrl + '/' + response.data.id;

						$.get(url, {

						}, function(response) {
							var container = $el.parent().find('.plupload-images');

							container.append(response.thumbnail);

							if ($('#image_id').length && container.find('img').length === 1) {
								container.find('img').closest('.thumbnail-plupload').addClass('featured-image');
								$('#image_id').val(container.find('img').data('id'));
							}

						});
					}
				});

				upload.bind('UploadComplete', function(uploader, files) {
					$('.plupload [type=hidden]').remove();
				});

				$(".plupload_button").each(function(){
					if($(this).hasClass("plupload_add")){
						$(this).attr("class", 'btn pl_add btn-primary').html("<i class='icon-plus-sign'></i> "+$(this).html());
					} else {
						$(this).attr("class", 'btn pl_start btn-success').html("<i class='icon-cloud-upload'></i> "+$(this).html());
					}
				});

				$el.find('[type=hidden]').remove();
			}
		});
	}

	// Wizard
	if ($(".form-wizard").length && $().formwizard) {
		$(".form-wizard").formwizard({
			formPluginEnabled: true,
			validationEnabled: false,
			focusFirstInput : false,
			disableUIStyles:true,
			textSubmit: "Сохранить",
			textNext: "Далее",
			textBack: "Назад",
			validationOptions: {
				errorElement:'span',
				errorClass: 'help-block error',
				errorPlacement:function(error, element){
					element.parents('.controls').append(error);
				},
				highlight: function(label) {
					$(label).closest('.control-group').removeClass('error success').addClass('error');
				},
				success: function(label) {
					label.addClass('valid').closest('.control-group').removeClass('error success').addClass('success');
				}
			},
			formOptions :{
				success: function(data){
					alert("Response: \n\n"+data.say);
				},
				dataType: 'json',
				resetForm: true
			}
		});
	}

	// Validation
	if($('.form-validate').length > 0)
	{
		$('.form-validate').each(function(){
			var id = $(this).attr('id');
			$("#"+id).validate({
				errorElement:'span',
				errorClass: 'help-block error',
				errorPlacement:function(error, element){
					element.parents('.controls').append(error);
				},
				highlight: function(label) {
					$(label).closest('.control-group').removeClass('error success').addClass('error');
				},
				success: function(label) {
					label.addClass('valid').closest('.control-group').removeClass('error success').addClass('success');
				}
			});
		});
	}

	// dataTables
	if($('.dataTable').length > 0){
		$('.dataTable').each(function(){
			var opt = {
				"aaSorting": [[ 0, "desc" ]],
				"sPaginationType": "full_numbers",
				"oLanguage":{
					"sSearch": "<span>Поиск:</span> ",
					"sInfo": "Записи с <span>_START_</span> по <span>_END_</span>. Всего записей <span>_TOTAL_</span>.",
					"sLengthMenu": "_MENU_ <span>записей на странице</span>",
					"sEmptyTable": "Данные не найдены",
					"oPaginate": {
						"sFirst": "В начало",
						"sLast": "В конец",
						"sNext": "След.",
						"sPrevious": "Пред."
					}

				}
			};
			if ($(this).hasClass("dataTable-100")) {
				opt.iDisplayLength = 100;
			}
			if($(this).hasClass("dataTable-noheader")){
				opt.bFilter = false;
				opt.bLengthChange = false;
			}
			if($(this).hasClass("dataTable-nofooter")){
				opt.bInfo = false;
				opt.bPaginate = false;
			}
			if($(this).hasClass("dataTable-nosort")){
				var column = $(this).data('nosort');
				column = column.split(',');
				for (var i = 0; i < column.length; i++) {
					column[i] = parseInt(column[i]);
				};
				opt.aoColumnDefs =	[
				{ 'bSortable': false, 'aTargets': column }
				];
			}
			if($(this).hasClass("dataTable-scroll-x")){
				opt.sScrollX = "100%";
				opt.bScrollCollapse = true;
			}
			if($(this).hasClass("dataTable-scroll-y")){
				opt.sScrollY = "300px";
				opt.bPaginate = false;
				opt.bScrollCollapse = true;
			}
			if($(this).hasClass("dataTable-reorder")){
				opt.sDom = "Rlfrtip";
			}
			if($(this).hasClass("dataTable-colvis")){
				opt.sDom = 'C<"clear">lfrtip';
				opt.oColVis = {
					"buttonText": "Колонки <i class='icon-angle-down'></i>"
				};
			}
			if($(this).hasClass('dataTable-tools')){
				opt.sDom= 'T<"clear">lfrtip';
				opt.oTableTools = {
					"sSwfPath": "js/plugins/datatable/swf/copy_csv_xls_pdf.swf"
				};
			}
			if($(this).hasClass("dataTable-scroller")){
				opt.sScrollY = "300px";
				opt.bDeferRender = true;
				opt.sDom = "frtiS";
				opt.sAjaxSource = "js/plugins/datatable/demo.txt";
			}
			var oTable = $(this).dataTable(opt);
			$('.dataTables_filter input').attr("placeholder", "");
			$(".dataTables_length select").wrap("<div class='input-mini'></div>").chosen({
				disable_search_threshold: 9999999
			});
			if($(this).hasClass("dataTable-fixedcolumn")){
				new FixedColumns( oTable );
			}
			if($(this).hasClass("dataTable-columnfilter")){
				oTable.columnFilter({
					"sPlaceHolder" : "head:after"
				});
			}
		});
}

	// force correct width for chosen
	resize_chosen();

	// file_management
	if($('.file-manager').length > 0)
	{
		$('.file-manager').elfinder({
			url:'js/plugins/elfinder/php/connector.php'
		});
	}

	// slider
	if($('.slider').length > 0)
	{
		$(".slider").each(function(){
			var $el = $(this);
			var min = parseInt($el.attr('data-min')),
			max = parseInt($el.attr('data-max')),
			step = parseInt($el.attr('data-step')),
			range = $el.attr('data-range'),
			rangestart = parseInt($el.attr('data-rangestart')),
			rangestop = parseInt($el.attr('data-rangestop'));

			var opt = {
				min: min,
				max: max,
				step: step,
				slide: function( event, ui ) {
					$el.find('.amount').html( ui.value );
				}
			};

			if(range !== undefined)
			{
				opt.range = true;
				opt.values = [rangestart, rangestop];
				opt.slide = function( event, ui ) {
					$el.find('.amount').html( ui.values[0]+" - "+ui.values[1] );
				};
			}

			$el.slider(opt);
			if(range !== undefined){
				var val = $el.slider('values');
				$el.find('.amount').html(val[0] + ' - ' + val[1]);
			} else {
				$el.find('.amount').html($el.slider('value'));
			}
		});
	}

	if ($('.nav-pills').length) {
		$('.nav-pills, .tab-content').each(function() {
			$(this).find('a:first').parent().addClass('active');
			$(this).find('.tab-pane:first').addClass('active');
		});


		$('.nav-pills a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
		});
	}
/*
	if($(".ckeditor").length > 0){
		CKEDITOR.replace("ck");
	}
*/

	$(".retina-ready").retina("@2x");
});

$(window).resize(function() {
	// chosen resize bug
	resize_chosen();
});

function resize_chosen(){
	$('.chzn-container').each(function() {
		var $el = $(this);
		$el.css('width', $el.parent().width()+'px');
		$el.find(".chzn-drop").css('width', ($el.parent().width()-2)+'px');
		$el.find(".chzn-search input").css('width', ($el.parent().width()-37)+'px');
	});
}