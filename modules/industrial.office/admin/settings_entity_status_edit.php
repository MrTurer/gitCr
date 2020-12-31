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

$backUrl = 'industrial_office_settings_entity_statuses_list.php?lang=' . LANGUAGE_ID.'&ENTITY_CODE='.$ENTITY_CODE;

$arMessages = [];
$bIsSuccess = false;
$arResult = [];

$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(['filter' => ['NAME' => 'StatusEntity']]);
if ($hldata = $rsData->fetch()) {
	$hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
	$strEntityDataClass = $hlentity->getDataClass();
	
	if ($STATUS_ID > 0) {
		$res = $strEntityDataClass::getList([
			'filter' => [
				'ID' => $STATUS_ID,
			],
			'select' => ["*"],
		]);
		
		if ($row = $res->fetch()) {
			if ($row['UF_ACTIVE']) {
				$row['UF_ACTIVE'] = 'Y';
			} else {
				$row['UF_ACTIVE'] = 'N';
			}
			
			$row['UF_NEXT_STATUS'] = unserialize($row['UF_NEXT_STATUS']);
			$row['UF_NEXT_STATUS_BUTTON_NAME'] = unserialize($row['UF_NEXT_STATUS_BUTTON_NAME']);
			
			$arResult['FIELDS'] = $row;
		} 
	} else {
		$arResult['FIELDS']['UF_ACTIVE'] = 'Y';
	}
	
	$res = $strEntityDataClass::getList([
		'filter' => [
			'UF_ENTITY_TYPE_BIND' => $ENTITY_CODE,
		],
		'select' => ["UF_CODE", "UF_RUS_NAME"],
	]);
	while ($row = $res->fetch()) {
		$arResult['STATUSES']['REFERENCE_ID'][] = $row['UF_CODE'];
		$arResult['STATUSES']['REFERENCE'][] = $row['UF_RUS_NAME'];
		
		$arResult['STATUSES_LINKED'][$row['UF_CODE']] = $row['UF_RUS_NAME'];
	}
	
	if ((!empty($save) || !empty($apply)) && is_array($_POST)) {
		$arResult['FIELDS'] = $_POST;
		
		if ($arResult['FIELDS']['UF_CODE'] == '') {
			$arMessages[] = new CAdminMessage(Loc::getMessage("PMO_ERROR_EMPTY_CODE"));
		} else {
			$arResult['FIELDS']['UF_CODE'] = strtoupper($arResult['FIELDS']['UF_CODE']);
		}
		
		if (!is_array($arResult['FIELDS']['UF_PRESENCE_INCOMP_CHILD'])) {
			$arResult['FIELDS']['UF_PRESENCE_INCOMP_CHILD'] = [$arResult['FIELDS']['UF_PRESENCE_INCOMP_CHILD']];
		}
		
		if (!is_array($arResult['FIELDS']['UF_LACK_LINKED_ENTITIES'])) {
			$arResult['FIELDS']['UF_LACK_LINKED_ENTITIES'] = [$arResult['FIELDS']['UF_LACK_LINKED_ENTITIES']];
		}
		
		foreach($arResult['FIELDS']['UF_NEXT_STATUS_BUTTON_NAME'] as $strStatusCode => $strStatusName) {
			if ($strStatusName != '' && !in_array($strStatusCode, $arResult['FIELDS']['UF_NEXT_STATUS'])) {
				$arMessages[] = new CAdminMessage(
					Loc::getMessage(
						"PMO_ERROR_EMPTY_NEXT_STATUS_CODE",
						[
							'#STATUS_NAME#' => $arResult['STATUSES_LINKED'][$strStatusCode],
							'#BUTTON_NAME#' => $strStatusName
						]
					)
				);
			}
		}
		
		if (empty($arMessages)) {
			$arElementFields = [
				'UF_ACTIVE' => $arResult['FIELDS']['UF_ACTIVE'],
				'UF_CODE' => $arResult['FIELDS']['UF_CODE'],
				'UF_RUS_NAME' => $arResult['FIELDS']['UF_RUS_NAME'],
				'UF_ENG_NAME' => $arResult['FIELDS']['UF_ENG_NAME'],
				'UF_ENTITY_TYPE_BIND' => $ENTITY_CODE,
				'UF_NEXT_STATUS' => serialize($arResult['FIELDS']['UF_NEXT_STATUS']),
				'UF_NEXT_STATUS_BUTTON_NAME' => serialize($arResult['FIELDS']['UF_NEXT_STATUS_BUTTON_NAME']),
				'UF_PRESENCE_INCOMP_CHILD' => $arResult['FIELDS']['UF_PRESENCE_INCOMP_CHILD'],
				'UF_LACK_LINKED_ENTITIES' => $arResult['FIELDS']['UF_LACK_LINKED_ENTITIES'],
			];
						
			if ($STATUS_ID <= 0) {
				$obResult = $strEntityDataClass::add($arElementFields);
			} else {
				$obResult = $strEntityDataClass::update($STATUS_ID, $arElementFields);
			}
			
			if ($obResult->isSuccess()) {
				$bIsSuccess = true;
			} else {
				$arMessages[] = new CAdminMessage(Loc::getMessage("PMO_ERROR_EDIT"));
			}
		}
		
		if (!empty($save)) {
			LocalRedirect($backUrl);
		} elseif(empty($arMessages)) {
			LocalRedirect($_SERVER['PHP_SELF'] . '?ENTITY_CODE='.$ENTITY_CODE.'&STATUS_ID=' . $obResult->getId() . '&lang=' . LANGUAGE_ID.'&SUCCESS=true');
		}
	}
	
	$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(['filter' => ['NAME' => 'Entities']]);
	if ($hldata = $rsData->fetch()) {
		$hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
		$strEntityDataClass2 = $hlentity->getDataClass();
		
		$db_res = $strEntityDataClass2::getList([
			'select' => ['UF_CODE', 'UF_NAME'],
		]);
		while ($ar_res = $db_res->fetch()) {
			$arResult['ENTITIES'][$ar_res['UF_CODE']] = $ar_res['UF_NAME'];
			$arResult['ENTITIES_FOR_SELECT']['REFERENCE_ID'][] = $ar_res['UF_CODE'];
			$arResult['ENTITIES_FOR_SELECT']['REFERENCE'][] = $ar_res['UF_NAME'];
		}
	}
}

$tabs = [
  ['DIV' => 'tab-1', 'TAB' => Loc::getMessage('PMO_ENTITY_EDIT_GENERAL')],
];
$tabControl = new CAdminTabControl("tabControl", $tabs);

$APPLICATION->SetTitle(Loc::getMessage('PMO_ENTITY_EDIT_TITLE'));
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

if(!empty($arMessages)) {
	foreach($arMessages as $objMessage) {
		echo $objMessage->Show();
	}
} elseif ($SUCCESS == 'true') {
	CAdminMessage::ShowMessage(["MESSAGE" => Loc::getMessage("PMO_SUCCESS_EDIT"), "TYPE" => "OK"]);
}
?>

<form method="POST" action="<? echo $APPLICATION->GetCurPage() ?>?lang=<?=LANG?>&ENTITY_CODE=<?=$ENTITY_CODE?>&STATUS_ID=<?=$STATUS_ID?>" name="form1">
    <?=bitrix_sessid_post()?>
    <? $tabControl->Begin(); ?>
    <? $tabControl->BeginNextTab(); ?>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_ENTITY_TYPE_BIND') ?></td>
        <td>
			<span><?= $arResult['ENTITIES'][$ENTITY_CODE]?></span>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_ACTIVE') ?></td>
        <td>
			<?= InputType('checkbox', 'UF_ACTIVE', 'Y', [$arResult['FIELDS']['UF_ACTIVE']]) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_CODE') ?></td>
        <td>
            <?= InputType('text', 'UF_CODE', $arResult['FIELDS']['UF_CODE'], false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_RUS_NAME') ?></td>
        <td>
            <?= InputType('text', 'UF_RUS_NAME', $arResult['FIELDS']['UF_RUS_NAME'], false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_ENG_NAME') ?></td>
        <td>
            <?= InputType('text', 'UF_ENG_NAME', $arResult['FIELDS']['UF_ENG_NAME'], false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_NEXT_STATUS') ?></td>
        <td>
            <?= SelectBoxMFromArray('UF_NEXT_STATUS[]', $arResult['STATUSES'], $arResult['FIELDS']['UF_NEXT_STATUS'], "", false, 5)?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_NEXT_STATUS_BUTTON_NAME') ?></td>
        <td>
			<?
			$i = 0;
			foreach($arResult['STATUSES_LINKED'] as $key => $strStatusName) {
				echo "<span style='min-width: 150px; display:inline-block;'>".$strStatusName."</span>";
				echo  InputType('text', "UF_NEXT_STATUS_BUTTON_NAME[".$key."]", $arResult['FIELDS']['UF_NEXT_STATUS_BUTTON_NAME'][$key], false);
				echo "<br/>";
				$i++;
			}
			?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_PRESENCE_INCOMP_CHILD') ?></td>
        <td>
            <?= SelectBoxMFromArray('UF_PRESENCE_INCOMP_CHILD[]', $arResult['ENTITIES_FOR_SELECT'], $arResult['FIELDS']['UF_PRESENCE_INCOMP_CHILD'], "", false, 5)?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('PMO_ENTITY_FIELD_LACK_LINKED_ENTITIES') ?></td>
        <td>
            <?= SelectBoxMFromArray('UF_LACK_LINKED_ENTITIES[]', $arResult['ENTITIES_FOR_SELECT'], $arResult['FIELDS']['UF_LACK_LINKED_ENTITIES'], "", false, 5)?>
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