<?php

namespace App\Services\Pipeline\Hub\Pipes;

class RemoveACharacter implements IQuery
{
    public function handle($string, \Closure $next)
    {
        $string = str_replace(["a", "A"],"",$string);

        return $next($string);
    }
}
