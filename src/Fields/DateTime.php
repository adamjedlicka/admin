<?php

namespace AdamJedlicka\Luna\Fields;

use Carbon\Carbon;

class DateTime extends Date
{
    /**
     * Returns formated value for API to use
     * Uses ISO 8601 format
     *
     * @param mixed $value
     * @return string
     */
    protected function formatValue($value)
    {
        if (is_null($value)) return null;

        if ($value instanceof Carbon) {
            return $value->toIso8601String();
        } else {
            return (new Carbon($value))->toIso8601String();
        }
    }
}
