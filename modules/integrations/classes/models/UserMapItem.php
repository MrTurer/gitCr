<?php

namespace RNS\Integrations\Models;

class UserMapItem
{
    /** @var string */
    private $internalId;
    /** @var string|null */
    private $externalId;

     /**
     * @return string
     */
    public function getInternalId(): string
    {
        return $this->internalId;
    }

    /**
     * @param string $internalId
     * @return UserMapItem
     */
    public function setInternalId(string $internalId): UserMapItem
    {
        $this->internalId = $internalId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /**
     * @param string|null $externalId
     * @return UserMapItem
     */
    public function setExternalId(?string $externalId): UserMapItem
    {
        $this->externalId = $externalId;
        return $this;
    }
}
