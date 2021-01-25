<?php

namespace RNS\Integrations\Models;

class EntityMap
{
    /** @var int|null */
    private $defaultEntityId;
    /** @var EntityMapItem[] */
    private $items = [];

    /**
     * @return int|null
     */
    public function getDefaultEntityId(): ?int
    {
        return $this->defaultEntityId;
    }

    /**
     * @param int|null $defaultEntityId
     * @return EntityMap
     */
    public function setDefaultEntityId(?int $defaultEntityId): EntityMap
    {
        $this->defaultEntityId = $defaultEntityId;
        return $this;
    }

    /**
     * @return EntityMapItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param EntityMapItem[] $items
     * @return EntityMap
     */
    public function setItems(array $items): EntityMap
    {
        $this->items = $items;
        return $this;
    }

    public function getItemByExternalId(string $id)
    {
        foreach ($this->items as $item) {
            if ($item->getExternalEntityId() == $id) {
                return $item;
            }
        }
        return null;
    }

    public function addItem($externalEntityId, $internalEntityId = null)
    {
        $item = new EntityMapItem();
        $item->setExternalEntityId($externalEntityId);
        $item->setInternalEntityId($internalEntityId);
        $this->items[]  = $item;
    }
}
