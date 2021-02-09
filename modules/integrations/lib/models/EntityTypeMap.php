<?php

namespace RNS\Integrations\Models;

class EntityTypeMap
{
    /** @var int|null */
    private $defaultProjectId;
    /** @var int|null */
    private $defaultTypeId;
    /** @var EntityTypeMapItem[] */
    private $items = [];

    /**
     * @return int|null
     */
    public function getDefaultProjectId(): ?int
    {
        return $this->defaultProjectId;
    }

    /**
     * @param int|null $defaultProjectId
     * @return EntityTypeMap
     */
    public function setDefaultProjectId(?int $defaultProjectId): EntityTypeMap
    {
        $this->defaultProjectId = $defaultProjectId;
        return $this;
    }

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

    /**
     * @param mixed $id
     * @param mixed $projectId
     * @return EntityTypeMapItem|null
     */
    public function getItemByExternalTypeId($id, $projectId = null)
    {
        foreach ($this->items as $item) {
            if ($item->getExternalTypeId() == $id && (!$projectId || $projectId == $item->getExternalProjectId())) {
                return $item;
            }
        }
        return null;
    }

    public function addItem($projectId, $typeId)
    {
        $item = new EntityTypeMapItem();
        $item->setExternalProjectId($projectId);
        $item->setExternalTypeId($typeId);
        $this->items[] = $item;
    }
}
