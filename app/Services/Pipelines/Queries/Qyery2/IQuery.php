<?php

namespace App\Services\Pipelines\Queries\Query2;

interface IQuery
{
    public function handle($query, \Closure $next);
}
