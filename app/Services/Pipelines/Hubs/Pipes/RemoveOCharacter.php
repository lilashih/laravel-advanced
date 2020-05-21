<?php

namespace App\Services\Pipeline\Hub\Pipes;

class RemoveOCharacter implements IQuery
{
    public function handle($string, \Closure $next)
    {
        $string = str_replace(["o", "O"], "", $string);

        return $next($string);
    }
}
