<?php

use Bitrix\Main\Localization\Loc;

return [
  'module' => [
    'id' => Loc::getMessage('INTEGRATIONS_MODULE_ID'),
  ],
  'database' => [
    'projectTableName' => 'b_sonet_group',
    'taskTableName' => 'b_tasks',
    'isSavedFieldName' => 'is_saved',
    'createdFieldName' => [
      'jira' => 'created'
    ]
  ]
];