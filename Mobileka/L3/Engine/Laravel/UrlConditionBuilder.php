<?php namespace Mobileka\L3\Engine\Laravel;

use Mobileka\L3\Engine\Laravel\Base\Model;
use Mobileka\L3\Engine\Laravel\Helpers\Misc;
use Mobileka\L3\Engine\Laravel\Helpers\Arr;
use Mobileka\L3\Engine\Laravel\Base\Controller;

class UrlConditionBuilder
{
    public static $registry;

    public $query;
    public $table;
    public $related_models = array();
    public $related_tables = array();
    public $foreign_keys = array();
    public $filters = array();
    public $relation_filters = array();
    public $model;

    public function __call($name, $args)
    {
        if (strpos($name, '_related') !== false) {
            $method = str_replace('_related', '', $name);
            $relation = explode('.', $args[0]);
            $relation = $relation[0];
            $table = $this->related_tables[$relation];

            $conditions = Arr::searchRecursively(
                $this->relation_filters,
                $relation,
                $method,
                array()
            );

            return $this->$method($conditions, $table);
        }
    }

    public static function instance($query = '', $model = '', $relations = array(), $filters = array(), $relation_filters = array())
    {
        if (!static::$registry) {
            static::$registry = static::make($query, $model, $relations, $filters = array(), $relation_filters = array());
        }

        return static::$registry;
    }

    public static function make($query, $model = '', $relations = array(), $filters = array(), $relation_filters = array())
    {
        $self = new static;
        $self->model = $model;
        $self->table = $model->table() . '.';
        $self->query = $query;
        $self->filters = $filters;
        $self->relation_filters = $relation_filters;

        //////////////////////////////////////////////
        // Запишем названия связанных таблиц в массив
        // с ключом, являющимся названием связи.
        // Так же запишем модели и поля, по которым
        // соединяются таблицы.
        //////////////////////////////////////////////

        foreach ($relations as $relation) {
            $rels = explode('.', $relation);
            /** @var Model $model */
            $result = clone $model;

            for ($i = 0, $count = count($rels); $i < $count; $i++) {
                $result = $self->parseRelations($result, $rels[$i]);
            }
        }

        return static::$registry = $self;
    }

    /**
     * Join a master model with a related model
     *
     * @todo this method should be protected but it's difficult to achieve without refactoring
     * @param  string              $relation
     * @return UrlConditionBuilder
     */
    public function join($relation)
    {
        $first_table = $this->table;

        if (is_array($relation) and count($relation) > 1) {
            $this->join($relation[0]);
            $first_table = $this->related_models[$relation[0]]->model->table() . '.';
            $relation = $relation[1];
        } else {
            $relation = (is_array($relation)) ? $relation[0] : $relation;
        }

        $related_table = $this->related_tables[$relation];
        $foreign_key = $this->foreign_keys[$relation];
        $model = $this->related_models[$relation];

        if ($this->tableAlreadyJoined(str_replace('.', '', $related_table))) {
            return $this;
        }

        if (($relation_type = get_class($model)) == 'Laravel\Database\Eloquent\Relationships\Belongs_To') {
            $first_key = $related_table . 'id';
            $second_key = $first_table . $foreign_key;
            $this->query->left_join(str_replace('.', '', $related_table), $first_key, '=' , $second_key);
        } elseif ($relation_type == 'Laravel\Database\Eloquent\Relationships\Has_Many_And_Belongs_To') {
            $pivot_table = Misc::propertyValue($model, 'joining');
            $second_fk = Misc::truthyValue($model->foreign,  Str::singular(Str::lower($relation)) . '_id');

            $master_key = $first_table . 'id';
            $first_key = $pivot_table . '.' . $foreign_key;

            $second_key = $pivot_table . '.' . $second_fk;
            $third_key = $related_table . 'id';

            if (!$this->tableAlreadyJoined($pivot_table)) {
                $this->query->left_join($pivot_table, $first_key, '=' , $master_key);
            }

            $this->query->left_join(str_replace('.', '', $related_table), $third_key, '=', $second_key);
        } else {
            $first_key = $first_table . 'id';
            $second_key =  $related_table . $foreign_key;
            $this->query->left_join(str_replace('.', '', $related_table), $first_key, '=' , $second_key);
        }

        return $this;
    }

    /**
     * Order by x
     *
     * @param  array               $conditions
     * @return UrlConditionBuilder
     */
    public function order_by(array $conditions)
    {
        foreach ($conditions as $column) {
            $table = $this->table;

            $tmp = explode(':', $column);

            $column = $tmp[0];

            $direction = Arr::getItem($tmp, 1, 'asc');

            //проверим, не относится ли сортировка к связанной таблице
            if (strpos($column, '.') !== false) {
                //если относится, то поменяем таблицу
                $column = explode('.', $column);

                if (!$table = Arr::getItem($this->related_tables, $column[0])) {
                    $relation = $column[0];
                    $field = $column[1];
                    $controller = Router::requestId(Controller::$route);

                    throw new \Exception("In order to sort by \"$field\" field of the \"$relation\" relation, you need to add \"$relation\" to \"$controller\" controller's \$with property");
                }

                $column = $column[1];
            }

            $this->query->order_by($table . $column, $direction);
        }

        return $this;
    }

    /**
     * in_array(x): WHERE IN() condition
     *
     * @param  array               $conditions
     * @return UrlConditionBuilder
     */
    public function in($conditions, $table = '')
    {
        $table = $table ? : $this->table;

        foreach ($conditions as $column => $condition) {
            if ($condition != '') {
                $values = (is_array($condition)) ? $condition : explode(',', $condition);
                $this->query->where_in($table . $column, $values);
            }
        }

        return $this;
    }

    /**
     * !in_array(x): WHERE NOT IN() condition
     *
     * @param  array               $conditions
     * @return UrlConditionBuilder
     */
    public function not_in($conditions, $table = '')
    {
        foreach ($conditions as $column => $condition) {
            if ($condition != '') {
                $values = (is_array($condition)) ? $condition : explode(',', $condition);
                $this->query->where_not_in($table . $column, $values);
            }
        }

        return $this;
    }

    /**
     * LIKE 'x%'
     *
     * @param  array               $conditions
     * @return UrlConditionBuilder
     */
    public function ends_with($conditions, $table = '')
    {
        $table = ($table) ? $table : $this->table;

        foreach ($conditions as $column => $condition) {
            if ($condition != '') {
                $this->query->where($table . $column, 'LIKE', $condition . '%');
            }
        }

        return $this;
    }

    /**
     * LIKE '%x'
     *
     * @param  array               $conditions
     * @return UrlConditionBuilder
     */
    public function starts_with($conditions, $table = '')
    {
        $table = ($table) ? $table : $this->table;

        foreach ($conditions as $column => $condition) {
            if ($condition != '') {
                $this->query->where($table . $column, 'LIKE', '%' . $condition);
            }
        }

        return $this;
    }

    /**
     * >= x (More than)
     *
     * @param  array               $conditions
     * @return UrlConditionBuilder
     */
    public function from($conditions, $table = '')
    {
        $table = ($table) ? $table : $this->table;

        foreach ($conditions as $column => $condition) {
            if ($condition != '') {
                $this->query->where($table . $column, '>=', $condition);
            }
        }

        return $this;
    }

    /**
     * <= x (Less than)
     *
     * @param  array               $conditions
     * @return UrlConditionBuilder
     */
    public function to($conditions, $table = '')
    {
        $table = ($table) ? $table : $this->table;

        foreach ($conditions as $column => $condition) {
            if ($condition != '') {
                $this->query->where($table . $column, '<=', $condition);
            }
        }

        return $this;
    }

    /**
     * LIKE '%x%' (Contains a string)
     *
     * @param  array               $conditions
     * @return UrlConditionBuilder
     */
    public function contains($conditions, $table = '')
    {
        $table = ($table) ? $table : $this->table;

        foreach ($conditions as $column => $condition) {
            if ($condition != '') {
                $this->query->where($table . $column, 'LIKE', '%' . $condition . '%');
            }
        }

        return $this;
    }

    /**
     * OR LIKE '%x%' (Contains a string)
     *
     * @param  array               $conditions
     * @return UrlConditionBuilder
     */
    public function or_contains($conditions, $table = '')
    {
        $table = ($table) ? $table : $this->table;

        foreach ($conditions as $column => $condition) {
            if ($condition != '') {
                $this->query->or_where($table . $column, 'LIKE', '%' . $condition . '%');
            }
        }

        return $this;
    }

    /**
     * = x (equals to)
     *
     * @param  array               $conditions
     * @return UrlConditionBuilder
     */
    public function where($conditions, $table = '')
    {
        $table = ($table) ? $table : $this->table;

        foreach ($conditions as $column => $condition) {
            if ($condition != '') {
                $this->query->where($table . $column, '=', $condition);
            }
        }

        return $this;
    }

    /**
     * or = x (or equals to)
     *
     * @param  array               $conditions
     * @return UrlConditionBuilder
     */
    public function or_where($conditions, $table = '')
    {
        $table = ($table) ? $table : $this->table;

        foreach ($conditions as $column => $condition) {
            if ($condition != '') {
                $this->query->or_where($table . $column, '=', $condition);
            }
        }

        return $this;
    }

    /**
     * != x (is not equal to)
     *
     * @param  array               $conditions
     * @return UrlConditionBuilder
     */
    public function not($conditions, $table = '')
    {
        $table = ($table) ? $table : $this->table;

        foreach ($conditions as $column => $condition) {
            if ($condition != '') {
                $this->query->where($table . $column, '!=', $condition);
            }
        }

        return $this;
    }

    public function take($take = 0)
    {
        if ((int) $take !== 0) {
            $this->query->take($take);
        }

        return $this;
    }

    public function skip($skip = 0)
    {
        if ($skip !== 0) {
            $this->query->skip($skip);
        }

        return $this;
    }

    public function group_by(array $fields)
    {
        foreach ($fields as $field) {
            $this->query->group_by($field);
        }

        return $this;
    }

    public function get($columns = '*')
    {
        return $this->query->get($columns);
    }

    public function count()
    {
        return $this->query->count();
    }

    public function first()
    {
        return $this->query->first();
    }

    public function end()
    {
        return $this->query;
    }

    protected function parseRelations($model, $relation)
    {
        $this->related_models[$relation] = $result = $model->{$relation}();
        $this->related_tables[$relation] = $result->model->table() . '.';

        $model = explode('\\', get_class($model));
        $model = end($model);

        $this->foreign_keys[$relation] = Misc::truthyValue($result->foreign, Str::lower($model . '_id'));

        return $result->model;
    }

    /**
     * Check if provided table is already joined
     *
     * @param  string $table
     * @return bool
     */
    protected function tableAlreadyJoined($table)
    {
        $joins = $this->query->joins ? : array();

        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }

        return false;
    }
}
