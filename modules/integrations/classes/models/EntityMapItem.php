<?php

namespace RNS\Integrations\Models;

class EntityMapItem
{
    /** @var string */
    private $externalEntityId;
    /** @var int|null */
    private $internalEntityId;

    /**
     * @return string
     */
    public function getExternalEntityId(): string
    {
        return $this->externalEntityId;
    }

    /**
     * @param string $externalEntityId
     * @return EntityMapItem
     */
    public function setExternalEntityId(string $externalEntityId): EntityMapItem
    {
        $this->externalEntityId = $externalEntityId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInternalEntityId(): ?int
    {
        return $this->internalEntityId;
    }

    /**
     * @param int|null $internalEntityId
     * @return EntityMapItem
     */
    public function setInternalEntityId(?int $internalEntityId): EntityMapItem
    {
        $this->internalEntityId = $internalEntityId;
        return $this;
    }
}
