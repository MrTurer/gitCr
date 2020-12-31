<?
use Bitrix\Main\Localization\Loc,
	Bitrix\Tasks\TaskTable,
	Bitrix\Main\Loader;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require_once($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/admin_tools.php');
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @global CUserTypeManager $USER_FIELD_MANAGER */

if (!Loader::includeModule("industrial.office") || !Loader::includeModule('highloadblock')) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

Loc::loadMessages(__FILE__);

$entityId = 'PMO_ENTITIES_LIST';
$tableId = 'pmo_entities_list';
$sort = new CAdminSorting($tableId, 'id', 'asc');
/** @var CAdminList $lAdmin */
$lAdmin = new CAdminList($tableId, $sort);

$headers = [
    [
        'id' => 'ID',
        'content' => 'ID',
        'default' => true,
        'sort' => 'id'
    ],
    [
        'id' => 'NAME',
        'content' => Loc::getMessage("PMO_ENTITIES_LIST_FIELD_NAME"),
        'default' => true,
        'sort' => 'name'
    ],
];

$USER_FIELD_MANAGER->AdminListAddHeaders($entityId, $headers);
$lAdmin->AddHeaders($headers);

$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(['filter' => ['NAME' => 'Entities']]);
if ($hldata = $rsData->fetch()) {
	$hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
	$strEntityDataClass = $hlentity->getDataClass();
	
	if(($arID = $lAdmin->GroupAction())) {
		global $DB;
		
		foreach($arID as $ID) {
			if(strlen($ID) <= 0)
				continue;
			$ID = IntVal($ID);
			
			switch($_REQUEST['action']) {
				// Удаление сущности
				case "delete":
					if (Loader::includeModule('tasks')) {
						
						$db_res = TaskTable::getList([
							'filter' => ['UF_TYPE_ENTITY' => $ID],
							'select' => ['ID']
						]);
						$totalCount = 0;
						if ($resultTotal = \Bitrix\Main\Application::getConnection()->queryScalar('SELECT FOUND_ROWS() as TOTAL')) {
							$totalCount = $resultTotal;
						}
						
						if ($totalCount <= 0) {
							@set_time_limit(0);
							$DB->StartTransaction();
							
							$obResult = $strEntityDataClass::delete($ID);
							
							if ($obResult->isSuccess()) {
								$bIsSuccess = true;
							} else {
								$DB->Rollback();
								$lAdmin->AddGroupError(Loc::getMessage("PMO_ENTITIES_LIST_ELEM_DELETE_ERROR"), $ID);
							}
							
							$DB->Commit();
						} else {
							$lAdmin->AddGroupError(Loc::getMessage("PMO_ENTITIES_LIST_ELEM_NOT_EMPTY", array('#COUNT#' => $totalCount)), $ID);
						}
					}
					break;
			}
		}
	}
	
	$res = $strEntityDataClass::getList([
		'select' => ['ID', 'UF_NAME'],
		'order' => [strtoupper($sort->getField()) => $sort->getOrder()]
	]);
	while ($dr = $res->fetch()) {
		$row = &$lAdmin->addRow('ID', $dr, 'industrial_office_entities_edit.php?lang='.LANGUAGE_ID.'&ID='.urlencode($dr['ID']));

		$USER_FIELD_MANAGER->AddUserFields($entityId, $dr, $row);
		
		// параметр NAME будет отображаться ссылкой
		$htmlLink = 'industrial_office_entities_edit.php?lang='.LANGUAGE_ID.'&ID='.urlencode($dr['ID']);
		$row->AddViewField("NAME", '<a href="'.htmlspecialcharsbx($htmlLink).'">'.htmlspecialcharsEx($dr['UF_NAME']).'</a>');
		
		$arActions = [];

		// редактирование элемента
		$arActions[] = [
			"ICON"=>"edit",
			"DEFAULT"=>true,
			"TEXT"=>Loc::getMessage("PMO_ENTITIES_LIST_ELEM_EDIT"),
			"ACTION"=>$lAdmin->ActionRedirect($htmlLink)
		];

		// удаление элемента
		$arActions[] = [
			"ICON"=>"delete",
			"TEXT"=>Loc::getMessage("PMO_ENTITIES_LIST_ELEM_DELETE"),
			"ACTION"=>"if(confirm('".Loc::getMessage('PMO_ENTITIES_LIST_ELEM_DELETE_CONFIRM')."')) ".$lAdmin->ActionDoGroup($dr['ID'], "delete")
		];
	
		// применим контекстное меню к строке
		$row->AddActions($arActions);
	}
}

$context = [
  [
    'ICON' => 'btn_new',
    'TEXT' => Loc::getMessage('MAIN_ADD'),
    'LINK' => 'industrial_office_entities_edit.php?lang='.LANGUAGE_ID.'&ID=0',
    'TITLE' => Loc::getMessage('MAIN_ADD')
  ]
];

$lAdmin->AddAdminContextMenu($context);
$lAdmin->CheckListMode();

$APPLICATION->SetTitle(Loc::getMessage('PMO_ENTITIES_LIST_TITLE'));
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");

$lAdmin->DisplayList();
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");

?>