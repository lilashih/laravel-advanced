<?php

namespace App\Services\Pipelines\Hub\Pipes;

interface IQuery
{
    public function handle($string, \Closure $next);
}
