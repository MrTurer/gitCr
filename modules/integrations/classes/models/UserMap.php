<?php

namespace RNS\Integrations\Models;

class UserMap implements \JsonSerializable
{
    /** @var string|null*/
    private $srcElementName;
    /** @var string|null */
    private $keyAttrName;
    /** @var string|null */
    private $displayAttrName;
    /** @var UserMapItem[] */
    private $items = [];

    /**
     * @return string|null
     */
    public function getSrcElementName(): ?string
    {
        return $this->srcElementName;
    }

    /**
     * @param string|null $srcElementName
     * @return UserMap
     */
    public function setSrcElementName(?string $srcElementName): UserMap
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
     * @return UserMap
     */
    public function setKeyAttrName(?string $keyAttrName): UserMap
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
     * @return UserMap
     */
    public function setDisplayAttrName(string $displayAttrName): UserMap
    {
        $this->displayAttrName = $displayAttrName;
        return $this;
    }

    /**
     * @return UserMapItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param UserMapItem[] $items
     * @return UserMap
     */
    public function setItems(array $items): UserMap
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
