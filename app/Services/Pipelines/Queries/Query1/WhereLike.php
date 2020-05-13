<?php

namespace App\Services\Pipelines\Queries\Query1;

class WhereLike extends BaseQuery
{
    public function query($query, array $args)
    {
        $column = array_shift($args);
        $content = array_shift($args);

        if ($column && $content) {
            $query = $query->where($column, 'like', "%$content%");
        }

        return $query;
    }
}
