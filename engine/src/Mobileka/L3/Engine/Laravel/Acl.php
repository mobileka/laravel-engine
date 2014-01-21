<?php

class Mobileka\L3\Engine\Laravel\Acl extends Mobileka\L3\Engine\Laravel\Base\Bclass {

	public $aliases = array();
	public $except = array();

	public static function make()
	{
		$self = new static;
		$self->permissions = Config::get('acl.permissions', array());
		$self->defaultResult = Config::get('acl.defaultResult', false);

		foreach (Arr::getItem($self->permissions, 'aliases', array()) as $key => $value)
		{
			$self->aliases[Router::wildcards($key)] = $value;
		}

		$self->paths = Arr::getItem($self->permissions, 'paths', array());
		$self->route = Controller::$route;
		$self->bundle = $self->route['bundle'];
		$self->controller = $self->route['controller'];
		$self->action = $self->route['action'];
		$self->alias = $self->route['alias'];
		$self->path = $self->route['uses'];

		return $self;
	}

	/**
	 * Проверяет наличие доступа у текущего пользователя к текущему роуту
	 *
	 * @return bool
	 */
	public function check()
	{
		/**
		 * Если текущий роут есть в исключениях, то возврашает true
		 */
		if (in_array($this->alias, $this->except))
		{
			return true;
		}
		/**
		 * Проверяет по псевдониму роута или по пути к нему.
		 * Под псевдонимом подразумевается значение, задаваемое в 'as' роута,
	 	 * а под путем -- в 'uses' ('users.admin.default@index', например)
		 */
		if ($this->checkByAlias($this->alias) or $this->checkByPath($this->path))
		{
			return true;
		}

		/**
		 * Если доступ был заблокирован, запишем url, чтобы после авторизации вернуться назад.
		 */
		\Session::put('acl: last_blocked_url', \URL::current());

		return false;
	}

	/**
	 * Проверяет наличие доступа у текущего пользователя к текущему роуту
	 * по псевдониму ('as')
	 *
	 * @param string $alias
	 * @return bool
	 */
	public function checkByAlias($alias)
	{
		$aliases = array_keys($this->aliases);

		//если есть прямое совпадение, то отдаем ему приоритет
		if (in_array($alias, $aliases))
		{
			return Arr::haveIntersections(
				static::userAclGroups(),
				$this->aliases[$alias]
			);
		}

		//если прямого совпадения не было, то ищем по регулярным выражениям
		foreach ($aliases as $a)
		{
			if (preg_match('#^' . $a . '#u', $alias))
			{
				return Arr::haveIntersections(
					static::userAclGroups(),
					$this->aliases[$a]
				);
			}
		}

		return $this->defaultResult;
	}

	/**
	 * Проверяет наличие доступа у текущего пользователя к текущему роуту
	 * по пути ('uses')
	 *
	 * @param string $alias
	 * @return bool
	 */
	public function checkByPath($path)
	{
		if (isset($this->paths[$path]))
		{
			return Arr::haveIntersections(
				static::userAclGroups(),
				$this->paths[$path]
			);
		}

		return $this->defaultResult;
	}

	/**
	 * Проверяет наличие доступа у группы пользователей
	 * к определенному действию, описанному в acl.permissions конфиге
	 *
	 * @param string $action
	 * @param string $group
	 * @return bool
	 */
	public static function can($action, $group = null)
	{
		if ($action = Arr::getItem(Config::get('acl.actions', array()), $action, array()))
		{
			$allow = Arr::getItem($action, 'allow', array());
			$deny = Arr::getItem($action, 'deny', array());
			$group = static::userAclGroups($group);

			/**
			 * Если группа есть в списке запрета, то сразу вернуть false
			 */
			foreach ($deny as $groupName)
			{
				if (in_array($groupName, $group))
				{
					return false;
				}
			}

			foreach ($allow as $groups)
			{
				$groups = (is_array($groups)) ? $groups : array($groups);

				foreach ($groups as $groupName)
				{
					if (in_array($groupName, $group))
					{
						return true;
					}
				}
			}
		}

		return Config::get('acl.defaultResult', false);
	}

	public static function userAclGroups($group = null)
	{
		$group = ($group) ? $group : group();

		return array(
			$group,
			'*',
			'(:any)',
			'(:all)'
		);
	}

	public function wildcardToLaravel($alias)
	{
		foreach (Router::$optional as $key => $pattern)
		{
			$alias = str_replace($pattern, $key, $alias);
		}

		foreach (Router::$patterns as $key => $pattern)
		{
			$alias = str_replace($pattern, $key, $alias);
		}

		return $alias;
	}

	public function __call($method, $args)
	{
		if ($method == 'except')
		{
			$arguments = Arr::getItem($args, 0, false);
			$this->except =  is_array($arguments) ? $arguments : $args;
			return $this;
		}
	}
}