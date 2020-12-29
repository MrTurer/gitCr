<?php

namespace RNS\Integrations\Helpers;

use CSocNetGroup;
use CTasks;
use CUser;
use RNS\Integrations\Models\Mapping;
use RNS\Integrations\Models\OptionsBase;

class EntityFacade
{
    public static function getEntityTypes()
    {
        // TODO: from HLB
        $list = [
            'REFERENCE_ID' => [1, 2, 3, 4, 5, 6, 7],
            'REFERENCE' => [
              'Задача', 'Требование', 'UserStory', 'Обращение', 'Запрос на изменение', 'Тестовый сценарий', 'Дефект'
            ]
        ];
        return $list;
    }

    public static function getEntityStatuses()
    {
        // TODO: from HLB
        $list = [
          'REFERENCE_ID' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
          'REFERENCE' => [
            'ToDo','Develop','Ready For Test','Testing','InProgress','Review','Closed','Reopen','Hold','Passed','Failed'
          ]
        ];
        return $list;
    }

    public static function getExternalEntityTypes(string $systemCode)
    {
        // TODO: from HLB
        $data = [
          'jira' => [
            'REFERENCE_ID' => [1, 2, 3, 4, 5],
            'REFERENCE' => [
              'Эпик', 'История', 'Задача', 'Под-задача', 'Баг'
            ]
          ]
        ];
        return $data[$systemCode];
    }

    public static function getExternalEntityStatues(string $systemCode)
    {
        // TODO: from HLB
        $data = [
          'jira' => [
            'REFERENCE_ID' => [1, 2, 3, 4, 5, 6],
            'REFERENCE' => [
              'Ожидает выполнения', 'Выполняется', 'Открыт', 'Закрыт', 'Переоткрыт', 'Решен'
            ]
          ]
        ];
        return $data[$systemCode];
    }

    public static function getEntityProperties()
    {
        // TODO: from HLB
        $list = [
          'REFERENCE_ID' => [1, 2, 3, 4, 5, 6, 7],
          'REFERENCE' => [
            'Тип', 'Название задачи', 'Приоритет задачи', 'Проект', 'Автор', 'Исполнитель', 'Версия поставки'
          ]
        ];
        return $list;
    }

    public static function getExternalEntityProperties(string $systemCode)
    {
        // TODO: from HLB
        $data = [
          'jira' => [
            'REFERENCE_ID' => [1, 2, 3, 4, 5, 6, 7],
            'REFERENCE' => [
              'Тип', 'Название задачи', 'Приоритет задачи', 'Проект', 'Автор', 'Исполнитель', 'Описание'
            ]
          ]
        ];
        return $data[$systemCode];
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

    public static function getTasks()
    {
        \CModule::IncludeModule('tasks');

        $res = CTasks::GetList([], ['STATUS' => '2']);
        $result = [];
        while ($row = $res->GetNext()) {
            $result[] = $row;
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

    public static function getDataProvider(string $echangeTypeCode, OptionsBase $options, Mapping $mapping)
    {
        $providerClassPath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/integrations/classes/processors/' . $echangeTypeCode . '/' .
          $options->getType() . '/DataProvider.php';
        include_once($providerClassPath);
        $providerClass = "RNS\\Integrations\\Processors\\{$echangeTypeCode}\\{$options->getType()}\\DataProvider";
        $provider = new $providerClass($options, $mapping);
        return $provider;
    }
}
