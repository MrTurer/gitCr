<?php

namespace RNS\Integrations\Models;

use RNS\Integrations\Helpers\TableHelper;
use RNS\Integrations\MapTypeTable;

class Mapping implements \JsonSerializable
{
    /** @var EntityMap */
    private $projectMap;
    /** @var EntityMap */
    private $taskTypeMap;
    /** @var EntityMap */
    private $taskStatusMap;
    /** @var AttributeMap */
    private $projectAttrMap;
    /** @var AttributeMap */
    private $taskAttrMap;
    /** @var UserMap */
    private $userMap;

    public function __construct()
    {
        $this->projectMap = new EntityMap();
        $this->taskTypeMap = new EntityMap();
        $this->taskStatusMap = new EntityMap();
        $this->projectAttrMap = new AttributeMap();
        $this->taskAttrMap = new AttributeMap();
        $this->userMap = new UserMap();
    }

    /**
     * @return EntityMap
     */
    public function getProjectMap(): EntityMap
    {
        return $this->projectMap;
    }

    /**
     * @param EntityMap $projectMap
     * @return Mapping
     */
    public function setProjectMap(EntityMap $projectMap): Mapping
    {
        $this->projectMap = $projectMap;
        return $this;
    }

    /**
     * @return EntityMap
     */
    public function getTaskTypeMap(): EntityMap
    {
        return $this->taskTypeMap;
    }

    /**
     * @param EntityMap $taskTypeMap
     * @return Mapping
     */
    public function setTaskTypeMap(EntityMap $taskTypeMap): Mapping
    {
        $this->taskTypeMap = $taskTypeMap;
        return $this;
    }

    /**
     * @return EntityMap
     */
    public function getTaskStatusMap(): EntityMap
    {
        return $this->taskStatusMap;
    }

    /**
     * @param EntityMap $taskStatusMap
     * @return Mapping
     */
    public function setTaskStatusMap(EntityMap $taskStatusMap): Mapping
    {
        $this->taskStatusMap = $taskStatusMap;
        return $this;
    }

    /**
     * @return AttributeMap
     */
    public function getProjectAttrMap(): AttributeMap
    {
        return $this->projectAttrMap;
    }

    /**
     * @param AttributeMap $projectAttrMap
     * @return Mapping
     */
    public function setProjectAttrMap(AttributeMap $projectAttrMap): Mapping
    {
        $this->projectAttrMap = $projectAttrMap;
        return $this;
    }

    /**
     * @return AttributeMap
     */
    public function getTaskAttrMap(): AttributeMap
    {
        return $this->taskAttrMap;
    }

    /**
     * @param AttributeMap $taskAttrMap
     * @return Mapping
     */
    public function setTaskAttrMap(AttributeMap $taskAttrMap): Mapping
    {
        $this->taskAttrMap = $taskAttrMap;
        return $this;
    }

    /**
     * @return UserMap
     */
    public function getUserMap(): UserMap
    {
        return $this->userMap;
    }

    /**
     * @param UserMap $userMap
     * @return Mapping
     */
    public function setUserMap(UserMap $userMap): Mapping
    {
        $this->userMap = $userMap;
        return $this;
    }

    public static function createDefault(): self
    {
        $result = new self;

        $settings = require_once ($_SERVER['DOCUMENT_ROOT'] . '/local/modules/integrations/settings.php');

        $projectFields = TableHelper::getTableColumns($settings['database']['projectTableName']);
        $taskFields = TableHelper::getTableColumns($settings['database']['taskTableName']);

        $items = [];
        foreach ($projectFields as $projectField) {
            $item = new AttributeMapItem();
            $item->setMapTypeId(MapTypeTable::FTF);
            $item->setDestAttrName($projectField->getName());
            $items[] = $item;
        }
        $result->projectAttrMap->setItems($items);

        $items = [];
        foreach ($taskFields as $taskField) {
            $item = new AttributeMapItem();
            $item->setMapTypeId(MapTypeTable::FTF);
            $item->setDestAttrName($taskField->getName());
            $items[] = $item;
        }
        $result->taskAttrMap->setItems($items);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
