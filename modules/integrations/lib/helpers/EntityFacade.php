<?php

namespace RNS\Integrations\Helpers;

use Bitrix\Main\UserFieldLangTable;
use Bitrix\Main\UserFieldTable;
use Bitrix\Tasks\Util\UserField;
use CSocNetGroup;
use CTasks;
use CUser;
use RNS\Integrations\Models\Mapping;
use RNS\Integrations\Models\OptionsBase;

class EntityFacade
{
    public static function getEntityTypes()
    {
        $items = HLBlockHelper::getList('b_hlsys_entities', ['ID', 'UF_NAME', 'UF_CODE'], ['ID'],
          'UF_CODE', ['UF_ACTIVE' => 1]);
        $list = [
            'REFERENCE_ID' => [],
            'REFERENCE' => []
        ];
        foreach ($items as $key => $item) {
            $list['REFERENCE_ID'][] = $key;
            $list['REFERENCE'][] = $item['UF_NAME'];
        }
        return $list;
    }

    public static function getExternalEntityTypes(string $systemCode)
    {
        return self::getHLBlockItems('b_hlsys_external_entities', $systemCode);
    }

    public static function getExternalEntityStatuses(string $systemCode)
    {
        return self::getHLBlockItems('b_hlsys_external_entity_statuses', $systemCode);
    }

    public static function getEntityProperties()
    {
        $fixedFields = [
          'REFERENCE_ID' => [
            'TITLE',
            'GROUP_ID',
            'PARENT_ID',
            'PRIORITY',
            'CREATED_BY',
            'RESPONSIBLE_ID',
            'DESCRIPTION',
            'CREATED_DATE',
            'CHANGED_DATE',
            'START_DATE_PLAN',
            'END_DATE_PLAN',
            'DATE_START',
            'CLOSED_DATE',
          ],
          'REFERENCE' => [
            'Название задачи',
            'Идентификатор проекта',
            'Идентификатор родительской задачи',
            'Приоритет задачи',
            'Автор',
            'Ответственный',
            'Описание задачи',
            'Дата создания',
            'Дата изменения',
            'Планируемая дата начала',
            'Планируемая дата окончания',
            'Дата начала',
            'Дата завершения',
          ]
        ];

        $list = [
          'REFERENCE_ID' => [],
          'REFERENCE' => []
        ];
        $rs = UserFieldTable::getList([
            'select' => ['ID', 'FIELD_NAME'],
            'filter' => ['ENTITY_ID' => 'TASKS_TASK'],
            'order' => ['SORT' => 'ASC']
        ]);
        $fields = $rs->fetchAll();
        foreach ($fields as $field) {
            $rs = UserFieldLangTable::getList([
              'select' => ['USER_FIELD_ID', 'LIST_COLUMN_LABEL'],
              'filter' => ['USER_FIELD_ID' => $field['ID'], 'LANGUAGE_ID' => 'ru']
            ]);
            $lang = $rs->fetch();
            $list['REFERENCE_ID'][] = $field['FIELD_NAME'];
            $list['REFERENCE'][] = !empty($lang['LIST_COLUMN_LABEL']) ? $lang['LIST_COLUMN_LABEL'] : $field['FIELD_NAME'];
        }
        return array_merge_recursive($fixedFields, $list);
    }

    public static function getExternalEntityProperties(string $systemCode)
    {
        return self::getHLBlockItems('b_hlsys_external_entity_properties', $systemCode);
    }

    /**
     * Возвращает список активных проектов для выбора.
     * @return array
     */
    public static function getProjects()
    {
        \CModule::IncludeModule('socialnetwork');

        $res = CSocNetGroup::GetList([], ['ACTIVE' => 'Y'], false, false, ['ID', 'NAME']);
        $result = [
          'REFERENCE_ID' => [],
          'REFERENCE' => []
        ];
        while ($row = $res->GetNext()) {
            $result['REFERENCE_ID'][] = $row['ID'];
            $result['REFERENCE'][] = $row['NAME'];
        }
        return $result;
    }

    /**
     * Возвращает список активных пользователей для выбора.
     * @return array
     */
    public static function getUsers()
    {
        $by = 'LAST_NAME, NAME, SECOND_NAME';
        $order = 'ASC';
        $res = CUser::GetList($by, $order, ['ACTIVE' => 'Y']);
        $result = [
          'REFERENCE_ID' => [],
          'REFERENCE' => []
        ];
        while ($row = $res->GetNext()) {
            $result['REFERENCE_ID'][] = $row['ID'];
            $result['REFERENCE'][] = $row['LAST_NAME'] . ' ' . $row['NAME'] . ' ' . ($row['SECOND_NAME'] ?? '') .
              ' (' . $row['EMAIL'] . ')';
        }
        return $result;
    }

    public static function getExternalProjects(string $echangeTypeCode, OptionsBase $options, Mapping $mapping)
    {
        $provider = self::getDataProvider($echangeTypeCode, $options, $mapping);
        return $provider->getProjects();
    }

    public static function getExternalUsers(string $echangeTypeCode, OptionsBase $options, Mapping $mapping)
    {
        $provider = self::getDataProvider($echangeTypeCode, $options, $mapping);
        return $provider->getUsers();
    }

    public static function getDataProvider(string $exchangeTypeCode, OptionsBase $options, Mapping $mapping)
    {
        $providerClassPath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/integrations/lib/processors/' . $exchangeTypeCode . '/' .
          $options->getType() . '/DataProvider.php';
        include_once($providerClassPath);
        $providerClass = "RNS\\Integrations\\Processors\\{$exchangeTypeCode}\\{$options->getType()}\\DataProvider";
        $provider = new $providerClass($options, $mapping);
        return $provider;
    }

    private static function getHLBlockItems(string $tableName, string $systemCode)
    {
        $items = HLBlockHelper::getList($tableName, ['ID', 'UF_NAME', 'UF_CODE'], ['ID'],
          'UF_CODE', ['UF_SYSTEM_CODE' => $systemCode, 'UF_ACTIVE' => 1]);
        $list = [
          'REFERENCE_ID' => [],
          'REFERENCE' => []
        ];
        foreach ($items as $key => $item) {
            $list['REFERENCE_ID'][] = $key;
            $list['REFERENCE'][] = $item['UF_NAME'];
        }
        return $list;
    }
}
