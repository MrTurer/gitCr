<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Config\Configuration,
	Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader,
	Bitrix\Main\Diag\Debug;

global $APPLICATION, $USER;

if (!$USER->IsAdmin()) {
	return;
}

$arModulesList = ['industrial.office'];
foreach($arModulesList as $moduleId)
{
	Loader::includeModule($moduleId);
}
$module_id = 'industrial.office';
/**
 *
 * Описание логики табов и настроек в табах
 */
/**
 * общие настройки
 */
$tabs[] = [
	'DIV' => 'general',
	'TAB' => Loc::getMessage('PMO_ST_TAB_GENERAL_NAME'),
	'TITLE' => Loc::getMessage('PMO_ST_TAB_GENERAL_TITLE')
];
$strKanbanBoardColumn = trim(Option::get($module_id, 'PMO_ST_KANBAN_BOARD_COLUMN'));
$strWorkDay = trim(Option::get($module_id, 'PMO_ST_WORK_DAY'));

$options['general'] = [
	[
		'PMO_ST_KANBAN_BOARD_COLUMN',
		Loc::getMessage('PMO_ST_KANBAN_BOARD_COLUMN'),
		$strKanbanBoardColumn,
		['text', '20'],
		'',
		''
	],
	[
		'PMO_ST_WORK_DAY',
		Loc::getMessage('PMO_ST_WORK_DAY'),
		$strWorkDay,
		['text', '20'],
		'',
		''
	],
];

if (check_bitrix_sessid() && (strlen($_POST['save']) > 0 || strlen($_POST['apply']) > 0))
{
	foreach ($options as $option) {
		__AdmSettingsSaveOptions($module_id, $option);
	}
	if (strlen($_POST['save']) > 0)
	{
		LocalRedirect($APPLICATION->GetCurPageParam());
	}
}
/*
 * отрисовка формы
 */
$tabControl = new CAdminTabControl('tabControl', $tabs);
$tabControl->Begin();
?>

<form method="POST"
	action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&lang=<?= LANGUAGE_ID ?>"
	id="baseexchange_form">
	
	<?
    if (!empty($options))
    {
	    foreach($options as $option){
		    $tabControl->BeginNextTab();
		    __AdmSettingsDrawList($module_id, $option);
	    }
    }
	$tabControl->Buttons(['btnApply' => true, 'btnCancel' => false, 'btnSaveAndAdd' => false]);
	echo bitrix_sessid_post();
	$tabControl->End();
	?>
</form>