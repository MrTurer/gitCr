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

$backUrl = 'industrial_office_entities_list.php?lang=' . LANGUAGE_ID;

$arMessages = [];
$hlDataClass = '';
$bIsSuccess = false;
$arFields = [];
$arFile = [];
$strFormName = 'entity_edit_form';

$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(['filter' => ['NAME' => 'Entities']]);
if ($hldata = $rsData->fetch()) {
	$hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
	$strEntityDataClass = $hlentity->getDataClass();
	
	if ($ID > 0) {
		$res = $strEntityDataClass::getList([
			'filter' => [
				'ID' => $ID,
			],
			'select' => ["*"],
			'order' => [
				'UF_NAME' => 'asc'
			],
		]);
		
		if ($row = $res->fetch()) {
			if ($row['UF_ACTIVE']) {
				$row['UF_ACTIVE'] = 'Y';
			} else {
				$row['UF_ACTIVE'] = 'N';
			}
			
			if ($row['UF_CONSIDER_TIME']) {
				$row['UF_CONSIDER_TIME'] = 'Y';
			} else {
				$row['UF_CONSIDER_TIME'] = 'N';
			}
			
			if ($row['UF_DECISION_REQUIRED']) {
				$row['UF_DECISION_REQUIRED'] = 'Y';
			} else {
				$row['UF_DECISION_REQUIRED'] = 'N';
			}
			
			if (intval($row['UF_ENTITY_ICON']) > 0) {
				$row['ICON_FILE_ID'] = $row['UF_ENTITY_ICON'];
				$rsFile = CFile::GetByID($row['UF_ENTITY_ICON']);
				$row['ICON_FILE'] = $rsFile->Fetch();
			} else {
				$row['ICON_CODE'] = $row['UF_ENTITY_ICON'];
			}
			
			$arFields = $row;
		} 
	} else {
		$arFields['UF_ACTIVE'] = 'Y';
	}

	if ((!empty($save) || !empty($apply)) && is_array($_POST)) {
		$arFields = $_POST;
		
		if ($arFields['UF_NAME'] == '') {
			$arMessages[] = new CAdminMessage(Loc::getMessage("PMO_ERROR_EMPTY_NAME"));
		}
		if ($arFields['ICON_CODE'] == '' && (empty($_FILES) || !isset($_FILES['ICON_FILE']))) {
			$arMessages[] = new CAdminMessage(Loc::getMessage("PMO_ERROR_EMPTY_ICON"));
		} elseif (!empty($_FILES) && isset($_FILES['ICON_FILE']) && $_FILES['ICON_FILE']['error'] == 0) {
			$arFile = [
				"name" => $_FILES['ICON_FILE']['name'],
				"size" => $_FILES['ICON_FILE']['size'],
				"tmp_name" => $_FILES['ICON_FILE']['tmp_name'],
				"type" => "",
				"old_file" => "",
				"del" => "Y",
				"MODULE_ID" => "highloadblock"
			];
		} elseif (intval($arFields['ICON_FILE_ID']) <= 0) {
			$arMessages[] = new CAdminMessage(Loc::getMessage("PMO_ERROR_EMPTY_ICON"));
		}
		
		if ($arFields['UF_ENTITY_COLOR'] == '') {
			$arMessages[] = new CAdminMessage(Loc::getMessage("PMO_ERROR_EMPTY_COLOR"));
		}
		
		if (!isset($arFields['UF_ACTIVE'])) {
			$arFields['UF_ACTIVE'] = 'N';
		}
		if (!isset($arFields['UF_CONSIDER_TIME'])) {
			$arFields['UF_CONSIDER_TIME'] = 'N';
		}
		if (!isset($arFields['UF_DECISION_REQUIRED'])) {
			$arFields['UF_DECISION_REQUIRED'] = 'N';
		}
		
		if (empty($arMessages)) {
			$arElementFields = [
				'UF_NAME' => $arFields['UF_NAME'],
				'UF_ACTIVE' => $arFields['UF_ACTIVE'],
				'UF_CODE' => $arFields['UF_CODE'],
				'UF_CONSIDER_TIME' => $arFields['UF_CONSIDER_TIME'],
				'UF_DECISION_REQUIRED' => $arFields['UF_DECISION_REQUIRED'],
				'UF_ENTITY_COLOR' => $arFields['UF_ENTITY_COLOR'],
			];
			
			if (!empty($arFile)) {
				$fid = CFile::SaveFile($arFile, "pmo");
				
				if (intval($fid)>0) $arElementFields["UF_ENTITY_ICON"] = intval($fid); 
				else $arElementFields["UF_ENTITY_ICON"] = "null";
			} elseif (empty($arFile) && intval($arFields['ICON_FILE_ID']) > 0) {
				$arElementFields['UF_ENTITY_ICON'] = $arFields['ICON_FILE_ID'];
			} elseif (empty($arFile) && intval($arFields['ICON_FILE_ID']) <= 0) {
				$arElementFields['UF_ENTITY_ICON'] = $arFields['ICON_CODE'];
			}
			
			if ($ID <= 0) {
				$obResult = $strEntityDataClass::add($arElementFields);
			} else {
				$obResult = $strEntityDataClass::update($ID, $arElementFields);
			}
			
			if ($obResult->isSuccess()) {
				$ID = $obResult->getId();
				$bIsSuccess = true;
			} else {
				$arMessages[] = new CAdminMessage(Loc::getMessage("PMO_ERROR_EDIT"));
			}
		}
		
		if (!empty($save) && empty($arMessages)) {
			LocalRedirect($backUrl);
		} elseif(empty($arMessages)) {
			LocalRedirect($_SERVER['PHP_SELF'] . '?ID=' . $ID . '&lang=' . LANGUAGE_ID.'&SUCCESS=true');
		}
	}
}

$tabs = [
  ['DIV' => 'tab-1', 'TAB' => Loc::getMessage('PMO_ENTITY_EDIT_GENERAL')],
];
$tabControl = new CAdminTabControl("tabControl", $tabs);

$APPLICATION->SetTitle(Loc::getMessage('PMO_ENTITY_EDIT_TITLE'));
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

$aMenu = [
	[
		"TEXT" => Loc::getMessage("PMO_ENTITIES_LIST"),
		"TITLE" => Loc::getMessage("PMO_ENTITIES_LIST"),
		"LINK" => $backUrl,
		"ICON" => "btn_list",
	]
];
// создание экземпляра класса административного меню
$context = new CAdminContextMenu($aMenu);

// вывод административного меню
$context->Show();

if(!empty($arMessages)) {
	foreach($arMessages as $objMessage) {
		echo $objMessage->Show();
	}
} elseif ($SUCCESS == 'true') {
	CAdminMessage::ShowMessage(["MESSAGE" => Loc::getMessage("PMO_SUCCESS_EDIT"), "TYPE" => "OK"]);
}
?>

<form method="POST"
	action="<? echo $APPLICATION->GetCurPage() ?>?lang=<?=LANG?>&ID=<?=$ID?>"
	name="<?= $strFormName?>"
	enctype = 'multipart/form-data'>
	
    <?=bitrix_sessid_post()?>
    <? $tabControl->Begin(); ?>
    <? $tabControl->BeginNextTab(); ?>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_NAME') ?></td>
        <td>
            <?= InputType('text', 'UF_NAME', $arFields['UF_NAME'], false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_ACTIVE') ?></td>
        <td>
			<?= InputType('checkbox', 'UF_ACTIVE', 'Y', [$arFields['UF_ACTIVE']]) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_CODE') ?></td>
        <td>
            <?= InputType('text', 'UF_CODE', $arFields['UF_CODE'], false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_USE_TIME') ?></td>
        <td>
            <?= InputType('checkbox', 'UF_CONSIDER_TIME', 'Y', [$arFields['UF_CONSIDER_TIME']]) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_RESOLUTION') ?></td>
        <td>
            <?= InputType('checkbox', 'UF_DECISION_REQUIRED', 'Y', [$arFields['UF_DECISION_REQUIRED']]) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_ICON') ?></td>
        <td>
            <?= InputType('hidden', 'ICON_FILE_ID', $arFields['ICON_FILE_ID'], false) ?>
            <?= InputType('file', 'ICON_FILE', $arFields['ICON_FILE']['ORIGINAL_NAME'], false, true) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_ICON_CODE') ?></td>
        <td>
            <?= InputType('text', 'ICON_CODE', $arFields['ICON_CODE'], false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_COLOR') ?></td>
        <td>
            <?= InputType('text', 'UF_ENTITY_COLOR', $arFields['UF_ENTITY_COLOR'], false) ?>
        </td>
    </tr>
</form>

<?
$tabControl->Buttons([
    'back_url' => $backUrl
]);
$tabControl->End();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
?>