<?php

namespace RNS\Integrations\Models;

/**
 * Настройки лоя интеграции путем обмена файлами.
 * @package RNS\Integrations\Models
 */
class FilesOptions extends OptionsBase implements \JsonSerializable
{
    /** @var string */
    private $fileLocation;

    /**
     * @return string
     */
    public function getFileLocation(): string
    {
        return $this->fileLocation;
    }

    /**
     * @param string $fileLocation
     * @return FilesOptions
     */
    public function setFileLocation(string $fileLocation): FilesOptions
    {
        $this->fileLocation = $fileLocation;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), get_object_vars($this));
    }
}
