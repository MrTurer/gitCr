<?php

namespace RNS\Integrations\Processors\database\pgsql;

use RNS\Integrations\Helpers\EntityFacade;
use RNS\Integrations\Models\IntegrationOptions;
use RNS\Integrations\Models\Mapping;
use RNS\Integrations\Models\OptionsBase;
use RNS\Integrations\Processors\DataProviderBase;

class DataProvider extends DataProviderBase
{
    private $conn;

    public function __construct(
        string $systemCode,
        IntegrationOptions $integrationOptions,
        OptionsBase $options,
        Mapping $mapping
    ) {
        parent::__construct($systemCode, $integrationOptions, $options, $mapping);
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

        $tableName = $this->integrationOptions->getProjectSource();
        $keyField = $this->integrationOptions->getProjectKeyField();
        $displayField = $this->integrationOptions->getProjectDisplayField();

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
     * @return array
     */
    public function getEntities()
    {
        $result = [];
        if (!$this->isAvailable()) {
            return $result;
        }

        $srcTableName = $this->integrationOptions->getEntitySource();
        $prjTableName = $this->integrationOptions->getProjectSource();
        $prjKeyFieldName = $this->integrationOptions->getProjectKeyField();
        $refFieldName = $this->integrationOptions->getEntityRefFieldName();
        $isSavedFieldName = $this->integrationOptions->getIsSavedFieldName();
        $createdFieldName = $this->integrationOptions->getCreatedFieldName();
        $idFieldName = $this->integrationOptions->getEntityIdFieldName();

        $fields = EntityFacade::getExternalEntityProperties($this->systemCode);
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

        $tableName = $this->integrationOptions->getUserSource();
        $keyField = $this->integrationOptions->getUserSourceKeyField();
        $displayField = $this->integrationOptions->getUserSourceDisplayField();

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

    public function getEntityKeyById($id)
    {
        $result = false;
        if (!$this->isAvailable()) {
            return $result;
        }

        $srcTableName = $this->integrationOptions->getEntitySource();
        $keyFieldName = $this->integrationOptions->getEntityKeyField();
        $idFieldName = $this->integrationOptions->getEntityIdFieldName();

        $this->connect();

        $sql = "select {$keyFieldName} from {$srcTableName} where {$idFieldName} = {$id}";

        $res = pg_query($this->conn, $sql);

        if ($row = pg_fetch_row($res)) {
            $result = $row[0];
        }

        $this->disconnect();

        return $result;
    }

    public function setEntitySaved($id, bool $saved)
    {
        if (!$this->isAvailable()) {
            return;
        }

        $tableName = $this->integrationOptions->getEntitySource();
        $keyFieldName = $this->integrationOptions->getEntityKeyField();
        $fieldName = $this->integrationOptions->getIsSavedFieldName();

        $this->connect();

        $val = $saved ? 't' : 'f';

        $sql = "update {$tableName} set {$fieldName} = '{$val}' where {$keyFieldName} = '{$id}'";

        pg_exec($this->conn, $sql);

        $this->disconnect();
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
