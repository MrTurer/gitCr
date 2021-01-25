<?php

namespace RNS\Integrations\Processors\database\pgsql;

use RNS\Integrations\Helpers\EntityFacade;
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

    /**
     * Возвращает массив сущностей для импорта, у которых еще не проставлен признак того, что она уже сымпортирована.
     * @param string $systemCode
     * @return array
     */
    public function getEntities(string $systemCode)
    {
        $result = [];
        if (!$this->isAvailable()) {
            return $result;
        }

        $srcTableName = $this->mapping->getEntityPropertyMap()->getSourceElementName();
        $prjTableName = $this->mapping->getProjectMap()->getSrcElementName();
        $prjKeyFieldName = $this->mapping->getProjectMap()->getKeyAttrName();
        $refFieldName = $this->mapping->getEntityTypeMap()->getRefPropertyId();
        $isSavedFieldName = $this->moduleOptions['database']['isSavedFieldName'];
        $createdFieldName = $this->moduleOptions['database'][$systemCode]['createdFieldName'];
        $idFieldName = $this->moduleOptions['database'][$systemCode]['idFieldName'];

        $fields = EntityFacade::getExternalEntityProperties($systemCode);
        $fieldNames = implode(', ', $fields['REFERENCE_ID']);

        $sql =
          "select {$fieldNames} from {$srcTableName} t
           inner join {$prjTableName} p on t.{$refFieldName} = p.{$prjKeyFieldName}
           where not t.{$isSavedFieldName}
           order by t.{$createdFieldName} ASC, t.{$idFieldName} ASC";

        $this->connect();

        $res = pg_query($this->conn, $sql);

        while ($row = pg_fetch_assoc($res)) {
            $result[] = $row;
        }

        $this->disconnect();

        return $result;
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

    public function getEntityKeyById(string $systemCode, $id)
    {
        $result = false;
        if (!$this->isAvailable()) {
            return $result;
        }

        $mapping = $this->mapping->getEntityPropertyMap();
        $srcTableName = $mapping->getSourceElementName();
        $keyFieldName = $mapping->getKeyPropertyName();
        $idFieldName = $this->moduleOptions['database'][$systemCode]['idFieldName'];

        $this->connect();

        $sql = "select {$keyFieldName} from {$srcTableName} where {$idFieldName} = {$id}";

        $res = pg_query($this->conn, $sql);

        if ($row = pg_fetch_row($res)) {
            $result = $row[0];
        }

        return $result;
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
