<?php

namespace App\Concerns;

trait FlipsEnums{


    /**
     * Formats currency as required
     * @param string $amount
     * @return int
     */
    private function enumValueByName(string $enum, string $value): string|int
    {
        return array_flip(config('enums.'.$enum))[$value];
    }


}
