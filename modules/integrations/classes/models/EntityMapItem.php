<?php

namespace RNS\Integrations\Models;

class EntityMapItem implements \JsonSerializable
{
    /** @var string */
    private $srcEntityId;
    /** @var int */
    private $destEntityId;

    /**
     * @return string
     */
    public function getSrcEntityId(): string
    {
        return $this->srcEntityId;
    }

    /**
     * @param string $srcEntityId
     * @return EntityMapItem
     */
    public function setSrcEntityId(string $srcEntityId): EntityMapItem
    {
        $this->srcEntityId = $srcEntityId;
        return $this;
    }

    /**
     * @return int
     */
    public function getDestEntityId(): int
    {
        return $this->destEntityId;
    }

    /**
     * @param int $destEntityId
     * @return EntityMapItem
     */
    public function setDestEntityId(int $destEntityId): EntityMapItem
    {
        $this->destEntityId = $destEntityId;
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
