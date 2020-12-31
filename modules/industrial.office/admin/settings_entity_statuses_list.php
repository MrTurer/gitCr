<?
use Bitrix\Main\Localization\Loc;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require_once($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/admin_tools.php');
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CUserTypeManager $USER_FIELD_MANAGER */

if (!CModule::IncludeModule("industrial.office") || !CModule::IncludeModule('highloadblock')) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

Loc::loadMessages(__FILE__);

$entityId = 'PMO_SETTINGS_STATUSES_LIST';
$tableId = 'pmo_settings_statuses_list';
$sort = new CAdminSorting($tableId, 'id', 'asc');
/** @var CAdminList $list */
$list = new CAdminList($tableId, $sort);

$headers = [
    [
        'id' => 'ID',
        'content' => 'ID',
        'default' => true,
        'sort' => 'ID'
    ],
    [
        'id' => 'UF_ACTIVE',
        'content' => Loc::getMessage("PMO_STATUSES_LIST_FIELD_ACTIVE"),
        'default' => true,
    ],
    [
        'id' => 'UF_CODE',
        'content' => Loc::getMessage("PMO_STATUSES_LIST_FIELD_CODE"),
        'default' => true,
    ],
    [
        'id' => 'NAME_RUS',
        'content' => Loc::getMessage("PMO_STATUSES_LIST_FIELD_NAME_RUS"),
        'default' => true,
        'sort' => 'UF_RUS_NAME'
    ],
    [
        'id' => 'NAME_ENG',
        'content' => Loc::getMessage("PMO_STATUSES_LIST_FIELD_NAME_ENG"),
        'default' => false,
        'sort' => 'UF_ENG_NAME'
    ],
    [
        'id' => 'UF_ENTITY_TYPE_BIND',
        'content' => Loc::getMessage("PMO_STATUSES_LIST_FIELD_ENTITY_TYPE_BIND"),
        'default' => false,
    ],
    [
        'id' => 'UF_NEXT_STATUS',
        'content' => Loc::getMessage("PMO_STATUSES_LIST_FIELD_NEXT_STATUS"),
        'default' => true,
    ],
    [
        'id' => 'UF_NEXT_STATUS_BUTTON_NAME',
        'content' => Loc::getMessage("PMO_STATUSES_LIST_FIELD_NEXT_STATUS_BUTTON_NAME"),
        'default' => true,
    ],
    [
        'id' => 'UF_PRESENCE_INCOMP_CHILD',
        'content' => Loc::getMessage("PMO_STATUSES_LIST_FIELD_PRESENCE_INCOMP_CHILD"),
        'default' => false,
    ],
    [
        'id' => 'UF_LACK_LINKED_ENTITIES',
        'content' => Loc::getMessage("PMO_STATUSES_LIST_FIELD_LACK_LINKED_ENTITIES"),
        'default' => false,
    ],
];

$USER_FIELD_MANAGER->AdminListAddHeaders($entityId, $headers);
$list->AddHeaders($headers);

$arEntity = [];
$arResult = [];

$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(['filter' => ['NAME' => 'Entities']]);
if ($hldata = $rsData->fetch() && strlen($ENTITY_CODE) > 0) {
	$hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
	$strEntityDataClass = $hlentity->getDataClass();
	
	$res = $strEntityDataClass::getList([
		'select' => ['UF_CODE', 'UF_NAME'],
	]);
	while ($dr = $res->fetch()) {
		if ($dr['UF_CODE'] == $ENTITY_CODE) {
			$arEntity = $dr;
		}
		
		$arResult['ENTITIES'][$dr['UF_CODE']] = $dr['UF_NAME'];
	}
	unset($rsData, $hldata, $hlentity, $strEntityDataClass, $res, $dr);
	
	$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(['filter' => ['NAME' => 'StatusEntity']]);
	$hldata = $rsData->fetch();
	if ($hldata && !empty($arEntity)) {
		$hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
		$strEntityDataClass = $hlentity->getDataClass();
		
		$res = $strEntityDataClass::getList([
			'select' => ['*'],
			'order' => [strtoupper($sort->getField()) => $sort->getOrder()],
			'filter' => ['UF_ENTITY_TYPE_BIND' => $arEntity['UF_CODE']]
		]);
		while($dr = $res->fetch()) {
			$row = &$list->addRow('ID', $dr, 'industrial_office_settings_entity_status_edit.php?lang='.LANGUAGE_ID.'&ENTITY_CODE='.urlencode($arEntity['UF_CODE']).'&STATUS_ID='.urlencode($dr['ID']));
			$USER_FIELD_MANAGER->AddUserFields($entityId, $dr, $row);
			
			$dr['UF_ACTIVE'] = ($dr['UF_ACTIVE']) ? Loc::getMessage('PMO_STATUSES_LIST_FIELD_ACTIVE_YES') : Loc::getMessage('PMO_STATUSES_LIST_FIELD_ACTIVE_NO');
			$row->AddViewField('UF_ACTIVE', htmlspecialcharsEx($dr['UF_ACTIVE']));
			$row->AddViewField('UF_CODE', htmlspecialcharsEx($dr['UF_CODE']));

			$htmlLink = 'industrial_office_settings_entity_status_edit.php?lang='.LANGUAGE_ID.'&ENTITY_CODE='.urlencode($arEntity['UF_CODE']).'&STATUS_ID='.urlencode($dr['ID']);
			$row->AddViewField("NAME_RUS", '<a href="'.htmlspecialcharsbx($htmlLink).'">'.htmlspecialcharsEx($dr['UF_RUS_NAME']).'</a>');
			$row->AddViewField("NAME_ENG", '<a href="'.htmlspecialcharsbx($htmlLink).'">'.htmlspecialcharsEx($dr['UF_ENG_NAME']).'</a>');
			
			$row->AddViewField('UF_ENTITY_TYPE_BIND', htmlspecialcharsEx($arResult['ENTITIES'][$dr['UF_ENTITY_TYPE_BIND']]));
			$dr['UF_NEXT_STATUS'] = unserialize($dr['UF_NEXT_STATUS']);
			$row->AddViewField('UF_NEXT_STATUS', htmlspecialcharsEx(implode(' / ', $dr['UF_NEXT_STATUS'])));
			
			$dr['UF_NEXT_STATUS_BUTTON_NAME'] = array_filter(unserialize($dr['UF_NEXT_STATUS_BUTTON_NAME']));
			$row->AddViewField('UF_NEXT_STATUS_BUTTON_NAME', htmlspecialcharsEx(implode(' / ', $dr['UF_NEXT_STATUS_BUTTON_NAME'])));
			
			$arTmp = [];
			foreach($dr['UF_PRESENCE_INCOMP_CHILD'] as $strEntityCode){
				if (isset($arResult['ENTITIES'][$strEntityCode])) {
					$arTmp[] = $arResult['ENTITIES'][$strEntityCode];
				}
			}
			$row->AddViewField('UF_PRESENCE_INCOMP_CHILD', htmlspecialcharsEx(implode(' / ', $arTmp)));
			
			$arTmp = [];
			foreach($dr['UF_LACK_LINKED_ENTITIES'] as $strEntityCode){
				if (isset($arResult['ENTITIES'][$strEntityCode])) {
					$arTmp[] = $arResult['ENTITIES'][$strEntityCode];
				}
			}
			$row->AddViewField('UF_LACK_LINKED_ENTITIES', htmlspecialcharsEx(implode(' / ', $arTmp)));
		}
	}
	
	$context = [
	  [
		'ICON' => 'btn_new',
		'TEXT' => GetMessage('MAIN_ADD'),
		'LINK' => 'industrial_office_settings_entity_status_edit.php?lang='.LANGUAGE_ID.'&ENTITY_CODE='.$arEntity['UF_CODE'].'&ID=0',
		'TITLE' => GetMessage('MAIN_ADD')
	  ]
	];

	$list->AddAdminContextMenu($context);
}

$list->CheckListMode();

$APPLICATION->SetTitle(Loc::getMessage('PMO_STATUSES_LIST_TITLE').$arEntity['UF_NAME']);
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");

$list->DisplayList();
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");

?>