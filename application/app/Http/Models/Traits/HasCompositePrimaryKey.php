<?php

namespace App\Http\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasCompositePrimaryKey
{
    public function getIncrementing()
    {
        return false;
    }

    protected function setKeysForSaveQuery(Builder $query)
    {
        foreach ($this->getKeyName() as $key) {
            if (isset($this->$key))
                $query->where($key, '=', $this->$key);
            else
                throw new Exception(__METHOD__ . 'Primary key hilang: ' . $key);
        }
        return $query;
    }

    protected static function find($id, $columns = ['*'])
    {
        $me = new self;
        $query = $me->newQuery();
        $i=0;
        foreach ($me->getKeyName() as $key) {
            $query->where($key, '=', $id[$i]);
            $i++;
        }
        return $query->first();
    }

    protected static function findOrFail($id, $columns = ['*'])
    {
        $first = self::find($id);
        if($first == null)
            abort(404);
        else
            return $first;
    }
}