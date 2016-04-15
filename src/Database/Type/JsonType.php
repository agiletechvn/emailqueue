<?php

namespace EmailQueue\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\StringType;

class JsonType extends StringType
{
    public function toPHP($value, Driver $driver)
    {
        if ($value === null) {
            return;
        }

        return json_decode($value, true);
    }

    public function marshal($value)
    {
        if (is_array($value) || $value === null) {
            return $value;
        }

        return json_decode($value, true);
    }

    public function toDatabase($value, Driver $driver)
    {
        return json_encode($value);
    }

    public function requiresToPhpCast()
    {
        return true;
    }
}
