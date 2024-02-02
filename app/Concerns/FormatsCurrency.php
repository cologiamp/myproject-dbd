<?php

namespace App\Concerns;

trait FormatsCurrency{


    /**
     * Formats currency as required
     * @param string $amount
     * @return int
     */
    private function currencyStringToInt(string $amount): int
    {
        return (int)preg_replace('/\D/u','',$amount);
    }

}
