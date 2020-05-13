<?php

namespace App\Services\Pipeline\Hub\Pipes;

interface IQuery
{
    public function handle($string, \Closure $next);
}
