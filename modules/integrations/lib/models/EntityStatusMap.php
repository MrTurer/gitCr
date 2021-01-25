<?php

namespace RNS\Integrations\Models;

class EntityStatusMap
{
    /** @var mixed */
    private $defaultTypeId;
    /** @var mixed */
    private $defaultStatusId;
    /** @var EntityStatusMapItem[] */
    private $items = [];

    /**
     * @return mixed
     */
    public function getDefaultTypeId()
    {
        return $this->defaultTypeId;
    }

    /**
     * @param mixed $defaultTypeId
     * @return EntityStatusMap
     */
    public function setDefaultTypeId($defaultTypeId): EntityStatusMap
    {
        $this->defaultTypeId = $defaultTypeId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultStatusId()
    {
        return $this->defaultStatusId;
    }

    /**
     * @param mixed $defaultStatusId
     * @return EntityStatusMap
     */
    public function setDefaultStatusId($defaultStatusId): EntityStatusMap
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

    public function getItemByExternalStatusId($typeId, $statusId)
    {
        foreach ($this->items as $item) {
            if ($item->getExternalTypeId() == $typeId && $item->getExternalStatusId() == $statusId) {
                return $item;
            }
        }
        return null;
    }
}
