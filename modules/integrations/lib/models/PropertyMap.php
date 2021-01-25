<?php

namespace RNS\Integrations\Models;

class PropertyMap
{
    /** @var mixed */
    private $defaultTypeId;
    /** @var mixed */
    private $defaultPropertyId;
    /** @var PropertyMapItem[] */
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
     * @return PropertyMap
     */
    public function setDefaultTypeId($defaultTypeId): PropertyMap
    {
        $this->defaultTypeId = $defaultTypeId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultPropertyId()
    {
        return $this->defaultPropertyId;
    }

    /**
     * @param mixed $defaultPropertyId
     * @return PropertyMap
     */
    public function setDefaultPropertyId($defaultPropertyId): PropertyMap
    {
        $this->defaultPropertyId = $defaultPropertyId;
        return $this;
    }

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
}
