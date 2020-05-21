<?php

namespace App\Services\Pipeline\Hub;

use App\Services\Pipeline\Hub\Pipes\RemoveACharacter;
use App\Services\Pipeline\Hub\Pipes\RemoveOCharacter;

class ArticleHub extends BaseHub
{
    /**
     * Registers the Body Pipeline
     *
     * @return void
     */
    protected function registerBody()
    {
        $this->pipeline('body', function ($pipeline, $object) {
            return $pipeline->send($object)
                ->through([
                    RemoveOCharacter::class,
                    function($string, $next) {
                        return $next(ucfirst($string));
                    }
                ])
                ->thenReturn();
        });
    }

    /**
     * Registers the Title Pipeline
     *
     * @return void
     */
    protected function registerTitle()
    {
        $this->pipeline('title', function ($pipeline, $object) {
            return $pipeline->send($object)
                ->through([
                    RemoveACharacter::class,
                    RemoveOCharacter::class,
                    function($string, $next) {
                        return $next(strtolower($string));
                    }
                ])
                ->thenReturn();
        });
    }
}
