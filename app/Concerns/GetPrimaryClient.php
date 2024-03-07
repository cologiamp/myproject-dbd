<?php

namespace App\Concerns;

trait GetPrimaryClient{


    /**
     * Get model's primary client
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
