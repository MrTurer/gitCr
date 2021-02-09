<?php

namespace RNS\Integrations\Models;

class PropertyMap
{
    /** @var PropertyMapItem[] */
    private $items = [];

    /**
     * @return PropertyMapItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param PropertyMapItem[] $items
     * @return PropertyMap
     */
    public function setItems(array $items): PropertyMap
    {
        $this->items = $items;
        return $this;
    }

    public function getItemByExternalPropertyId($projectId, $typeId, $propertyId)
    {
        foreach ($this->items as $item) {
            if ($item->getExternalProjectId() == $projectId && $item->getExternalTypeId() == $typeId &&
              $item->getExternalPropertyId() == $propertyId) {
                return $item;
            }
        }
        return null;
    }

    public function addItem($projectId, $typeId, $propertyId)
    {
        $item = new PropertyMapItem();
        $item->setExternalProjectId($projectId);
        $item->setExternalTypeId($typeId);
        $item->setExternalPropertyId($propertyId);
        $this->items[] = $item;
    }
}
