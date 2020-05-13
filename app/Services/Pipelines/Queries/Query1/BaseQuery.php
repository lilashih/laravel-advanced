<?php

namespace App\Services\Pipelines\Queries\Query1;

abstract class BaseQuery
{
    abstract public function query($query, array $args);

    public function handle($query, \Closure $next, ...$args)
    {
        $args = $this->filter($args);
        $query = $this->query($query, $args);
        return $next($query);
    }

    protected function filter($args)
    {
        return array_filter($args);
    }
}
