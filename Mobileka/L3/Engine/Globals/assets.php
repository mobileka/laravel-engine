<?php
//@todo говнокод
$uri = \URI::current();

if ($uri == '')
{
	$uri = '/';
}

\Request::$route = \Router::route(\Request::method(), $uri);

$route = \Request::route()->action;
$alias = \Arr::getItem($route, 'as');

if (\Str::contains($alias, 'admin'))
{
	\Asset::container('plugins')->add('jquery',                           'bower_components/jquery/jquery.min.js');
	\Asset::container('plugins')->add('bootstrap',                        'admin/css/bootstrap.min.css');
	\Asset::container('plugins')->add('bootstrap-responsive',             'admin/css/bootstrap-responsive.min.css');
	\Asset::container('plugins')->add('colorbox',                         'admin/css/plugins/colorbox/colorbox.css');
	\Asset::container('plugins')->add('jquery-ui',                        'admin/css/plugins/jquery-ui/smoothness/jquery-ui.css');
	\Asset::container('plugins')->add('jquery-ui-theme',                  'admin/css/plugins/jquery-ui/smoothness/jquery.ui.theme.css');
	\Asset::container('plugins')->add('pageguide',                        'admin/css/plugins/pageguide/pageguide.css');
	\Asset::container('plugins')->add('fullcalendar',                     'admin/css/plugins/fullcalendar/fullcalendar.css');
	\Asset::container('plugins')->add('fullcalendar-print',               'admin/css/plugins/fullcalendar/fullcalendar.print.css');
	\Asset::container('plugins')->add('tagsinput',                        'admin/css/plugins/tagsinput/jquery.tagsinput.css');
	\Asset::container('plugins')->add('datatable',                        'admin/css/plugins/datatable/TableTools.css');
	\Asset::container('plugins')->add('chosen',                           'admin/css/plugins/chosen/chosen.css');
	\Asset::container('plugins')->add('multiselect',                      'admin/css/plugins/multiselect/multi-select.css');
	\Asset::container('plugins')->add('timepicker',                       'admin/css/plugins/timepicker/bootstrap-timepicker.min.css');
	\Asset::container('plugins')->add('colorpicker',                      'admin/css/plugins/colorpicker/colorpicker.css');
	\Asset::container('plugins')->add('datepicker',                       'admin/css/plugins/datepicker/datepicker.css');
	\Asset::container('plugins')->add('plupload',                         'admin/css/plugins/plupload/jquery.plupload.queue.css');
	\Asset::container('plugins')->add('select2',                          'admin/css/plugins/select2/select2.css');
	\Asset::container('plugins')->add('icheck',                           'admin/css/plugins/icheck/all.css');
	\Asset::container('plugins')->add('jcrop',                            'admin/css/plugins/jcrop/jquery.Jcrop.css');
	\Asset::container('plugins')->add('alertify_core',                    'bower_components/alertify/themes/alertify.core.css');
	\Asset::container('plugins')->add('alertify_theme',                   'bower_components/alertify/themes/alertify.bootstrap.css');

	\Asset::container('custom')->add('style',                             'admin/css/style.css');
	\Asset::container('custom')->add('themes',                            'admin/css/themes.css');
	\Asset::container('custom')->add('admin',                             'admin/css/admin.css');

	//\Asset::add('alertify_styles', 'bower_components/alertify/alertify.css');

	\Asset::container('plugins')->add('bootstrap',                        'admin/js/bootstrap.min.js');
	\Asset::container('plugins')->add('alertify',                         'bower_components/alertify/alertify.min.js');
	\Asset::container('plugins')->add('nicescroll',                       'admin/js/plugins/nicescroll/jquery.nicescroll.min.js');
	\Asset::container('plugins')->add('imagesloaded',                     'admin/js/plugins/imagesLoaded/jquery.imagesloaded.min.js');
	\Asset::container('plugins')->add('ui-core',                          'admin/js/plugins/jquery-ui/jquery.ui.core.min.js');
	\Asset::container('plugins')->add('ui-widget',                        'admin/js/plugins/jquery-ui/jquery.ui.widget.min.js');
	\Asset::container('plugins')->add('ui-mouse',                         'admin/js/plugins/jquery-ui/jquery.ui.mouse.min.js');
	\Asset::container('plugins')->add('ui-resizable',                     'admin/js/plugins/jquery-ui/jquery.ui.resizable.min.js');
	\Asset::container('plugins')->add('ui-sortable',                      'admin/js/plugins/jquery-ui/jquery.ui.sortable.min.js');
	\Asset::container('plugins')->add('ui-ui',                            'admin/js/plugins/jquery-ui/jquery.ui.spinner.js');
	\Asset::container('plugins')->add('ui-ui',                            'admin/js/plugins/jquery-ui/jquery.ui.slider.js');
	\Asset::container('plugins')->add('ui-draggable',                     'admin/js/plugins/jquery-ui/jquery.ui.draggable.min.js');
	\Asset::container('plugins')->add('ui-droppable',                     'admin/js/plugins/jquery-ui/jquery.ui.droppable.min.js');
	\Asset::container('plugins')->add('ui-ui',                            'admin/js/plugins/jquery-ui/jquery.ui.position.js');
	\Asset::container('plugins')->add('ui-spinner',                       'admin/js/plugins/jquery-ui/jquery.ui.spinner.js');
	\Asset::container('plugins')->add('slimscroll',                       'admin/js/plugins/slimscroll/jquery.slimscroll.min.js');
	\Asset::container('plugins')->add('bootbox',                          'admin/js/plugins/bootbox/jquery.bootbox.js');
	\Asset::container('plugins')->add('form',                             'admin/js/plugins/form/jquery.form.min.js');
	\Asset::container('plugins')->add('dataTables',                       'admin/js/plugins/datatable/jquery.dataTables.min.js');
	\Asset::container('plugins')->add('TableTools',                       'admin/js/plugins/datatable/TableTools.min.js');
	\Asset::container('plugins')->add('ColReorder',                       'admin/js/plugins/datatable/ColReorder.min.js');
	\Asset::container('plugins')->add('ColVis',                           'admin/js/plugins/datatable/ColVis.min.js');
	\Asset::container('plugins')->add('columnFilter',                     'admin/js/plugins/datatable/jquery.dataTables.columnFilter.js');
	\Asset::container('plugins')->add('maskedinput',                      'admin/js/plugins/maskedinput/jquery.maskedinput.min.js');
	\Asset::container('plugins')->add('tagsinput',                        'admin/js/plugins/tagsinput/jquery.tagsinput.min.js');
	\Asset::container('plugins')->add('bootstrap-datepicker',             'admin/js/plugins/datepicker/bootstrap-datepicker.js');
	\Asset::container('plugins')->add('bootstrap-datepicker-ru',          'admin/js/plugins/datepicker/locales/bootstrap-datepicker.ru.js');
	\Asset::container('plugins')->add('bootstrap-timepicker',             'admin/js/plugins/timepicker/bootstrap-timepicker.min.js');
	\Asset::container('plugins')->add('bootstrap-colorpicker',            'admin/js/plugins/colorpicker/bootstrap-colorpicker.js');
	\Asset::container('plugins')->add('chosen',                           'admin/js/plugins/chosen/chosen.jquery.min.js');
	\Asset::container('plugins')->add('multi-select',                     'admin/js/plugins/multiselect/jquery.multi-select.js');
	\Asset::container('plugins')->add('ckeditor',                         'admin/js/plugins/ckeditor/ckeditor.js');
	\Asset::container('plugins')->add('plupload',                         'admin/js/plugins/plupload/plupload.full.js');
	\Asset::container('plugins')->add('plupload-queue',                   'admin/js/plugins/plupload/jquery.plupload.queue.js');
	\Asset::container('plugins')->add('bootstrap-fileupload',             'admin/js/plugins/fileupload/bootstrap-fileupload.min.js');
	\Asset::container('plugins')->add('mockjax',                          'admin/js/plugins/mockjax/jquery.mockjax.js');
	\Asset::container('plugins')->add('select2',                          'admin/js/plugins/select2/select2.min.js');
	\Asset::container('plugins')->add('colorbox',                         'admin/js/plugins/colorbox/jquery.colorbox-min.js');
	\Asset::container('plugins')->add('icheck',                           'admin/js/plugins/icheck/jquery.icheck.min.js');
	\Asset::container('plugins')->add('Jcrop',                            'admin/js/plugins/jcrop/jquery.Jcrop.js');
	\Asset::container('plugins')->add('validation',                       'admin/js/plugins/validation/jquery.validate.min.js');
	\Asset::container('plugins')->add('validation-additional',            'admin/js/plugins/validation/additional-methods.min.js');
	/* @todo Добавить проверку языка и подключать следующий файл только когда выбран русский язык */
	\Asset::container('plugins')->add('validation-ru',                    'admin/js/plugins/validation/messages_ru.js');
	\Asset::container('plugins')->add('quicksearch',                      'admin/js/plugins/quicksearch/jquery.quicksearch.js');

	\Asset::container('custom')->add('admin',                             'admin/js/admin.js');
	\Asset::container('custom')->add('eakroko',                           'admin/js/eakroko.js');
	\Asset::container('custom')->add('application',                       'admin/js/application.min.js');
}
else
{
	\Asset::container('plugins')->add('normalize',            'css/normalize.css');
	\Asset::container('plugins')->add('bootstrap',            'css/bootstrap.min.css');
	// \Asset::container('plugins')->add('bootstrap-responsive', 'css/bootstrap-responsive.min.css');
	\Asset::container('plugins')->add('swiper',               'css/vendor/swiper/idangerous.swiper.css');
	\Asset::container('plugins')->add('chosen',               'css/vendor/chosen/chosen.css');
	\Asset::container('plugins')->add('lightbox',             'css/vendor/lightbox/lightbox.css');
	\Asset::container('plugins')->add('alertify_core',        'bower_components/alertify/themes/alertify.core.css');
	\Asset::container('plugins')->add('alertify_theme',       'bower_components/alertify/themes/alertify.bootstrap.css');
	\Asset::container('custom')->add('style',                 'css/style.css');

	\Asset::container('header_plugins')->add('modernizr',     'js/vendor/modernizr-2.6.2.min.js');
	\Asset::container('header_plugins')->add('mikh',          'js/mikh.js');
	\Asset::container('header_plugins')->add('alertify',      'bower_components/alertify/alertify.min.js');

	\Asset::container('footer_plugins')->add('jquery',        'js/vendor/jquery-1.10.2.min.js');
	\Asset::container('footer_plugins')->add('jquery-ui',     'js/vendor/jquery-ui-1.10.3.custom.min.js');
	\Asset::container('footer_plugins')->add('skrollr',       'js/vendor/skrollr.min.js');
	\Asset::container('footer_plugins')->add('gsapCSSPlugin', 'js/vendor/gsap/CSSPlugin.min.js');
	\Asset::container('footer_plugins')->add('gsapEasePack',  'js/vendor/gsap/EasePack.min.js');
	\Asset::container('footer_plugins')->add('gsapTweenLite', 'js/vendor/gsap/TweenLite.min.js');
	\Asset::container('footer_plugins')->add('chosen',        'js/vendor/chosen/chosen.jquery.min.js');
	\Asset::container('footer_plugins')->add('lightbox',      'js/vendor/lightbox2/lightbox-2.6.min.js');
	\Asset::container('footer_plugins')->add('caroufredsel',  'js/vendor/caroufredsel/jquery.carouFredSel-6.2.1-packed.js');
	\Asset::container('footer_plugins')->add('history',       'js/vendor/html5-history/history.min.js');
	\Asset::container('footer_plugins')->add('easyxdm',       \Config::get('chocoauth::config.staticPath') . 'easyxdm/easyXDM.js', 'jquery');
	\Asset::container('footer_plugins')->add('chocoauth',     \Config::get('chocoauth::config.staticPath') . 'chocoaccount.js', 'easyxdm');

	\Asset::container('footer_custom')->add('plugins',       'js/plugins.js');
	\Asset::container('footer_custom')->add('main',          'js/main.js');
}