<?php

namespace RNS\Integrations\Models;

class UserMap
{
    /** @var string|null*/
    private $srcElementName;
    /** @var string|null */
    private $keyAttrName;
    /** @var string|null */
    private $displayAttrName;
    /** @var string|null */
    private $defaultExternalEmail;
    /** @var bool */
    private $ignoreAliens = false;
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
     * @return string|null
     */
    public function getDefaultExternalEmail(): ?string
    {
        return $this->defaultExternalEmail;
    }

    /**
     * @param string|null $defaultExternalEmail
     * @return UserMap
     */
    public function setDefaultExternalEmail(?string $defaultExternalEmail): UserMap
    {
        $this->defaultExternalEmail = $defaultExternalEmail;
        return $this;
    }

    /**
     * @return bool
     */
    public function isIgnoreAliens(): bool
    {
        return $this->ignoreAliens;
    }

    /**
     * @param bool $ignoreAliens
     * @return UserMap
     */
    public function setIgnoreAliens(bool $ignoreAliens): UserMap
    {
        $this->ignoreAliens = $ignoreAliens;
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

    public function getInternalItem(string $id)
    {
        foreach ($this->items as $item) {
            if ($item->getInternalId() == $id) {
                return $item;
            }
        }
        return null;
    }

    public function addItem($internalId, $externalId = null)
    {
        $item = new UserMapItem();
        $item->setInternalId($internalId);
        $item->setExternalId($externalId);
        $this->items[]  = $item;
    }
}
