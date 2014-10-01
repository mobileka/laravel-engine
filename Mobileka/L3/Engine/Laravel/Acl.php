<?php namespace Mobileka\L3\Engine\Laravel;

use Laravel\Request;
use Laravel\IoC;
use Carbon\Carbon;
use Mobileka\L3\Engine\Laravel\Helpers\Arr;
use Mobileka\L3\Engine\Laravel\Base\Controller;

class Acl
{
    public $aliases = array();
    public $except = array();

    public static function make()
    {
        $self = new static;
        $self->permissions = Config::get('acl.permissions', array());
        $self->defaultResult = Config::get('acl.defaultResult', false);

        foreach (Arr::getItem($self->permissions, 'aliases', array()) as $key => $value) {
            $self->aliases[Router::wildcards($key)] = $value;
        }

        $self->paths = Arr::getItem($self->permissions, 'paths', array());
        $self->route = Controller::$route;

        $self->bundle     = $self->route ? $self->route['bundle']     : '';
        $self->controller = $self->route ? $self->route['controller'] : '';
        $self->action     = $self->route ? $self->route['action']     : '';
        $self->alias      = $self->route ? $self->route['alias']      : '';
        $self->path       = $self->route ? $self->route['uses']       : '';

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
        if (in_array($this->alias, $this->except)) {
            return true;
        }
        /**
         * Проверяет по псевдониму роута или по пути к нему.
         * Под псевдонимом подразумевается значение, задаваемое в 'as' роута,
         * а под путем -- в 'uses' ('users.admin.default@index', например)
         */
        if ($this->checkByAlias($this->alias) or $this->checkByPath($this->path)) {
            return true;
        }

        /**
         * Если доступ был заблокирован, запишем url, чтобы после авторизации вернуться назад.
         */
        Session::put('acl: last_blocked_url', URL::current());

        return false;
    }

    /**
     * Проверяет наличие доступа у текущего пользователя к текущему роуту
     * по псевдониму ('as')
     *
     * @param  string $alias
     * @return bool
     */
    public function checkByAlias($alias)
    {
        $aliases = array_keys($this->aliases);

        //если есть прямое совпадение, то отдаем ему приоритет
        if (in_array($alias, $aliases)) {
            return Arr::haveIntersections(
                static::userAclGroups(),
                $this->aliases[$alias]
            );
        }

        //если прямого совпадения не было, то ищем по регулярным выражениям
        foreach ($aliases as $a) {
            if (preg_match('#^' . $a . '#u', $alias)) {
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
     * @param  string $alias
     * @return bool
     */
    public function checkByPath($path)
    {
        if (isset($this->paths[$path])) {
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
     * @param  string $action
     * @param  string $group
     * @return bool
     */
    public static function can($action, $group = null)
    {
        if ($greenGroups = Arr::getItem(Config::get('acl.actions', array()), $action, array())) {
            $group = static::userAclGroups($group);

            foreach ($greenGroups as $groups) {
                $groups = (is_array($groups)) ? $groups : array($groups);

                foreach ($groups as $groupName) {
                    if (in_array($groupName, $group)) {
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
        foreach (Router::$optional as $key => $pattern) {
            $alias = str_replace($pattern, $key, $alias);
        }

        foreach (Router::$patterns as $key => $pattern) {
            $alias = str_replace($pattern, $key, $alias);
        }

        return $alias;
    }

    public static function isOwnerOfObject($model, $field, $object_id)
    {
        $model = is_string($model) ? new $model : $model;

        if (!$model = $model->find($object_id)) {
            return false;
        }

        return uid() === (int) $model->$field;
    }

    public static function isTooMuchLoginAttempts($username, $ip = null)
    {
        $ip = $ip ?: Request::ip();
        $limit = Config::get('security.allowed_login_attempts', 0);

        return $limit
            ? (int) $limit <= (int) static::getLoginAttempts($username, $ip)
            : false
        ;
    }

    public static function getLoginAttempts($username, $ip = null)
    {
        $ip = $ip ?: Request::ip();

        //Если уже есть неудачные попытки
        if ($model = IoC::resolve('UserLoginAttemptModel')->getByUsernameAndIp($username, $ip)) {
            $last_fail = Carbon::parse($model->last_fail);

            /**
             * Если она не меньше, чем один блокировочный период назад, то
             * продлеваем этот период и добавляем 1 к неудачным попыткам
             */
            if (Carbon::now()->diffInMinutes($last_fail) <= Config::get('security.login_attempts_block_duration', 15)) {
                return $model->attempts;
            }
        }

        //Если неудачных попыток нет или они просрочены, очистить ключ в сессии
        static::clearLoginAttempts($username, $ip);

        return 0;
    }

    public static function incLoginAttempts($username, $ip = null)
    {
        $ip = $ip ?: Request::ip();
        $Attempt = IoC::resolve('UserLoginAttemptModel');

        //Если уже есть неудачные попытки
        if ($model = $Attempt->getByUsernameAndIp($username, $ip)) {
            $last_fail = Carbon::parse($model->last_fail);

            /**
             * Если она не меньше, чем один блокировочный период назад, то
             * продлеваем этот период и добавляем 1 к неудачным попыткам
             */
            if (Carbon::now()->diffInMinutes($last_fail) <= Config::get('security.login_attempts_block_duration', 15)) {
                $attempts = $model->attempts + 1;
                $last_fail = Carbon::now()->toDateTimeString();

                return $model->saveData(compact('attempts', 'last_fail'));
            }
        }

        //Сюда мы попадаем только при первой ошибке или если ошибки просрочены
        static::clearLoginAttempts($username, $ip);
        $attempts = 1;
        $last_fail = Carbon::now()->toDateTimeString();

        return $Attempt->saveData(compact('username', 'ip', 'attempts', 'last_fail'));
    }

    public static function clearLoginAttempts($username, $ip = null)
    {
        $ip = $ip ?: Request::ip();

        return IoC::resolve('UserLoginAttemptModel')->getByUsernameAndIp($username, $ip);
    }

    public function __call($method, $args)
    {
        if ($method == 'except') {
            $arguments = Arr::getItem($args, 0, false);
            $this->except = is_array($arguments) ? $arguments : $args;

            return $this;
        }
    }
}
