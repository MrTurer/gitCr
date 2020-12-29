<?php

namespace RNS\Integrations\Processors\database\pgsql;

use RNS\Integrations\Models\Mapping;
use RNS\Integrations\Models\OptionsBase;
use RNS\Integrations\Processors\DataProviderBase;

class DataProvider extends DataProviderBase
{
    private $conn;

    public function __construct(OptionsBase $options, Mapping $mapping)
    {
        parent::__construct($options, $mapping);
    }

    public function isAvailable()
    {
        return extension_loaded('pgsql');
    }

    public function getProjects()
    {
        if (!$this->isAvailable()) {
            return [];
        }
        $this->connect();

        $map = $this->mapping->getProjectMap();
        $tableName = $map->getSrcElementName();
        $keyField = $map->getKeyAttrName();
        $displayField = $map->getDisplayAttrName();

        $displayField = explode(',', $displayField);

        if (count($displayField) > 1) {
            $displayField = implode("|| ', ' ||", $displayField);
        } else {
            $displayField = $displayField[0];
        }

        $sql = "select {$keyField} as id, {$displayField} as name from {$tableName}";

        $res = pg_query($this->conn, $sql);

        $data = [];
        while ($row = pg_fetch_assoc($res)) {
            $data[$row['id']] = $row['name'];
        }

        $this->disconnect();

        return $data;
    }

    public function getEntities()
    {
        if (!$this->isAvailable()) {
            return [];
        }

        return [];
    }

    public function getUsers()
    {
        if (!$this->isAvailable()) {
            return [];
        }
        $this->connect();

        $map = $this->mapping->getUserMap();
        $tableName = $map->getSrcElementName();
        $keyField = $map->getKeyAttrName();
        $displayField = $map->getDisplayAttrName();

        $displayField = explode(',', $displayField);

        if (count($displayField) > 1) {
            $displayField = implode(" || ', ' || ", $displayField);
        } else {
            $displayField = $displayField[0];
        }

        $sql = "select {$keyField} as id, {$displayField} as name from {$tableName}";

        $res = pg_query($this->conn, $sql);

        $data = [];
        while ($row = pg_fetch_assoc($res)) {
            $data[$row['id']] = $row['name'];
        }

        $this->disconnect();

        return $data;
    }

    private function connect()
    {
        $connStr = "host={$this->options->getHostName()} port={$this->options->getPort()} dbname={$this->options->getDatabaseName()} user={$this->options->getUserName()} password={$this->options->getPassword()}";
        $this->conn = pg_connect($connStr);
    }

    private function disconnect()
    {
        pg_close($this->conn);
    }
}
