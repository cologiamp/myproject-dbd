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
        $amount = (int)preg_replace('/\D/u','',$amount);

        return $amount ;
    }
    /**
     * Formats currency as required
     * @param int $amount
     * @return string
     */
    private function currencyIntToString(int $amount): string
    {
        return '£' . number_format($amount/100,2);
    }

}
