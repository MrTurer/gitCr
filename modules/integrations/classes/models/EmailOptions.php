<?php

namespace RNS\Integrations\Models;

class EmailOptions extends OptionsBase
{

    public static function createDefault()
    {
        $result = new self;

        return $result;
    }
}
