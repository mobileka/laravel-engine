<?php

use \Mobileka\L3\Engine\Laravel\Uploader;

IoC::register('Uploader', function(){
	return new Uploader;
});