<?php

Autoloader::namespaces(array(
  'Mobileka\L3\Engine' => '/home/galymzhan/work/lara3/lara3.dev/laravel-engine/engine/src/Mobileka/L3/Engine/',
));

Laravel\Autoloader::$aliases['Bundle'] = 'Laravel\\Bundle';
Laravel\Autoloader::$aliases['Config'] = 'Laravel\\Config';
Laravel\Autoloader::$aliases['Controller'] = 'Mobileka\L3\Engine\Laravel\Base\Controller';
Laravel\Autoloader::$aliases['BaseModel'] = 'Mobileka\L3\Engine\Laravel\Base\Model';
Laravel\Autoloader::$aliases['View'] = 'Base\\View';
Laravel\Autoloader::$aliases[ 'File'] = 'Laravel\\File';
Laravel\Autoloader::$aliases['HTML'] = 'Laravel\\HTML';
Laravel\Autoloader::$aliases['Input'] = 'Laravel\\Input';
Laravel\Autoloader::$aliases['Lang'] = 'Laravel\\Lang';
Laravel\Autoloader::$aliases['URL'] = 'Laravel\\URL';
Laravel\Autoloader::$aliases['Redirect'] = 'Laravel\\Redirect';
Laravel\Autoloader::$aliases['Router'] = 'Laravel\\Routing\\Router';
Laravel\Autoloader::$aliases['Session'] = 'Laravel\\Session';
Laravel\Autoloader::$aliases['Str'] = 'Laravel\\Str';
Laravel\Autoloader::$aliases['Validator'] = 'Laravel\\Validator';
Laravel\Autoloader::$aliases['BaseClass'] = 'Base\\BClass';
Laravel\Autoloader::$aliases['Debug'] = 'Helpers\\Debug';
Laravel\Autoloader::$aliases['Misc'] = 'Helpers\\Misc';
Laravel\Autoloader::$aliases['Arr'] = 'Helpers\\Arr';
Laravel\Autoloader::$aliases['Carbon'] = 'Carbon\\Carbon';
Laravel\Autoloader::$aliases['Sphinx'] = 'Mobileka\\Sphinx\\Sphinx';
