<?php

namespace RNS\Integrations\Models;

/**
 * Настройки для интеграции посредством API.
 * @package RNS\Integrations\Models
 */
class ApiOptions extends OptionsBase implements \JsonSerializable
{
    /** @var string */
    private $endpoint;
    /** @var string */
    private $username;
    /** @var string */
    private $password;

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), get_object_vars($this));
    }
}
