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

    public function getProjects()
    {
        $this->connect();

        $map = $this->mapping->getProjectMap();
        $tableName = $map->getSrcElementName();
        $keyField = $map->getKeyAttrName();
        $displayField = $map->getDisplayAttrName();

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

    }

    public function getUsers()
    {

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
