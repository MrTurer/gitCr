<?php

namespace RNS\Integrations\Models;

class ApiOptions extends OptionsBase
{
    /** @var string */
    private $endpoint;
    /** @var string */
    private $username;
    /** @var string */
    private $password;

    public static function createDefault()
    {
        $result = new self;

        return $result;
    }
}
