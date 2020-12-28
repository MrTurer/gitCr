<?php

namespace RNS\Integrations\Models;

class UserMapItem
{
    /** @var string */
    private $externalEmail;
    /** @var string */
    private $internalEmail;

    /**
     * @return string
     */
    public function getExternalEmail(): string
    {
        return $this->externalEmail;
    }

    /**
     * @param string $externalEmail
     * @return UserMapItem
     */
    public function setExternalEmail(string $externalEmail): UserMapItem
    {
        $this->externalEmail = $externalEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getInternalEmail(): string
    {
        return $this->internalEmail;
    }

    /**
     * @param string $internalEmail
     * @return UserMapItem
     */
    public function setInternalEmail(string $internalEmail): UserMapItem
    {
        $this->internalEmail = $internalEmail;
        return $this;
    }
}
