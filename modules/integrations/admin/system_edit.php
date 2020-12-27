<?php

use Bitrix\Main\Localization\Loc;
use RNS\Integrations\ExternalSystemTable;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require_once($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/admin_tools.php');

if (!CModule::IncludeModule("integrations")) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

Loc::loadMessages(__FILE__);

$backUrl = 'integrations_system_list.php?lang=' . LANGUAGE_ID;

if ($ID > 0) {
    $obj = ExternalSystemTable::getByPrimary($ID)
      ->fetchObject();
} else {
    $obj = ExternalSystemTable::createObject();
    $obj->setCreatedBy($USER->GetID());
}

if ((!empty($save) || !empty($apply)) && is_array($_POST)) {
    $fields = $_POST;

    $obj->setName($fields['name']);
    $obj->setCode($fields['code']);
    $obj->setDescription($fields['description']);
    $obj->setModifiedBy($USER->GetID());
    $obj->setModified(\Bitrix\Main\Type\DateTime::createFromTimestamp(time()));
    $obj->save();
    if (!empty($save)) {
        LocalRedirect($backUrl);
    } else {
        LocalRedirect($_SERVER['PHP_SELF'] . '?ID=' . $obj->getId() . '&lang=' . LANGUAGE_ID);
    }
}

$tabs = [
  ['DIV' => 'tab-1', 'TAB' => Loc::getMessage('INTEGRATIONS_SYSTEM_EDIT_GENERAL')],
];
$tabControl = new CAdminTabControl("tabControl", $tabs);

$APPLICATION->SetTitle(Loc::getMessage('INTEGRATIONS_SYSTEM_EDIT_TITLE'));
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';
?>
<form method="POST" action="<? echo $APPLICATION->GetCurPage() ?>?lang=<?=LANG?>&ID=<?=$ID?>" name="form1">
    <?=bitrix_sessid_post()?>
    <? $tabControl->Begin(); ?>
    <? $tabControl->BeginNextTab(); ?>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYSTEM_EDIT_FIELD_NAME') ?></td>
        <td>
            <?= InputType('text', 'name', $obj->getName(), false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYSTEM_EDIT_FIELD_CODE') ?></td>
        <td>
            <?= InputType('text', 'code', $obj->getCode(), false) ?>
        </td>
    </tr>
    <tr>
        <td>
            <label for="description">
            <?= Loc::getMessage('INTEGRATIONS_SYSTEM_EDIT_FIELD_DESCRIPTION') ?>
            </label>
        </td>
        <td>
            <textarea name="description" id="description" rows="3" style="width:100%;"><?=htmlspecialcharsbx($obj->getDescription())?></textarea>
        </td>
    </tr>
</form>
<?php
$tabControl->Buttons([
    'back_url' => $backUrl
]);
$tabControl->End();
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';

