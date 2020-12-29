<?php

namespace RNS\Integrations\Models;

class PropertyMapItem
{
    /** @var int */
    private $externalTypeId;
    /** @var int */
    private $externalPropertyId;
    /** @var int */
    private $internalTypeId;
    /** @var int */
    private $internalPropertyId;

    /**
     * @return int
     */
    public function getExternalTypeId(): int
    {
        return $this->externalTypeId;
    }

    /**
     * @param int $externalTypeId
     * @return PropertyMapItem
     */
    public function setExternalTypeId(int $externalTypeId): PropertyMapItem
    {
        $this->externalTypeId = $externalTypeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getExternalPropertyId(): int
    {
        return $this->externalPropertyId;
    }

    /**
     * @param int $externalPropertyId
     * @return PropertyMapItem
     */
    public function setExternalPropertyId(int $externalPropertyId): PropertyMapItem
    {
        $this->externalPropertyId = $externalPropertyId;
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
     * @return PropertyMapItem
     */
    public function setInternalTypeId(int $internalTypeId): PropertyMapItem
    {
        $this->internalTypeId = $internalTypeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getInternalPropertyId(): int
    {
        return $this->internalPropertyId;
    }

    /**
     * @param int $internalPropertyId
     * @return PropertyMapItem
     */
    public function setInternalPropertyId(int $internalPropertyId): PropertyMapItem
    {
        $this->internalPropertyId = $internalPropertyId;
        return $this;
    }
}
