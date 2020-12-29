<?php

namespace RNS\Integrations\Models;

class EntityStatusMapItem
{
    /** @var int */
    private $externalTypeId;
    /** @var int */
    private $externalStatusId;
    /** @var int */
    private $internalTypeId;
    /** @var int */
    private $internalStatusId;

    /**
     * @return int
     */
    public function getExternalTypeId(): int
    {
        return $this->externalTypeId;
    }

    /**
     * @param int $externalTypeId
     * @return EntityStatusMapItem
     */
    public function setExternalTypeId(int $externalTypeId): EntityStatusMapItem
    {
        $this->externalTypeId = $externalTypeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getExternalStatusId(): int
    {
        return $this->externalStatusId;
    }

    /**
     * @param int $externalStatusId
     * @return EntityStatusMapItem
     */
    public function setExternalStatusId(int $externalStatusId): EntityStatusMapItem
    {
        $this->externalStatusId = $externalStatusId;
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
     * @return EntityStatusMapItem
     */
    public function setInternalTypeId(int $internalTypeId): EntityStatusMapItem
    {
        $this->internalTypeId = $internalTypeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getInternalStatusId(): int
    {
        return $this->internalStatusId;
    }

    /**
     * @param int $internalStatusId
     * @return EntityStatusMapItem
     */
    public function setInternalStatusId(int $internalStatusId): EntityStatusMapItem
    {
        $this->internalStatusId = $internalStatusId;
        return $this;
    }
}
