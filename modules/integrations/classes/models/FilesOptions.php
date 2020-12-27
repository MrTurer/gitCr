<?php

namespace RNS\Integrations\Models;

class FilesOptions extends OptionsBase
{

    public static function createDefault()
    {
        $result = new self;

        return $result;
    }
}
