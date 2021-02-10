<?
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

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

$arMessages = [];
$arResult = [];
$strFormName = 'entity_edit_form';
$bSuccess = false;

$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(['filter' => ['NAME' => 'Entities']]);
$hldata = $rsData->fetch();
if ($hldata) {
	$hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
	$strEntityDataClass = $hlentity->getDataClass();
	
	if (!empty($save) && is_array($_POST)) {
		foreach($_POST['NESTED_ENTITY_TYPES'] as $intEntityID => $arEntities) {
			$arElementFields = [
				'UF_NESTED_ENTITY_TYPES' => $arEntities,
			];
			
			$obResult = $strEntityDataClass::update($intEntityID, $arElementFields);
		
			if ($obResult->isSuccess()) {
				$bSuccess = true;
			} else {
				$arMessages[] = new CAdminMessage(Loc::getMessage("PMO_ERROR_EDIT"));
			}
		}
	}

	$res = $strEntityDataClass::getList([
		'select' => ['*'],
	]);
	while ($dr = $res->fetch()) {
		$dr['UF_NESTED_ENTITY_TYPES'] = $dr['UF_NESTED_ENTITY_TYPES'];
		
		if (intval($dr['UF_ENTITY_ICON']) > 0) {
			$arFile = CFile::GetFileArray($dr["UF_ENTITY_ICON"]);
			
			if($arFile) {
				$dr['UF_ENTITY_ICON'] = $arFile;
			}
		}
		
		$arResult['ENTITIES'][$dr['ID']] = $dr;
		$arResult['JS_ENTITIES'][$dr['ID']] = [
			'NAME'	=> $dr['UF_NAME'],
			'VALUE'	=> $dr['ID']
		];
	}
	unset($rsData, $hldata, $hlentity, $res, $dr, $strEntityDataClass);
}

$arTabs = [
	['DIV' => 'tab-1', 'TAB' => Loc::getMessage('PMO_NESTED_ENTITY_TYPES_TAB_GENERAL')],
];
$tabControl = new CAdminTabControl("tabControl", $arTabs);

$APPLICATION->SetTitle(Loc::getMessage('PMO_NESTED_ENTITY_TYPES_LIST_TITLE'));
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");

if(!empty($arMessages)) {
	foreach($arMessages as $objMessage) {
		echo $objMessage->Show();
	}
} elseif ($bSuccess) {
	CAdminMessage::ShowMessage(["MESSAGE" => Loc::getMessage("PMO_SUCCESS_EDIT"), "TYPE" => "OK"]);
}
?>

<form method="POST"
	action="<? echo $APPLICATION->GetCurPage() ?>?lang=<?=LANG?>&ID=<?=$ID?>"
	name="<?= $strFormName?>"
	enctype = 'multipart/form-data'>
	
    <?= bitrix_sessid_post()?>
    <?
	$tabControl->Begin();
	$tabControl->BeginNextTab();
	?>
	<tr colspan="2">
		<td align="center">
			<table class="internal" id="table_ENTITY_FIELDS">
				<tr class="heading">
					<td><?echo GetMessage("PMO_ENTITY_NAME");?></td>
					<td><?echo GetMessage("PMO_NESTED_ENTITY_TYPES");?></td>
				</tr>
				<?foreach($arResult['ENTITIES'] as $strEntityID => $arEntity) {?>
					<tr id="tr_ENTITY_<?= $strEntityID?>">
						<td style="text-align: left;"><div style="display: table"><?
						if (is_array($arEntity['UF_ENTITY_ICON']) && !empty($arEntity['UF_ENTITY_ICON'])) {
							?><?= CFile::ShowImage($arEntity['UF_ENTITY_ICON']['ID'], 0, 0, "style='min-width: 25px; max-height: 25px; width: auto; margin-right: 10px;'") ?><?
						} elseif ($arEntity['UF_ENTITY_ICON']) {
							echo $arEntity['UF_ENTITY_ICON']." ";
						}
						
						?><span style="display:table-cell; vertical-align: middle;"><?= $arEntity['UF_NAME']?></span></div></td>
						<td style="text-align: middle;">
							<select name="NESTED_ENTITY_TYPES[<?= $strEntityID?>][]" multiple size="5">
								<?
								foreach($arResult['ENTITIES'] as $strNestedEntityID => $arNestedEntity) {?>
									<option value="<?= $strNestedEntityID?>" <?= (in_array($strNestedEntityID, $arEntity['UF_NESTED_ENTITY_TYPES'])) ? 'selected': ''?>><?= $arNestedEntity['UF_NAME']?></option>
								<?}?>
							<select>
						</td>
					</tr>
				<?}?>
			</table>
		</td>
	</tr>
</form>
<?
$tabControl->Buttons([
	"btnApply" => false
]);
$tabControl->End();
?>
<?require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';?>