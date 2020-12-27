<?php

namespace RNS\Integrations\Models;

class DatabaseOptions extends OptionsBase implements \JsonSerializable
{
    /** @var string */
    private $hostName;
    /** @var int */
    private $port;
    /** @var string */
    private $databaseName;
    /** @var string */
    private $userName;
    /** @var string */
    private $password;

    /**
     * @return string
     */
    public function getHostName(): string
    {
        return $this->hostName;
    }

    /**
     * @param string $hostName
     * @return DatabaseOptions
     */
    public function setHostName(string $hostName): DatabaseOptions
    {
        $this->hostName = $hostName;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     * @return DatabaseOptions
     */
    public function setPort($port): DatabaseOptions
    {
        $this->port = intval($port);
        return $this;
    }

    /**
     * @return string
     */
    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    /**
     * @param string $databaseName
     * @return DatabaseOptions
     */
    public function setDatabaseName(string $databaseName): DatabaseOptions
    {
        $this->databaseName = $databaseName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     * @return DatabaseOptions
     */
    public function setUserName(string $userName): DatabaseOptions
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return DatabaseOptions
     */
    public function setPassword(string $password): DatabaseOptions
    {
        $this->password = $password;
        return $this;
    }

    public static function createDefault()
    {
        $result = new self;

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), get_object_vars($this));
    }
}
