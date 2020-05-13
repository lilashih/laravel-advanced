<?php

namespace App\Services\Pipelines\Queries\Query1;

class WhereUser extends BaseQuery
{
    public function query($query, array $args)
    {
        $userId = array_shift($args);

        if ($userId) {
            $query = $query->where('user_id', $userId);
        }

        return $query;
    }
}
