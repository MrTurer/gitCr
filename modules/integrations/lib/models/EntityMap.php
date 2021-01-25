<?php

namespace RNS\Integrations\Models;

class EntityMap
{
    /** @var string|null */
    private $srcElementName;
    /** @var string|null */
    private $keyAttrName;
    /** @var string|null */
    private $displayAttrName;
    /** @var int|null */
    private $defaultEntityId;
    /** @var EntityMapItem[] */
    private $items = [];

    /**
     * @return string
     */
    public function getSrcElementName(): ?string
    {
        return $this->srcElementName;
    }

    /**
     * @param string|null $srcElementName
     * @return EntityMap
     */
    public function setSrcElementName(?string $srcElementName): EntityMap
    {
        $this->srcElementName = $srcElementName;
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
     * @return EntityMap
     */
    public function setKeyAttrName(?string $keyAttrName): EntityMap
    {
        $this->keyAttrName = $keyAttrName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDisplayAttrName(): ?string
    {
        return $this->displayAttrName;
    }

    /**
     * @param string|null $displayAttrName
     * @return EntityMap
     */
    public function setDisplayAttrName(?string $displayAttrName): EntityMap
    {
        $this->displayAttrName = $displayAttrName;
        return $this;
    }

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
