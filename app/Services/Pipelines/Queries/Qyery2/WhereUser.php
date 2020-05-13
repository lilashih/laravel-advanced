<?php

namespace App\Services\Pipelines\Queries\Query2;

class WhereUser implements IQuery
{
    public $userId;

    public function __construct($userId = 0)
    {
        $this->userId = $userId;
    }

    public function handle($query, \Closure $next)
    {
        if ($this->userId) {
            $query = $query->where('user_id', $this->userId);
        }

        return $next($query);
    }
}
