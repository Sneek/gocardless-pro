<?php

namespace GoCardless\Pro\Models\Traits;

trait Factory
{
    /**
     * @param $details
     *
     * @return static
     */
    public static function fromArray($details)
    {
        $model = new static;

        foreach ($details as $attribute => $value) {
            if (property_exists($model, $attribute)) {
                $model->{$attribute} = $value;
            }
        }

        return $model;
    }
}