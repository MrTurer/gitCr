<?php

namespace RNS\Integrations\Models;

class UserMap
{
    /** @var string|null */
    private $defaultExternalEmail;
    /** @var bool */
    private $ignoreAliens = false;
    /** @var UserMapItem[] */
    private $items = [];

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

    public function getItemByInternalId(string $id)
    {
        foreach ($this->items as $item) {
            if ($item->getInternalId() == $id) {
                return $item;
            }
        }
        return null;
    }

    public function getItemByExternalId(string $id)
    {
        foreach ($this->items as $item) {
            if ($item->getExternalId() == $id) {
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
