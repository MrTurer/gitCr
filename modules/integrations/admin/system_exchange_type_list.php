<?php

use Bitrix\Main\Localization\Loc;
use RNS\Integrations\Models\SystemExchangeType;
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

$entityId = 'INTEGRATION_SYSTEM_EXCHANGE_TYPE';
$tableId = 'integration_system_exchange_type';
$sort = new CAdminSorting($tableId, 'id', 'asc');
/** @var CAdminList $list */
$list = new CAdminList($tableId, $sort);

if ($objId = $list->GroupAction()) {
    switch ($_REQUEST['action']) {
        case 'delete':
            SystemExchangeType::delete($objId);
            break;
    }
}

$headers = [
  [
    'id' => 'SYSTEM_NAME',
    'content' => Loc::getMessage("INTEGRATIONS_SYS_EXCH_TYPE_LIST_FIELD_SYS_NAME"),
    'default' => true,
    'sort' => 'SYSTEM_NAME'
  ],
  [
    'id' => 'EXCHANGE_TYPE_NAME',
    'content' => Loc::getMessage("INTEGRATIONS_SYS_EXCH_TYPE_LIST_FIELD_EXCH_TYPE"),
    'default' => true,
    'sort' => 'EXCHANGE_TYPE_NAME'
  ],
  [
    'id' => 'CREATED',
    'content' => Loc::getMessage("INTEGRATIONS_SYS_EXCH_TYPE_LIST_FIELD_CREATED"),
    'default' => true,
    'sort' => 'created',
  ]
];

$USER_FIELD_MANAGER->AdminListAddHeaders($entityId, $headers);
$list->AddHeaders($headers);

$res = SystemExchangeTypeTable::getList([
  'select' => ['*', 'SYSTEM_NAME' => 'SYSTEM.NAME', 'EXCHANGE_TYPE_NAME' => 'EXCHANGE_TYPE.NAME'],
  'order' => [strtoupper($sort->getField()) => $sort->getOrder()]
]);
while ($dr = $res->fetch()) {

    $row = &$list->addRow('ID', $dr, 'integrations_system_exchange_type_edit.php?lang='.LANGUAGE_ID.'&ID='.$dr['ID']);

    $USER_FIELD_MANAGER->AddUserFields($entityId, $dr, $row);

    $htmlLink = 'integrations_system_exchange_type_edit.php?ID='.urlencode($dr['ID']).'&lang='.LANGUAGE_ID;
    $row->AddViewField("SYSTEM_NAME", '<a href="'.htmlspecialcharsbx($htmlLink).'">'.htmlspecialcharsEx($dr['SYSTEM_NAME']).'</a>');

    $arActions = [
      [
        "ICON" => "edit",
        "DEFAULT" => "Y",
        "TEXT" => GetMessage("INTEGRATIONS_SYS_EXCH_TYPE_LIST_ACT_EDIT"),
        "ACTION" => $list->ActionRedirect("integrations_system_exchange_type_edit.php?ID=".urlencode($dr['ID'])."&lang=".LANGUAGE_ID)
      ],
      [
        "ICON" => "btn_download",
        "TEXT" => GetMessage("INTEGRATIONS_SYS_EXCH_TYPE_LIST_ACT_RUN"),
        "ACTION" => $list->ActionRedirect("integrations_system_exchange_type_run.php?ID=".urlencode($dr['ID'])."&lang=".LANGUAGE_ID)
      ],
      ["SEPARATOR" => true],
      [
        "ICON" => "delete",
        "TEXT" => GetMessage("INTEGRATIONS_SYS_EXCH_TYPE_LIST_ACT_DELETE"),
        "ACTION" => "if(confirm('".CUtil::JSEscape(GetMessage("INTEGRATIONS_SYS_EXCH_TYPE_LIST_ACT_DEL_CONFIRM"))."')) ".$list->ActionDoGroup($dr['ID'], "delete"),
      ]
    ];
    $row->AddActions($arActions);
}

$context = [
  [
    'ICON' => 'btn_new',
    'TEXT' => GetMessage('MAIN_ADD'),
    'LINK' => 'integrations_system_exchange_type_edit.php?lang='.LANGUAGE_ID,
    'TITLE' => GetMessage('MAIN_ADD')
  ]
];

$list->AddAdminContextMenu($context);
$list->CheckListMode();

$APPLICATION->SetTitle(Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_LIST_TITLE'));
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");

$list->DisplayList();
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");
