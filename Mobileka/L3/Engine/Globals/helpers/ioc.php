<?php

use \Mobileka\L3\Engine\Laravel\Uploader;

Laravel\IoC::register('Uploader', function(){
	return new Uploader;
});