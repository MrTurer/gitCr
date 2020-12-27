<?php

namespace RNS\Integrations\Models;

class AttributeMapItem implements \JsonSerializable
{
    /** @var int */
    private $mapTypeId;
    /** @var string|null */
    private $srcAttrName;
    /** @var string|null */
    private $destAttrName;
    /** @var array */
    private $options = [];

    /**
     * @return int
     */
    public function getMapTypeId(): int
    {
        return $this->mapTypeId;
    }

    /**
     * @param int $mapTypeId
     * @return AttributeMapItem
     */
    public function setMapTypeId(int $mapTypeId): AttributeMapItem
    {
        $this->mapTypeId = $mapTypeId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSrcAttrName(): ?string
    {
        return $this->srcAttrName;
    }

    /**
     * @param string $srcAttrName
     * @return AttributeMapItem
     */
    public function setSrcAttrName(?string $srcAttrName): AttributeMapItem
    {
        $this->srcAttrName = $srcAttrName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestAttrName(): ?string
    {
        return $this->destAttrName;
    }

    /**
     * @param string $destAttrName
     * @return AttributeMapItem
     */
    public function setDestAttrName(?string $destAttrName): AttributeMapItem
    {
        $this->destAttrName = $destAttrName;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return AttributeMapItem
     */
    public function setOptions(array $options): AttributeMapItem
    {
        $this->options = $options;
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
