<?php

/*
|----------------------------------------------------------------------
| Create - Mass Bundle Creator for Laravel Engine by Rakhmatulin Daniil
|----------------------------------------------------------------------
|
| Extends from Laravel Generator by Jeffrey Way
|
| Single bundle:
| 	Type command: artisan engine::create:bundle path.to.bundle.bundlename name:type:option[ name:type:option ...][ addmenu:section:item]
|
| Create many bundles from SQL:
| 	Put schema.sql into path('app')/schema
| 	Type command: artisan engine::create:application[ schema_filename][ path_to_bundles]
|
| Write less code, go have beer sooner!
|--------------------------------------
*/

function p($string)
{
	return Str::lower(Str::plural($string));
}

function s($string)
{
	return Str::singular($string);
}

function str_replace_last($search, $replace, $subject)
{
    return preg_replace('~(.*)' . preg_quote($search, '~') . '~', '$1' . $replace, $subject, 1);
}

require Bundle::path('engine') . 'tasks/g.php';

class Engine_Create_Task extends G_Task {

	public function run($arguments)
	{
		echo "
 ---------------------------------------------------------------------
 Create - Mass Bundle Creator for Laravel Engine by Rakhmatulin Daniil
 ---------------------------------------------------------------------

 Extends from Laravel Generator by Jeffrey Way

 Single bundle:
 	Type command: artisan engine::create:bundle path.to.bundle.bundlename name:type:option[ name:type:option ...][ addmenu:section:item]

 Create many bundles from SQL:
 	Put schema.sql into path('app')/schema
 	Type command: artisan engine::create:application[ schema_filename][ path_to_bundles]

 Write less code, go have beer sooner!
 -------------------------------------
";
	}

	public function bundle($args)
	{
		if (empty($args))
		{
			echo "Error: Please provide a name for your bundle.\n";

			return;
		}

		$this->prepareArgs($args);

		$this->makeConfig();
		$this->makeController();
		$this->makeLanguage();
		$this->makeModel();
		$this->makeRoutes();
		$this->makeStart();
		$this->_migration();
		$this->makeForeign($args);
		$this->changeMenuConfig();
		$this->changeBundlesPhp();

		// Laravel\CLI\Command::run(array('migrate'));
	}

	public function application($args)
	{
		$fileName = 'schema.sql';
		$prePath = 'application';

		if (isset($args[0]) and !empty($args[0]))
		{
			$fileName = $args[0];
		}

		if (isset($args[1]) and !empty($args[1]))
		{
			$prePath = $args[1];
		}

		$commandList = $this->prepareSql($fileName, $prePath);

		foreach ($commandList as $i => $command)
		{
			array_unshift($command, 'engine::create:bundle');
			Command::run($command);

			echo "-----------------------------------------------------\n";
		}

		// sleep(1);

		foreach ($commandList as $i => $command)
		{
			$this->makeForeign($command);
		}

		$result = "<?php\n\nreturn ".var_export(Config::get('menu'), true).';';
		file_put_contents(path('app').'config'.DS.'menu'.EXT, $result, LOCK_EX);

		// Command::run(array('migrate'));
	}

	protected $prePath = '';
	protected $bundlePath = '';

	protected $name = '';
	protected $Name = '';
	protected $names = '';
	protected $Names = '';

	protected $fields = array();
	protected $args = array();

	protected $menu = array();
	protected $addMenuCommand = 'addmenu';
	protected $defaultMenuIcon = 'glyphicon-chevron-right';
	protected $section = 'Application';
	protected $label;

	protected $menuDeafultSection = 'Application';

	protected $formPrefix = 'form';
	protected $gridPrefix = 'grid';
	protected $filterPrefix = 'filter';

	protected $config =
"<?php

use #uFORM#;

use	#uGRID#;

use #uFILTERS#;

#RELATIONS#
return array(
	'form' => array(
		'components' => array(
#FORM#
		),
	),

	'grid' => array(
		'components' => array(
#GRID#
		),

		'filters' => array(
#FILTERS#
		),
	),
);";

	protected $controller =
'<?php

use Mobileka\L3\Engine\Laravel\Base\BackendController;

class #BUNDLE#_Admin_Default_Controller extends backendController {

	public function __construct()
	{
		$this->model = IoC::resolve("#Name#Model");
		parent::__construct();
	}
}';

	protected $language =
"<?php

return array(
	'controllers' => array(
		'admin' => array(
			'default' => array(
				'titles' => array(
					'index' => '#INDEX#',
					'add' => '#ADD#',
					'edit' => '#EDIT#',
				)
			)
		)
	),

	'labels' => array(
#LABELS#
	),

	'form' => array(),
	'grid' => array(),
);";

	protected $model =
'<?php namespace #BUNDLE#\Models;

use Mobileka\L3\Engine\Laravel\Base\Model,
	Laravel\IoC;

class #MODEL# extends Model {

	public static $table = \'#TABLE#\';

	public static $rules = array(
#RULES#
	);

#RELATIONS#
}';

	protected $routes =
"<?php

RestfulRouter::make()->except('view')->resource(array('submodule' => 'admin', 'bundle' => '#BUNDLE#'));";

	protected $start =
"<?php

Autoloader::namespaces(array(
	'#Names#' => Bundle::path('#names#')
));

IoC::register('#Name#Model', function()
{
	return new #Names#\Models\#Name#;
});";

	protected $bundles =
"\t'#names#' => array(
	\t'handles' => '#names#',
	\t'location' => '#Names#',
	\t'auto' => true,
\t),";

	protected $foreign =
'<?php

class #Names#_Add_#Names#_Foreign {

	public function up()
	{
		Schema::table(\'#names#\', function($table) {
#UP#
		});
	}

	public function down()
	{
		Schema::table(\'#names#\', function($table) {
#DOWN#
		});
	}
}';

	protected $components = array();

	protected function prepareArgs($args)
	{
		foreach ($args as $key => $arg)
		{
			if (strpos($arg, $this->addMenuCommand) !== false) // TODO: add to menu builder to single Maker
			{
				$temp = explode(':', $arg);

				if (count($temp) == 3)
				{
					$this->section = urldecode($temp[1]);
					$this->label = urldecode($temp[2]);
				}

				unset($args[$key]);

				break;
			}
		}

		$path = explode('.', $args[0]);

		$name = s(end($path));

		$prePath = '';
		if (count($path) > 1)
		{
			unset($path[count($path)-1]);
			$prePath = join(DS, $path);
		}

		$this->name = Str::lower($name);
		$this->Name = ucfirst($name);
		$this->names = p($name);
		$this->Names = ucfirst(p($name));

		$this->args = $args;

		unset($args[0]);

		$this->prePath = $prePath;
		$this->bundlePath = ($this->prePath) ? path('bundle').$this->prePath.DS.$this->Names.DS : path('bundle').$this->Names.DS;
		$this->fields = $args;

		if ($this->prePath) $this->section = ucfirst(end(explode(DS, $this->prePath)));
		
		$this->label = $this->Names;
	}

	protected function getComponents($path, $namespace)
	{
		$components = array();

		foreach (scandir($path) as $item)
		{
			if ($item != '.' and $item != '..' and $item != 'BaseComponent.php')
			{
				$item = explode('.', $item);

				$components['list'][$item[0]] = $item[0];
			}
		}

		$components['namespace'] = $namespace;

		return $components;
	}

	protected function prepareConfigUseBlock($components, $prefix = '')
	{
		$configUseBlock = '';

		foreach ($components['list'] as $item)
		{
			if (!isset($first))
			{
				$configUseBlock .= $components['namespace'].$item." as $prefix$item,\n";
				$first = true;
			}
			elseif ($item != end($components['list']))
			{
				$configUseBlock .= "\t".$components['namespace'].$item." as $prefix$item,\n";
			}
			else
			{
				$configUseBlock .= "\t".$components['namespace'].$item." as $prefix$item";
			}
		}

		return $configUseBlock;
	}

	protected function prepareIoCRelationsBlock()
	{
		$result = '';

		foreach ($this->fields as $key => $field)
		{
			$field = explode(':', $field);

			if ($field[1] === 'unsigned' and strstr($field[0], '_id'))
			{
				$table = p(substr($field[0], 0, -3));
				$relation = ucfirst(substr($field[0], 0, -3));

				$result .= "\$model = IoC::resolve('".$relation."Model');\n";
				$result .= '$'.$table.' = $model::lists(\'title\', \'id\');'."\n\n";
			}
		}

		return $result;
	}

	protected function prepareConfigRelationsBlock()
	{
		$result = '';

		foreach ($this->fields as $key => $field)
		{
			$field = explode(':', $field);

			if ($field[1] === 'unsigned' and strstr($field[0], '_id'))
			{
				$bundle = ucfirst(p(substr($field[0], 0, -3)));
				$model = ucfirst(substr($field[0], 0, -3));

				$result .= "use $bundle\\models\\$model;\n";
			}
		}

		if (!empty($result))
		{
			$result .= "\n";
		}

		foreach ($this->fields as $key => $field)
		{
			$field = explode(':', $field);

			if ($field[1] === 'unsigned' and strstr($field[0], '_id'))
			{
				$table = p(substr($field[0], 0, -3));
				$rel = ucfirst(substr($field[0], 0, -3));
				$result .= '$'.$table." = $rel::lists('title', 'id');\n";
			}
		}

		return $result;
	}

	protected function prepareConfigFormBlock()
	{
		$result = '';

		foreach ($this->fields as $key => $field)
		{
			$field = explode(':', $field);

			if ($field[1] === 'string')
			{
				$result .= "\t\t\t'$field[0]' => ".$this->formPrefix."Text::make('$field[0]'),\n";
			}
			elseif ($field[1] === 'text')
			{
				$result .= "\t\t\t'$field[0]' => ".$this->formPrefix."CKEditor::make('$field[0]'),\n";
			}
			elseif ($field[1] === 'unsigned' and strstr($field[0], '_id'))
			{
				$table = p(substr($field[0], 0, -3));
				$result .= "\t\t\t'$field[0]' => ".$this->formPrefix."DropdownChosen::make('$field[0]')->options($$table),\n";
			}
			elseif ($field[1] === 'boolean')
			{
				$result .= "\t\t\t'$field[0]' => ".$this->formPrefix."Checkbox::make('$field[0]'),\n";
			}
			elseif ($field[1] === 'date')
			{
				$result .= "\t\t\t'$field[0]' => ".$this->formPrefix."Datepicker::make('$field[0]'),\n";
			}
			elseif ($field[1] === 'decimal')
			{
				$result .= "\t\t\t'$field[0]' => ".$this->formPrefix."Price::make('$field[0]'),\n";
			}
			else
			{
				$result .= "\t\t\t'$field[0]' => ".$this->formPrefix."Text::make('$field[0]'),\n";
			}
		}

		return $result;
	}

	protected function prepareConfigGridBlock()
	{
		$result = '';

		foreach ($this->fields as $key => $field)
		{
			$field = explode(':', $field);

			if ($field[1] === 'boolean')
			{
				$result .= "\t\t\t'$field[0]' => ".$this->gridPrefix."Switcher::make('$field[0]'),\n";
			}
			elseif ($field[1] === 'decimal')
			{
				$result .= "\t\t\t'$field[0]' => ".$this->gridPrefix."PriceColumn::make('$field[0]'),\n";
			}
			elseif ($field[1] === 'date')
			{
				$result .= "\t\t\t'$field[0]' => ".$this->gridPrefix."Date::make('$field[0]'),\n";
			}
			elseif ($field[1] === 'unsigned' and strstr($field[0], '_id'))
			{
				$model = substr($field[0], 0, -3);
				$result .= "\t\t\t'$model.title' => ".$this->gridPrefix."Column::make('$model.title'),\n";
			}
			else
			{
				$result .= "\t\t\t'$field[0]' => ".$this->gridPrefix."Column::make('$field[0]'),\n";
			}
		}

		return $result;
	}

	protected function prepareConfigFiltersBlock()
	{
		$result = '';

		foreach ($this->fields as $key => $field)
		{
			$field = explode(':', $field);

			if ($field[1] === 'date')
			{
				$result .= "\t\t\t'$field[0]' => ".$this->filterPrefix."DateRange::make('$field[0]'),\n";
			}
			elseif ($field[1] === 'unsigned' and strstr($field[0], '_id'))
			{
				$table = p(substr($field[0], 0, -3));
				$result .= "\t\t\t'$field[0]' => ".$this->filterPrefix."DropdownChosen::make('$field[0]')->options($$table),\n";
			}
			else
			{
				$result .= "\t\t\t'$field[0]' => ".$this->filterPrefix."Text::make('$field[0]'),\n";
			}
		}

		return $result;
	}

	protected function prepareLanguageLabelsBlock()
	{
		$result = '';

		foreach ($this->fields as $key => $field)
		{
			$field = explode(':', $field);

			if ($field[1] === 'unsigned' and strstr($field[0], '_id'))
			{
				$model = substr($field[0], 0, -3);
				$result .=
"\t\t'$model' => array(
\t\t\t'title' => 'title of $model',
\t\t),
\t\t'$field[0]' => '$field[0]',\n";
			}
			else
			{
				$result .= "\t\t'$field[0]' => '$field[0]',\n";
			}
		}

		return $result;
	}

	protected function prepareModelRulesBlock()
	{
		$result = '';

		foreach ($this->fields as $key => $field)
		{
			$field = explode(':', $field);

			if ($field[1] === 'unsigned' and strstr($field[0], '_id'))
			{
				$result .= "\t\t'$field[0]' => 'required|integer',\n";
			}

			if (isset($field[2]) and ($field[2] === 'r' or $field[2] === 'required'))
			{
				$result .= "\t\t'$field[0]' => 'required',\n";
			}
		}

		return str_replace_last(",\n", ',', $result);
	}

	protected function prepareModelRelationsBlock()
	{
		$result = '';

		foreach ($this->fields as $key => $field)
		{
			$field = explode(':', $field);

			if ($field[1] === 'unsigned' and strstr($field[0], '_id'))
			{
				$bundle = ucfirst(p(substr($field[0], 0, -3)));
				$rel = substr($field[0], 0, -3);
				$model = ucfirst(substr($field[0], 0, -3));
				$result .=
"\tpublic function $rel()
\t{
\t\treturn ".'$this'."->belongs_to(IoC::resolve('{$model}Model'));
\t}\n";
			}
		}

		return $result;
	}

	protected function makeConfig()
	{
		$path = Bundle::path('engine').'Form'.DS.'Components';
		$namespace = 'Mobileka\L3\Engine\Form\Components\\';
		$this->components['form'] = $this->getComponents($path, $namespace);
		$useBlock = $this->prepareConfigUseBlock($this->components['form'], $this->formPrefix);
		$this->config = str_replace('#uFORM#', $useBlock, $this->config);

		$path = Bundle::path('engine').'Grid'.DS.'Components';
		$namespace = 'Mobileka\L3\Engine\Grid\Components\\';
		$this->components['grid'] = $this->getComponents($path, $namespace);
		$useBlock = $this->prepareConfigUseBlock($this->components['grid'], $this->gridPrefix);
		$this->config = str_replace('#uGRID#', $useBlock, $this->config);

		$path = Bundle::path('engine').'Grid'.DS.'Filters';
		$namespace = 'Mobileka\L3\Engine\Grid\Filters\\';
		$this->components['filters'] = $this->getComponents($path, $namespace);
		$useBlock = $this->prepareConfigUseBlock($this->components['filters'], $this->filterPrefix);
		$this->config = str_replace('#uFILTERS#', $useBlock, $this->config);

		$this->config = str_replace('#FORM#', $this->prepareConfigFormBlock(), $this->config);
		$this->config = str_replace('#GRID#', $this->prepareConfigGridBlock(), $this->config);
		$this->config = str_replace('#FILTERS#', $this->prepareConfigFiltersBlock(), $this->config);

		$this->config = str_replace('#RELATIONS#', $this->prepareIoCRelationsBlock(), $this->config);

		static::$content = $this->config;
		return $this->write_to_file($this->bundlePath.'config'.DS.'default.php');
	}

	protected function makeController()
	{
		$this->controller = str_replace('#BUNDLE#', $this->Names, $this->controller);
		$this->controller = str_replace('#Name#', $this->Name, $this->controller);
		static::$content = $this->controller;
		return $this->write_to_file($this->bundlePath.'controllers'.DS.'admin'.DS.'default.php');
	}

	protected function makeLanguage()
	{
		$this->language = str_replace('#INDEX#', 'Список '.$this->name, $this->language);
		$this->language = str_replace('#ADD#', 'Создать '.$this->name, $this->language);
		$this->language = str_replace('#EDIT#', 'Изменить '.$this->name, $this->language);

		$this->language = str_replace('#LABELS#', $this->prepareLanguageLabelsBlock(), $this->language);

		static::$content = $this->language;
		return $this->write_to_file($this->bundlePath.'language'.DS.'ru'.DS.'default.php');
	}

	protected function makeModel()
	{
		$this->model = str_replace('#BUNDLE#', $this->Names, $this->model);
		$this->model = str_replace('#MODEL#', $this->Name, $this->model);

		$this->model = str_replace('#RULES#', $this->prepareModelRulesBlock(), $this->model);
		$this->model = str_replace('#TABLE#', $this->names, $this->model);
		$this->model = str_replace('#RELATIONS#', $this->prepareModelRelationsBlock(), $this->model);

		static::$content = $this->model;
		return $this->write_to_file($this->bundlePath.'Models'.DS.$this->Name.'.php');
	}

	protected function makeRoutes()
	{
		$this->routes = str_replace('#BUNDLE#', $this->names, $this->routes);

		static::$content = $this->routes;
		return $this->write_to_file($this->bundlePath.'routes.php');
	}

	protected function makeStart()
	{
		$this->start = str_replace('#Names#', $this->Names, $this->start);
		$this->start = str_replace('#Name#', $this->Name, $this->start);
		$this->start = str_replace('#names#', $this->names, $this->start);

		static::$content = $this->start;
		return $this->write_to_file($this->bundlePath.'start.php');
	}

	protected function changeBundlesPhp()
	{
		$this->bundles = str_replace('#names#', $this->names, $this->bundles);

		$location = (empty($this->prePath)) ? $this->Names : $this->prePath.'/'.$this->Names;
		$this->bundles = str_replace('#Names#', $location, $this->bundles);

		$config = file_get_contents(path('app').'bundles.php');

		if (strstr($config, $this->bundles) == false)
		{
			$config = str_replace(');', $this->bundles."\n);", $config);

			if (file_put_contents(path('app').'bundles.php', $config) !== false)
			{
				echo "Add: ".$this->Names." to bundels.php\n";
			}
		}
		else
		{
			echo "Warning: ".$this->Names." already exists at bundels.php\n";
		}
	}

/*=====================================================================================

	MENU

=====================================================================================*/

	protected function changeMenuConfig()
	{
		if (!empty($this->section) and !empty($this->label))
		{
			$hasSection = false;
			$hasLabel = false;

			$this->menu = Config::get('menu');

			foreach ($this->menu['sections'] as $i => $menuSection)
			{
				if ($menuSection['label'] == $this->section)
				{
					$hasSection = true;

					foreach ($menuSection['items'] as $j => $menuItem)
					{
						if ($menuItem['label'] == $this->label)
						{
							$hasLabel = true;

							$this->menu['sections'][$i]['items'][$j]['route'] = $this->names.'_admin_default_index';
							$this->menu['sections'][$i]['items'][$j]['icon'] = $this->defaultMenuIcon;

							$message = "Warning: ".$this->Names." already exists at menu config - changed\n";

							break;
						}
					}

					if (!$hasLabel)
					{
						$item = array(
							'label' => $this->label,
							'route' => $this->names.'_admin_default_index',
							'icon' => $this->defaultMenuIcon,
						);

						$message = "Add: ".$this->Names." to menu config\n";
						array_push($this->menu['sections'][$i]['items'], $item);
					}

					break;
				}
			}

			if (!$hasSection)
			{
				$item = array(
					'label' => $this->section,
					'items' => array(
						array(
							'label' => $this->label,
							'route' => $this->names.'_admin_default_index',
							'icon' => $this->defaultMenuIcon,
						),
					),
				);

				$message = "Add: ".$this->Names." to menu config - new section\n";

				array_push($this->menu['sections'], $item);
			}

			$result = "<?php\n\nreturn ".var_export($this->menu, true).';';

			Config::set('menu', $this->menu);

			file_put_contents(path('app').'config'.DS.'menu'.EXT, $result, LOCK_EX);

			if (isset($message))
			{
				echo $message;
			}
		}
	}

	protected function _migration()
	{
		$args = $this->args;

		$args[0] = str_replace('/', '.', $this->prePath).'.'.$this->Names.'::create_'.$this->names.'_table';

		if (is_dir($this->bundlePath.'migrations'))
		{
			echo "Warning: Migration already exists\n";
		}
		else
		{
			return $this->migration($args);
		}
	}

	protected function makeForeign($args)
	{
		$this->prepareArgs($args);

		$foreign = $this->foreign;

		$foreign = str_replace('#Names#', $this->Names, $foreign);
		$foreign = str_replace('#names#', $this->names, $foreign);

		$up = '';
		$down = '';

        foreach ($args as $arg)
        {
            $arg = explode(':', $arg);

            if (strstr($arg[0], '_id'))
            {
                $table = substr($arg[0], 0, -3);
$up .= "\t\t\t".'$table->foreign(\''.$arg[0].'\')->references(\'id\')->on(\''.p($table).'\')->on_delete(\'cascade\')->on_update(\'cascade\');'."\n";
$down .= "\t\t\t".'$table->drop_foreign(\''.$this->names.'_'.$arg[0].'_foreign\');'."\n";
            }
        }

        if ($up !== '' and $down !== '')
        {
			$foreign = str_replace('#UP#', $up, $foreign);
			$foreign = str_replace('#DOWN#', $down, $foreign);
			static::$content = $foreign;
			unset($foreign);
			return $this->write_to_file($this->bundlePath.'migrations'.DS.date('Y_m_d_His', (time() + 1)).'_add_'.$this->names.'_foreign'.EXT);
        }

	}

	protected function take($string, $from = '`', $to = '`')
	{
		$start = strpos($string, $from);
		$end = strripos($string, $to);

		if ($start !== false and $end !== false)
		{
			$length = $end - $start + 1;
			$value = substr($string, $start, $length);
			$value = str_replace($from, '', $value);
			$value = str_replace($to, '', $value);

			return end(explode('.', $value));
		}

		return false;
	}

	protected function has($haystack, $needle)
	{
		if (strpos($haystack, $needle) !== false)
		{
			return true;
		}

		return false;
	}

	protected function prepareSql($fileName = 'schema.sql', $prePath = 'application')
	{
		$fileName = path('app').'schema'.DS.$fileName;
		$schema = file($fileName, FILE_IGNORE_NEW_LINES);
		$commandList = array();
		$command = -1;

		if ($schema)
		{
			foreach ($schema as $key => $row)
			{
				if ($this->has($row, 'CREATE TABLE'))
				{
					$command++;
					$commandList[$command][0] = $prePath.'.'.$this->take($row);
				}
				elseif ($this->has($row, ' INT') and $this->take($row) != 'id' and !$this->has($this->take($row), '_id'))
				{
					array_push($commandList[$command], $this->take($row).':integer');
				}
				elseif ($this->has($row, 'VARCHAR'))
				{
					array_push($commandList[$command], $this->take($row).':string');
				}
				elseif ($this->has($row, 'TEXT'))
				{
					array_push($commandList[$command], $this->take($row).':text');
				}
				elseif ($this->has($row, 'BOOLEAN'))
				{
					array_push($commandList[$command], $this->take($row).':boolean');
				}
				elseif ($this->has($row, 'DATETIME') and $this->take($row, 'created_at') and $this->take($row, 'updated_at'))
				{
					array_push($commandList[$command], $this->take($row).':date');
				}
				elseif ($this->has($row, 'DECIMAL'))
				{
					array_push($commandList[$command], $this->take($row).':decimal');
				}
				elseif ($this->has($row, ' INT') and $this->has($this->take($row), '_id'))
				{
					array_push($commandList[$command], $this->take($row).':unsigned');
				}
			}

			foreach ($commandList as $key => $value)
			{
				$temp = explode('.', $value[0]);
				$item = ucfirst(end($temp));

				array_push($commandList[$key], 'addmenu:'.$this->menuDeafultSection.':'.$item);
			}

			return $commandList;
		}

		return false;
	}

}
