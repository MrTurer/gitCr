<?php

namespace RNS\Integrations\Models;

class UserMapItem
{
    /** @var int */
    private $internalId;
    /** @var string|null */
    private $externalId;

     /**
     * @return int
     */
    public function getInternalId(): int
    {
        return $this->internalId;
    }

    /**
     * @param int $internalId
     * @return UserMapItem
     */
    public function setInternalId(int $internalId): UserMapItem
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
