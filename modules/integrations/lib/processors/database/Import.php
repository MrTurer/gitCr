<?php

namespace RNS\Integrations\Processors\Database;

use Bitrix\Main\Loader;
use Bitrix\Tasks\Internals\Task\MemberTable;
use Bitrix\Tasks\Item\Task;
use CSocNetGroup;
use CTaskMembers;
use RNS\Integrations\Helpers\EntityFacade;
use RNS\Integrations\Helpers\HLBlockHelper;
use RNS\Integrations\Models\EntityTypeMapItem;
use RNS\Integrations\Processors\DataTransferBase;
use RNS\Integrations\Processors\DataTransferResult;

/**
 * Реализация импорта из базы данных.
 * @package RNS\Integrations\Processors\Database
 */
class Import extends DataTransferBase
{
    public function getCapabilities()
    {
        return [
          'supportedDBMS' => [
            'REFERENCE_ID' => ['pgsql'],
            'REFERENCE' => ['PostgreSQL']
          ]
        ];
    }

    /**
     * @throws \Exception
     */
    protected function execute()
    {
        $this->result = new DataTransferResult();
        try {
            Loader::includeModule('tasks');
            Loader::includeModule('socialnetwork');

            $provider = EntityFacade::getDataProvider($this->exchangeTypeCode, $this->systemCode, $this->options, $this->mapping);

            $entities = $provider->getEntities($this->systemCode);

            $projectMap = $this->mapping->getProjectMap();
            $refFieldName = $this->integrationOptions->getEntityRefFieldName();

            $keyFieldName = $this->integrationOptions->getEntityKeyField();

            $propMapItems = $this->mapping->getEntityPropertyMap()->getItems();
            $typeMap = $this->mapping->getEntityTypeMap();
            $statusMap = $this->mapping->getEntityStatusMap();

            $userMap = $this->mapping->getUserMap();
            $respSettings = $this->mapping->getResponsibleSettings();

            $res = HLBlockHelper::getList('b_hlsys_task_source',  ['ID'], [], 'ID',
              ['UF_XML_ID' => strtoupper($this->systemCode)], false);
            if (empty($res)) {
                throw new \Exception('Не найдена запись в справочнике источников задачи для системы ' . $this->systemCode);
            }
            $sourceId = $res[0]['ID'];

            foreach ($entities as $entity) {

                $key = $entity[$keyFieldName];

                $task = Task::findOne([
                    'select' => ['ID', 'TITLE'],
                    'filter' => ['=UF_TASK_SOURCE' => $sourceId, '=UF_EXTERNAL_ID' => $key]
                ]);

                $data = [];
                $data['UF_TASK_SOURCE'] = $sourceId;

                /** @var EntityTypeMapItem $typeMapItem */
                $typeMapItem = null;

                foreach ($propMapItems as $propMapItem) {
                    $srcProp = $propMapItem->getExternalPropertyId();

                    $value = $entity[$srcProp];

                    $destProp = $propMapItem->getInternalPropertyId();

                    if ($destProp == 'UF_TYPE_ENTITY') {
                        $typeMapItem = $typeMap->getItemByExternalTypeId($value);
                        if (!$typeMapItem) {
                            $this->addError('Не найдено сопоставление для типа сущности ' . $value);
                            break;
                        }
                        $value = $typeMapItem->getInternalTypeId();
                        $dictItem = HLBlockHelper::getList('b_hlsys_entities', ['ID'], [], 'ID',
                          ['UF_CODE' => $value], false);
                        if (!empty($dictItem)) {
                            $value = $dictItem[0]['ID'];
                        }

                    } elseif ($destProp == 'UF_STATUS') {
                        if (!$typeMapItem) {
                            $this->addError('Сопоставление типов сущностей должно быть указано перед сопоставлением статусов.');
                            break;
                        }
                        $statusMapItem = $statusMap->getItemByExternalStatusId($typeMapItem->getExternalTypeId(), $value);
                        if (!$statusMapItem) {
                            $this->addError("Не найдено сопоставление для статуса {$value} сущности {$typeMapItem->getExternalTypeId()}");
                            break;
                        }
                        $value = $statusMapItem->getInternalStatusId();
                        $dictItem = HLBlockHelper::getList('b_hlsys_status_entity', ['ID'], [], 'ID',
                          ['UF_ENTITY_TYPE_BIND' => $typeMapItem->getInternalTypeId(), 'UF_CODE' => $value],
                          false);
                        if (!empty($dictItem)) {
                            $value = $dictItem[0]['ID'];
                        }
                    } elseif ($destProp == 'GROUP_ID') {
                        $projectItem = $projectMap->getItemByExternalId($entity[$refFieldName]);
                        if ($projectItem) {
                            $project = CSocNetGroup::GetById($projectItem->getInternalEntityId());
                            $value = $project['ID'];
                        } else {
                            $value = $projectMap->getDefaultEntityId();
                        }
                    } elseif ($destProp == 'RESPONSIBLE_ID') {
                        $userMapItem = $userMap->getItemByExternalId($value);
                        if ($userMapItem) {
                            $value = $userMapItem->getInternalId();
                        } else {
                            $value = $respSettings->getDefaultResponsibleId();
                        }
                    } elseif ($destProp == 'CREATED_BY') {
                        $userMapItem = $userMap->getItemByExternalId($value);
                        if ($userMapItem) {
                            $value = $userMapItem->getInternalId();
                        } else {
                            $value = $respSettings->getDefaultAuthorId();
                        }
                    } elseif ($destProp == 'PARENT_ID') {
                        $parentKey = $provider->getEntityKeyById($this->systemCode, $value);
                        if ($parentKey) {
                            $parentTask = Task::findOne([
                              'select' => ['ID', 'TITLE'],
                              'filter' => ['=UF_TASK_SOURCE' => $sourceId, '=UF_EXTERNAL_ID' => $parentKey]
                            ]);
                            if ($parentTask) {
                                $value = $parentTask->getId();
                            }
                        }
                    } elseif ($destProp == 'DEADLINE') {
                        if (!$value) {
                            $value = new \DateTime();
                            $value->add(new \DateInterval("P{$respSettings->getDefaultDeadlineDays()}D"));
                        }
                    }
                    $data[$destProp] = $value;
                }

                if (!$task) {
                    $task = new Task($data);
                } else {
                    $this->deleteTaskMembers($task->getId());
                    $task->setData($data);
                }

                $result = $task->save();

                if ($result->isSuccess()) {
                    $provider->setEntitySaved($key, true);
                } else {
                    $provider->setEntitySaved($key, false);
                    foreach ($result->getErrors() as $error) {
                        $this->addError($error->getMessage());
                    }
                }
            }

            $this->result->success = empty($this->result->errors);
        } catch (\Exception $ex) {
            $this->addError($ex->getMessage());
            throw $ex;
        }
    }

    private function deleteTaskMembers($taskId)
    {
        $list = MemberTable::getList(array(
          'filter' => ['TASK_ID' => $taskId]
        ));
        while ($item = $list->fetch())
        {
            MemberTable::delete($item);
        }
    }
}