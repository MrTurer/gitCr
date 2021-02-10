<?
use Bitrix\Main\Localization\Loc;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require_once($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/admin_tools.php');
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CUserTypeManager $USER_FIELD_MANAGER */

if (!CModule::IncludeModule("industrial.office") || !CModule::IncludeModule('highloadblock') || !CModule::IncludeModule("tasks")) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

Loc::loadMessages(__FILE__);

$entityId = 'PMO_SETTINGS_ENTITIES_LIST';
$tableId = 'pmo_settings_entities_list';
$sort = new CAdminSorting($tableId, 'id', 'asc');
/** @var CAdminList $list */
$list = new CAdminList($tableId, $sort);

$headers = [
    [
        'id' => 'ID',
        'content' => 'ID',
        'default' => true,
        'sort' => 'id'
    ],
    [
        'id' => 'UF_NAME',
        'content' => Loc::getMessage("PMO_ENTITIES_LIST_FIELD_NAME"),
        'default' => true,
        'sort' => 'UF_NAME'
    ],
];

$USER_FIELD_MANAGER->AdminListAddHeaders($entityId, $headers);
$list->AddHeaders($headers);

$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(['filter' => ['NAME' => 'Entities']]);
$hldata = $rsData->fetch();
if ($hldata) {
	$hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
	$strEntityDataClass = $hlentity->getDataClass();
	
	$res = $strEntityDataClass::getList([
		'select' => ['ID', 'UF_CODE', 'UF_NAME'],
		'order' => [strtoupper($sort->getField()) => $sort->getOrder()]
	]);
	while ($dr = $res->fetch()) {
		$row = &$list->addRow('ID', $dr, 'industrial_office_settings_entity_statuses_list.php?lang='.LANGUAGE_ID.'&ENTITY_CODE='.urlencode($dr['UF_CODE']));

		$USER_FIELD_MANAGER->AddUserFields($entityId, $dr, $row);

		$htmlLink = 'industrial_office_settings_entity_statuses_list.php?lang='.LANGUAGE_ID.'&ENTITY_CODE='.urlencode($dr['UF_CODE']);
		$row->AddViewField("UF_NAME", '<a href="'.htmlspecialcharsbx($htmlLink).'">'.htmlspecialcharsEx($dr['UF_NAME']).'</a>');
	}
	
	$list->AddAdminContextMenu([]);
}

$list->CheckListMode();

$APPLICATION->SetTitle(Loc::getMessage('PMO_ENTITIES_LIST_TITLE'));
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");

$list->DisplayList();
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");

?>