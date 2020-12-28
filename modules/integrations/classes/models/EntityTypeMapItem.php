<?php

namespace RNS\Integrations\Models;

class EntityTypeMapItem
{
    /** @var int */
    private $externalTypeId;
    /** @var int */
    private $internalTypeId;

    /**
     * @return int
     */
    public function getExternalTypeId(): int
    {
        return $this->externalTypeId;
    }

    /**
     * @param int $externalTypeId
     * @return EntityTypeMapItem
     */
    public function setExternalTypeId(int $externalTypeId): EntityTypeMapItem
    {
        $this->externalTypeId = $externalTypeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getInternalTypeId(): int
    {
        return $this->internalTypeId;
    }

    /**
     * @param int $internalTypeId
     * @return EntityTypeMapItem
     */
    public function setInternalTypeId(int $internalTypeId): EntityTypeMapItem
    {
        $this->internalTypeId = $internalTypeId;
        return $this;
    }
}
