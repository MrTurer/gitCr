<?php

namespace RNS\Integrations\Models;

class UserMapItem implements \JsonSerializable
{
    /** @var string */
    private $srcEmail;
    /** @var string */
    private $destEmail;

    /**
     * @return string
     */
    public function getSrcEmail(): string
    {
        return $this->srcEmail;
    }

    /**
     * @param string $srcEmail
     * @return UserMapItem
     */
    public function setSrcEmail(string $srcEmail): UserMapItem
    {
        $this->srcEmail = $srcEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestEmail(): string
    {
        return $this->destEmail;
    }

    /**
     * @param string $destEmail
     * @return UserMapItem
     */
    public function setDestEmail(string $destEmail): UserMapItem
    {
        $this->destEmail = $destEmail;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
