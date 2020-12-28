<?php

namespace RNS\Integrations\Models;

class EntityStatusMap
{
    /** @var int|null */
    private $defaultTypeId;
    /** @var int|null */
    private $defaultStatusId;
    /** @var EntityStatusMapItem[] */
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
     * @return EntityStatusMap
     */
    public function setDefaultTypeId(?int $defaultTypeId): EntityStatusMap
    {
        $this->defaultTypeId = $defaultTypeId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDefaultStatusId(): ?int
    {
        return $this->defaultStatusId;
    }

    /**
     * @param int|null $defaultStatusId
     * @return EntityStatusMap
     */
    public function setDefaultStatusId(?int $defaultStatusId): EntityStatusMap
    {
        $this->defaultStatusId = $defaultStatusId;
        return $this;
    }

    /**
     * @return EntityStatusMapItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param EntityStatusMapItem[] $items
     * @return EntityStatusMap
     */
    public function setItems(array $items): EntityStatusMap
    {
        $this->items = $items;
        return $this;
    }
}
