<?php

namespace RNS\Integrations\Models;

class AttributeMap implements \JsonSerializable
{
    /** @var string|null */
    private $sourceElementName;
    /** @var string|null */
    private $keyAttrName;
    /** @var AttributeMapItem[] */
    private $items = [];

    /**
     * @return string|null
     */
    public function getSourceElementName(): ?string
    {
        return $this->sourceElementName;
    }

    /**
     * @param string|null $sourceElementName
     * @return AttributeMap
     */
    public function setSourceElementName(?string $sourceElementName): AttributeMap
    {
        $this->sourceElementName = $sourceElementName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeyAttrName(): ?string
    {
        return $this->keyAttrName;
    }

    /**
     * @param string|null $keyAttrName
     * @return AttributeMap
     */
    public function setKeyAttrName(?string $keyAttrName): AttributeMap
    {
        $this->keyAttrName = $keyAttrName;
        return $this;
    }

    /**
     * @return AttributeMapItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param AttributeMapItem[] $items
     * @return AttributeMap
     */
    public function setItems(array $items): AttributeMap
    {
        $this->items = $items;
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
