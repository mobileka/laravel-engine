<?php

/*
 * These assets are required for admin interface to work properly.
 *
 * To properly include them in your admin layout first run
 * `php artisan bundle:publish` which will publish all assets to public
 * directory. Then, inside admin layout make sure you have these lines:

<head>
... snip

{{ Asset::container('engine_assets')->styles() }}
@yield('styles')
<script>
	var BASE = "{{ URL::base() }}";
	URL_KEEPER = {
	};

	@yield('script_vars')
</script>
{{ Asset::container('engine_assets')->scripts() }}
@yield('plugins')
@yield('scripts')

... snip
</head>

 */

Laravel\Asset::container('engine_assets')
	->add('jquery',                           'bundles/engine/bower_components/jquery/jquery.min.js')
	->add('bootstrap',                        'bundles/engine/admin/css/bootstrap.min.css')
	->add('bootstrap-responsive',             'bundles/engine/admin/css/bootstrap-responsive.min.css')
	->add('colorbox',                         'bundles/engine/admin/css/plugins/colorbox/colorbox.css')
	->add('jquery-ui',                        'bundles/engine/admin/css/plugins/jquery-ui/smoothness/jquery-ui.css')
	->add('jquery-ui-theme',                  'bundles/engine/admin/css/plugins/jquery-ui/smoothness/jquery.ui.theme.css')
	->add('pageguide',                        'bundles/engine/admin/css/plugins/pageguide/pageguide.css')
	->add('fullcalendar',                     'bundles/engine/admin/css/plugins/fullcalendar/fullcalendar.css')
	->add('fullcalendar-print',               'bundles/engine/admin/css/plugins/fullcalendar/fullcalendar.print.css')
	->add('tagsinput',                        'bundles/engine/admin/css/plugins/tagsinput/jquery.tagsinput.css')
	->add('datatable',                        'bundles/engine/admin/css/plugins/datatable/TableTools.css')
	->add('chosen',                           'bundles/engine/admin/css/plugins/chosen/chosen.css')
	->add('multiselect',                      'bundles/engine/admin/css/plugins/multiselect/multi-select.css')
	->add('timepicker',                       'bundles/engine/admin/css/plugins/timepicker/bootstrap-timepicker.min.css')
	->add('colorpicker',                      'bundles/engine/admin/css/plugins/colorpicker/colorpicker.css')
	->add('datepicker',                       'bundles/engine/admin/css/plugins/datepicker/datepicker.css')
	->add('plupload',                         'bundles/engine/admin/css/plugins/plupload/jquery.plupload.queue.css')
	->add('select2',                          'bundles/engine/admin/css/plugins/select2/select2.css')
	->add('icheck',                           'bundles/engine/admin/css/plugins/icheck/all.css')
	->add('jcrop',                            'bundles/engine/admin/css/plugins/jcrop/jquery.Jcrop.css')
	->add('dynatree',                         'bundles/engine/admin/css/plugins/dynatree/ui.dynatree.css')
	->add('alertify_core',                    'bundles/engine/bower_components/alertify/themes/alertify.core.css')
	->add('alertify_theme',                   'bundles/engine/bower_components/alertify/themes/alertify.bootstrap.css')

	->add('style',                            'bundles/engine/admin/css/style.css')
	->add('themes',                           'bundles/engine/admin/css/themes.css')
	->add('admin',                            'bundles/engine/admin/css/admin.css')

	->add('bootstrap',                        'bundles/engine/admin/js/bootstrap.min.js')
	->add('csrf',                             'bundles/engine/csrf.js')
	->add('alertify',                         'bundles/engine/bower_components/alertify/alertify.min.js')
	->add('nicescroll',                       'bundles/engine/admin/js/plugins/nicescroll/jquery.nicescroll.min.js')
	->add('imagesloaded',                     'bundles/engine/admin/js/plugins/imagesLoaded/jquery.imagesloaded.min.js')
	->add('ui-core',                          'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.core.min.js')
	->add('ui-widget',                        'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.widget.min.js')
	->add('ui-mouse',                         'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.mouse.min.js')
	->add('ui-resizable',                     'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.resizable.min.js')
	->add('ui-sortable',                      'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.sortable.min.js')
	->add('ui-ui',                            'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.spinner.js')
	->add('ui-ui',                            'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.slider.js')
	->add('ui-draggable',                     'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.draggable.min.js')
	->add('ui-droppable',                     'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.droppable.min.js')
	->add('ui-ui',                            'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.position.js')
	->add('ui-menu',                          'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.menu.js')
	->add('ui-autocomplete',                  'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.autocomplete.js')
	->add('ui-spinner',                       'bundles/engine/admin/js/plugins/jquery-ui/jquery.ui.spinner.js')
	->add('slimscroll',                       'bundles/engine/admin/js/plugins/slimscroll/jquery.slimscroll.min.js')
	->add('bootbox',                          'bundles/engine/admin/js/plugins/bootbox/jquery.bootbox.js')
	->add('form',                             'bundles/engine/admin/js/plugins/form/jquery.form.min.js')
	->add('dataTables',                       'bundles/engine/admin/js/plugins/datatable/jquery.dataTables.min.js')
	->add('TableTools',                       'bundles/engine/admin/js/plugins/datatable/TableTools.min.js')
	->add('ColReorder',                       'bundles/engine/admin/js/plugins/datatable/ColReorder.min.js')
	->add('ColVis',                           'bundles/engine/admin/js/plugins/datatable/ColVis.min.js')
	->add('columnFilter',                     'bundles/engine/admin/js/plugins/datatable/jquery.dataTables.columnFilter.js')
	->add('maskedinput',                      'bundles/engine/admin/js/plugins/maskedinput/jquery.maskedinput.min.js')
	->add('tagsinput',                        'bundles/engine/admin/js/plugins/tagsinput/jquery.tagsinput.min.js')
	->add('bootstrap-datepicker',             'bundles/engine/admin/js/plugins/datepicker/bootstrap-datepicker.js')
	->add('bootstrap-datepicker-ru',          'bundles/engine/admin/js/plugins/datepicker/locales/bootstrap-datepicker.ru.js')
	->add('bootstrap-timepicker',             'bundles/engine/admin/js/plugins/timepicker/bootstrap-timepicker.min.js')
	->add('bootstrap-colorpicker',            'bundles/engine/admin/js/plugins/colorpicker/bootstrap-colorpicker.js')
	->add('chosen',                           'bundles/engine/admin/js/plugins/chosen/chosen.jquery.min.js')
	->add('multi-select',                     'bundles/engine/admin/js/plugins/multiselect/jquery.multi-select.js')
	->add('ckeditor',                         'bundles/engine/admin/js/plugins/ckeditor/ckeditor.js')
	->add('plupload',                         'bundles/engine/admin/js/plugins/plupload/plupload.full.js')
	->add('plupload-queue',                   'bundles/engine/admin/js/plugins/plupload/jquery.plupload.queue.js')
	->add('mockjax',                          'bundles/engine/admin/js/plugins/mockjax/jquery.mockjax.js')
	->add('select2',                          'bundles/engine/admin/js/plugins/select2/select2.min.js')
	->add('colorbox',                         'bundles/engine/admin/js/plugins/colorbox/jquery.colorbox-min.js')
	->add('icheck',                           'bundles/engine/admin/js/plugins/icheck/jquery.icheck.min.js')
	->add('Jcrop',                            'bundles/engine/admin/js/plugins/jcrop/jquery.Jcrop.js')
	->add('validation',                       'bundles/engine/admin/js/plugins/validation/jquery.validate.min.js')
	->add('validation-additional',            'bundles/engine/admin/js/plugins/validation/additional-methods.min.js')
	->add('touch-punch',                      'bundles/engine/admin/js/plugins/touch-punch/jquery.touch-punch.min.js')
	->add('dynatree',                         'bundles/engine/admin/js/plugins/dynatree/min/jquery.dynatree.min.js')

	/* @todo Добавить проверку языка и подключать следующий файл только когда выбран русский язык */

	->add('validation-ru',                    'bundles/engine/admin/js/plugins/validation/messages_ru.js')
	->add('quicksearch',                      'bundles/engine/admin/js/plugins/quicksearch/jquery.quicksearch.js')

	->add('admin',                            'bundles/engine/admin/js/admin.js')
	->add('eakroko',                          'bundles/engine/admin/js/eakroko.js')
	->add('application',                      'bundles/engine/admin/js/application.min.js')

	//Jquery Fileupload
	->add('jquery_ui_widget', 'bundles/engine/admin/js/plugins/jquery_fileupload/jquery.ui.widget.js')
	->add('iframe-transport', 'bundles/engine/admin/js/plugins/jquery_fileupload/jquery.iframe-transport.js')
	->add('jquery_fileupload', 'bundles/engine/admin/js/plugins/jquery_fileupload/jquery.fileupload.js')

	//filter_toggle
	->add('filter_toggle', 'bundles/engine/admin/js/filter_toggle.js')

	// App specific assets
	// ->add('asi-admin.js', 'js/admin/scripts.js')

;
