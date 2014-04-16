/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.filebrowserBrowseUrl      = '/bundles/admin/js/plugins/ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = '/bundles/admin/js/plugins/ckfinder/ckfinder.html?type=Images';
	config.filebrowserFlashBrowseUrl = '/bundles/admin/js/plugins/ckfinder/ckfinder.html?type=Flash';
	config.filebrowserUploadUrl      = '/bundles/admin/js/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = '/bundles/admin/js/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = '/bundles/admin/js/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

	/* Added after weird behaviour with empty paragraphs began. */
	config.enterMode                 = CKEDITOR.ENTER_BR;
	config.fillEmptyBlocks           = false;
	config.AutoParagraph             = false;
};
