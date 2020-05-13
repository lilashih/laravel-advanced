<?php

namespace App\Services\Pipeline\Hub;

use Illuminate\Pipeline\Hub;

abstract class CoreHub extends Hub
{
    /**
     * Send an object through one of the available pipelines.
     *
     * @param  mixed  $object
     * @param  string|null  $pipeline
     * @return mixed
     */
    public function pipe($object, $pipeline = null)
    {
        // If a pipeline was issued but it wasn't created, we will call the method to create it
        if ($pipeline && ! isset($this->pipelines[$pipeline])) {
            $this->{'register' . ucfirst($pipeline)}();
        }

        return parent::pipe($object, $pipeline);
    }
}
