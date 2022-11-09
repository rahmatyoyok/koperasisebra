<?php

use Illuminate\Container\Container;

if (! function_exists('getNamespace')) 
{
	function getNamespace()
	{
		return Container::getInstance()->getNamespace();
	}
}

if (! function_exists('getLastId')) 
{
	function getLastId($model_name)
	{
		$model = "\\".getNamespace()."Http\Models\\".$model_name;
		return (int)getLastIdByModel(new $model);
	}
}

if (! function_exists('getNextId')) 
{
	function getNextId($model_name)
	{
		return (int)getLastId($model_name)+1;
	}
}

if (! function_exists('getLastIdByModel')) 
{
	function getLastIdByModel($model)
	{
		return (int)$model->max($model->getKeyname());
	}
}

if (! function_exists('getNestedMaxId')) 
{
	function getNestedMaxId($model_name, $primary_key, $foreign_key = array())
	{
		$model = "\\".getNamespace()."Http\Models\\".$model_name;
		return (int)getNestedMaxIdByModel(new $model, $primary_key, $foreign_key);
	}
}

if (! function_exists('getNestedMaxIdByModel')) 
{
	function getNestedMaxIdByModel($model, $primary_key, $foreign_key = array())
	{
		return (int)$model::where($foreign_key)->max($primary_key);
	}
}

if (! function_exists('getNextNestedByModel')) 
{
	function getNextNestedByModel($model, $primary_key, $foreign_key = array())
	{
		return (int)getNestedMaxIdByModel($model, $primary_key, $foreign_key = array())+1;
	}
}

if (! function_exists('getNextNestedMaxId')) 
{
	function getNextNestedMaxId($model_name, $primary_key, $foreign_key = array())
	{
		return (int)getNestedMaxId($model_name, $primary_key, $foreign_key = array())+1;
	}
}

if (! function_exists('insertFromSelectStatement')) 
{
	/**
     * @param string $model
     * @param array $columns
     * @param Builder $select
     * @return bool
     */
	function insertFromSelectStatement($model, $columns, $select)
	{
		/** @var \Illuminate\Database\Query\Builder $query */
        $query = (new $model)->getQuery();
		$sql = "insert into {$query->from} (". implode(', ', $columns) .") {$select->toSql()}";
		return $query->getConnection()->insert($sql, $select->getBindings());
	}
}

