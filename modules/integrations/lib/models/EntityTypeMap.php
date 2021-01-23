<?php

namespace RNS\Integrations\Models;

class EntityTypeMap
{
    /** @var int|null */
    private $defaultTypeId;
    /** @var mixed */
    private $refPropertyId;
    /** @var EntityTypeMapItem[] */
    private $items = [];

    /**
     * @return int|null
     */
    public function getDefaultTypeId(): ?int
    {
        return $this->defaultTypeId;
    }

    /**
     * @param int|null $defaultTypeId
     * @return EntityTypeMap
     */
    public function setDefaultTypeId(?int $defaultTypeId): EntityTypeMap
    {
        $this->defaultTypeId = $defaultTypeId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRefPropertyId()
    {
        return $this->refPropertyId;
    }

    /**
     * @param mixed $refPropertyId
     * @return EntityTypeMap
     */
    public function setRefPropertyId($refPropertyId)
    {
        $this->refPropertyId = $refPropertyId;
        return $this;
    }

    /**
     * @return EntityTypeMapItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param EntityTypeMapItem[] $items
     * @return EntityTypeMap
     */
    public function setItems(array $items): EntityTypeMap
    {
        $this->items = $items;
        return $this;
    }
}
