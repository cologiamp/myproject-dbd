<?php

namespace App\Concerns;

trait GetPrimaryClient{


    /**
     * Formats currency as required
     * @return $client
     */
    private function getPrimaryClient()
    {
        if ($this->clients->count() > 1) {
            return $this->clients()->where('c2_id', '!=', null)->first();
        }

        return $this->clients()->first();
    }
}
