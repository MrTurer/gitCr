<?php

use Bitrix\Main\Entity\Query\Join;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc;
use RNS\Integrations\ExternalSystemTable;
use RNS\Integrations\SystemExchangeTypeTable;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require_once($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/admin_tools.php');
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CUserTypeManager $USER_FIELD_MANAGER */

if (!CModule::IncludeModule("integrations")) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

Loc::loadMessages(__FILE__);

$entityId = 'INTEGRATION_EXTERNAL_SYSTEM';
$tableId = 'integration_external_system';
$sort = new CAdminSorting($tableId, 'id', 'asc');
/** @var CAdminList $list */
$list = new CAdminList($tableId, $sort);

$filterRows = [
  'ID' => 'ID',
  'NAME' => Loc::getMessage('INTEGRATIONS_SYSTEM_LIST_FIELD_NAME'),
  'CREATED' => Loc::getMessage('INTEGRATIONS_SYSTEM_LIST_FIELD_CREATED'),
];

$USER_FIELD_MANAGER->AddFindFields($entityId, $filterRows);

$filter = new CAdminFilter($tableId . '_filter_id', $filterRows);

$filterFields = [
  'find_name',
  'find_id',
  'find_created_from',
  'find_created_to',
];
$USER_FIELD_MANAGER->AdminListAddFilterFields($entityId, $filterFields);

$adminFilter = $list->InitFilter($filterFields);

$filter = [
  'ID' => $adminFilter['find_id'],
  '%NAME' => $adminFilter['find_name'],
  '>=CREATED' => $adminFilter['find_created_from'],
  '<=CREATED' => $adminFilter['find_created_to'],
];
$USER_FIELD_MANAGER->AdminListAddFilter($entityId, $filter);

$headers = [
    [
        'id' => 'NAME',
        'content' => Loc::getMessage("INTEGRATIONS_SYSTEM_LIST_FIELD_NAME"),
        'default' => true,
        'sort' => 'name'
    ],
    [
        'id' => 'CREATED',
        'content' => Loc::getMessage("INTEGRATIONS_SYSTEM_LIST_FIELD_CREATED"),
        'default' => true,
        'sort' => 'created',
    ],
    [
        'id' => 'IMPORT_ACTIVE',
        'content' => Loc::getMessage("INTEGRATIONS_SYSTEM_LIST_FIELD_IMPORT"),
        'default' => true,
    ],
    [
        'id' => 'EXPORT_ACTIVE',
        'content' => Loc::getMessage("INTEGRATIONS_SYSTEM_LIST_FIELD_EXPORT"),
        'default' => true,
    ],
    [
        'id' => 'DESCRIPTION',
        'content' => Loc::getMessage("INTEGRATIONS_SYSTEM_LIST_FIELD_DESCRIPTION")
    ]
];

$USER_FIELD_MANAGER->AdminListAddHeaders($entityId, $headers);
$list->AddHeaders($headers);

$res = ExternalSystemTable::query()
  ->registerRuntimeField('IMPORT',
        new ReferenceField(
          'IMPORT',
    SystemExchangeTypeTable::class,
          Join::on('this.ID', 'ref.SYSTEM_ID')->where('ref.DIRECTION',  SystemExchangeTypeTable::DIRECTION_IMPORT),
          [
            'join_type' => 'LEFT'
          ]
    )
  )
  ->registerRuntimeField('EXPORT',
    new ReferenceField(
      'EXPORT',
      SystemExchangeTypeTable::class,
      Join::on('this.ID', 'ref.SYSTEM_ID')->where('ref.DIRECTION',  SystemExchangeTypeTable::DIRECTION_EXPORT),
      [
        'join_type' => 'LEFT'
      ]
    )
  )
  ->setSelect(['ID', 'NAME', 'CREATED', 'DESCRIPTION', 'IMPORT_ACTIVE' => 'IMPORT.ACTIVE', 'EXPORT_ACTIVE' => 'EXPORT.ACTIVE'])
  ->addOrder(strtoupper($sort->getField()), $sort->getOrder())
  ->fetchAll();

$valueYes = Loc::getMessage("INTEGRATIONS_SYSTEM_LIST_FIELD_VALUE_ACTIVE");
$valueNo = Loc::getMessage("INTEGRATIONS_SYSTEM_LIST_FIELD_VALUE_INACTIVE");
$valueNull = Loc::getMessage("INTEGRATIONS_SYSTEM_LIST_FIELD_VALUE_NOTSET");

foreach ($res as $dr) {

    $dr['IMPORT_ACTIVE'] = !is_null($dr['IMPORT_ACTIVE']) ? ($dr['IMPORT_ACTIVE'] == 'Y' ? $valueYes : $valueNo) : $valueNull;
    $dr['EXPORT_ACTIVE'] = !is_null($dr['EXPORT_ACTIVE']) ? ($dr['EXPORT_ACTIVE'] == 'Y' ? $valueYes : $valueNo) : $valueNull;

    $row = &$list->addRow('ID', $dr, 'integrations_system_edit.php?lang='.LANGUAGE_ID.'&ID='.$dr['ID']);

    $USER_FIELD_MANAGER->AddUserFields($entityId, $dr, $row);

    $htmlLink = 'integrations_system_edit.php?ID='.urlencode($dr['ID']).'&lang='.LANGUAGE_ID;
    $row->AddViewField("NAME", '<a href="'.htmlspecialcharsbx($htmlLink).'">'.htmlspecialcharsEx($dr['NAME']).'</a>');
}

$context = [
  [
    'ICON' => 'btn_new',
    'TEXT' => GetMessage('MAIN_ADD'),
    'LINK' => 'integrations_system_edit.php?lang='.LANGUAGE_ID,
    'TITLE' => GetMessage('MAIN_ADD')
  ]
];

$list->AddAdminContextMenu($context);
$list->CheckListMode();

$APPLICATION->SetTitle(Loc::getMessage('INTEGRATIONS_SYSTEM_LIST_TITLE'));
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");

$list->DisplayList();
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");
