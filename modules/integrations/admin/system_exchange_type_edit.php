<?php

use Bitrix\Main\Localization\Loc;
use RNS\Integrations\ExchangeTypeTable;
use RNS\Integrations\ExternalSystemTable;
use RNS\Integrations\Helpers\TableHelper;
use RNS\Integrations\MapTypeTable;
use RNS\Integrations\Models\SystemExchangeType;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require_once($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/admin_tools.php');
if (!CModule::IncludeModule("integrations")) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

Loc::loadMessages(__FILE__);

$backUrl = 'integrations_system_exchange_type_list.php?lang=' . LANGUAGE_ID;

$obj = SystemExchangeType::getById($ID);

if ((!empty($save) || !empty($apply)) && is_array($_POST)) {
    $fields = $_POST;

    $obj->save($fields);

    if (!empty($save)) {
        LocalRedirect($backUrl );
    } else {
        LocalRedirect($_SERVER['PHP_SELF'] . '?ID=' . $obj->getId() . '&lang=' . LANGUAGE_ID);
    }
}

$options = $obj->getOptions();
$mapping = $obj->getMapping();

$rows = ExternalSystemTable::getList([
  'select' => ['*'],
  'order' => ['ID' => 'ASC']
])->fetchAll();
$externalSystems = [['REFERENCE_ID' => [], 'REFERENCE' => []]];
foreach ($rows as $item) {
    $externalSystems['REFERENCE_ID'][] = $item['ID'];
    $externalSystems['REFERENCE'][] = $item['NAME'];
}

$rows = ExchangeTypeTable::getList([
  'select' => ['*'],
  'order' => ['ID' => 'ASC']
])->fetchAll();
$exchangeTypes = ['REFERENCE_ID' => [], 'REFERENCE' => []];
foreach ($rows as $item) {
    $exchangeTypes['REFERENCE_ID'][] = $item['ID'];
    $exchangeTypes['REFERENCE'][] = $item['NAME'];
}

$directions = [
  'REFERENCE_ID' => [0, 1],
  'REFERENCE' => ['Импорт', 'Экспорт']
];

$rows = MapTypeTable::getList([
  'select' => ['*'],
  'order' => ['ID' => 'ASC']
])->fetchAll();
$mapTypes = ['REFERENCE_ID' => [], 'REFERENCE' => []];
foreach ($rows as $item) {
    $mapTypes['REFERENCE_ID'][] = $item['ID'];
    $mapTypes['REFERENCE'][] = $item['NAME'];
}

$exchType = '';

$tabs = [
  ['DIV' => 'tab-1', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL')]
];
if ($ID > 0) {
    $exchType = $obj->getExchangeTypeCode();
    $tabs = array_merge($tabs, [
      ['DIV' => 'tab-2', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_' . strtoupper($exchType))],
      ['DIV' => 'tab-3', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_PROJECTS')],
      ['DIV' => 'tab-4', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_PROJ_FIELDS')],
      ['DIV' => 'tab-5', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_TASK_FIELDS')],
      ['DIV' => 'tab-6', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_TASK_TYPES')],
      ['DIV' => 'tab-7', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_TASK_STATUSES')],
      ['DIV' => 'tab-8', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_USERS')]
    ]);
}

$APPLICATION->SetTitle(Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_TITLE'));

$tabControl = new CAdminTabControl('tabControl', $tabs);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';
$tabControl->Begin();
?>
<form method="POST" name="<?= basename(__FILE__, '.php') ?>" action="<?= $APPLICATION->GetCurUri() ?>">
    <?= bitrix_sessid_post() ?>
    <?php $tabControl->BeginNextTab() ?>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL_SYSTEN') ?></td>
        <td>
            <?= SelectBoxFromArray('externalSystem', $externalSystems, $obj->getSystemId()) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL_EXCH_TYPE') ?></td>
        <td>
            <?= SelectBoxFromArray('exchangeType', $exchangeTypes, $obj->getExchangeTypeId()) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL_EXCH_DIR') ?></td>
        <td>
            <?= SelectBoxFromArray('exchangeDirection', $directions, $obj->getDirection()) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL_SCHEDULE') ?></td>
        <td>
            <?= InputType('text', 'schedule', $obj->getSchedule(), false) ?>
        </td>
    </tr
    <tr>
        <td>
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_TASK_LEVEL') ?>
        </td>
        <td>
            <?= InputType('number', 'options[taskLevel]', $options->getTaskLevel(), false, false, false, 'step="1"') ?>
        </td>
    </tr>
    <tr>
        <td>
            <label for="active"><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL_ACTIVE') ?></label>
        </td>
        <td>
            <?= InputType('checkbox', 'active', true, htmlspecialcharsbx($obj->isActive())) ?>
        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <?php if($exchType == ExchangeTypeTable::TYPE_DATABASE) : ?>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_DATABASE_HOSTNAME') ?></td>
        <td>
            <?= InputType('text', 'options[database][hostName]', $options->getHostName(), false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_DATABASE_PORT') ?></td>
        <td>
            <?= InputType('text', 'options[database][port]', $options->getPort(), false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_DATABASE_DATABSENAME') ?></td>
        <td>
            <?= InputType('text', 'options[database][databaseName]', $options->getDatabaseName(), false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_DATABASE_USERNAME') ?></td>
        <td>
            <?= InputType('text', 'options[database][userName]', $options->getUserName(), false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_DATABASE_PASSWORD') ?></td>
        <td>
            <?= InputType('password', 'options[database][password]', $options->getPassword(), false) ?>
        </td>
    </tr>
    <tr>
        <td>
            <?= InputType('button', 'test_connection', Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_TEST_CONNECTION'), false); ?>
        </td>
    </tr>
    <?php endif; ?>
    <?php if ($exchType == ExchangeTypeTable::TYPE_API) : ?>
    <!-- Настройки API -->
    <tr><td></td></tr>
    <?php endif; ?>
    <?php if ($exchType == ExchangeTypeTable::TYPE_EMAIL) : ?>
    <!-- Настройки E-mail -->
    <tr><td></td></tr>
    <?php endif; ?>
    <?php if ($exchType == ExchangeTypeTable::TYPE_FILES) : ?>
    <!-- Настройки импорта/экспорта файлов -->
    <tr><td></td></tr>
    <?php endif; ?>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Экземпляры проектов -->
    <tr>
        <td>
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_PROJ_SOURCE_ELEM') ?>
        </td>
        <td>
            <?= InputType('text', 'mapping[projectMap][srcElementName]', $mapping->getProjectMap()->getSrcElementName(), false) ?>
        </td>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_KEY_ATTR') ?>
        </td>
        <td>
            <?= InputType('text', 'mapping[projectMap][keyAttrName]', $mapping->getProjectMap()->getKeyAttrName(), false) ?>
        </td>
    </tr>
    <tr>
        <td>
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DISPLAY_ATTR') ?>
        </td>
        <td>
            <?= InputType('text', 'mapping[projectMap][displayAttrName]', $mapping->getProjectMap()->getDisplayAttrName(), false) ?>
        </td>
        <td class="adm-detail-content-cell-l" colspan="2">
            <?= InputType('button', 'get_projects', Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_GET_PROJECTS'), false); ?>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <table class="adm-list-table">
                <thead>
                <tr class="adm-list-table-header">
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_SRC_PRJ') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DEST_PRJ') ?>
                        </div>
                    </td>
                </tr>
                </thead>
                <tbody>
                <tr>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Атрибуты проекта -->
    <tr>
        <td>
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_PROJ_SOURCE_ELEM') ?>
        </td>
        <td>
            <?= InputType('text', 'mapping[projectAttrMap][sourceElementName]', $mapping->getProjectAttrMap()->getSourceElementName(), false) ?>
        </td>
        <td>
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_KEY_ATTR') ?>
        </td>
        <td>
            <?= InputType('text', 'mapping[projectAttrMap][keyAttrName]', $mapping->getProjectAttrMap()->getKeyAttrName(), false) ?>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <table class="adm-list-table">
                <thead>
                <tr class="adm-list-table-header">
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DEST_ATTR') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_TYPE') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_SRC_ATTR') ?>
                        </div>
                    </td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mapping->getProjectAttrMap()->getItems() as $i => $mapItem): ?>
                <tr>
                    <td>
                        <div class="adm-list-table-cell-inner">
                            <?= InputType('text', "mapping[projectAttrMap][items][{$i}][destAttrName]", $mapItem->getDestAttrName(), false) ?>
                        </div>
                    </td>
                    <td>
                        <div class="adm-list-table-cell-inner">
                            <?= SelectBoxFromArray("mapping[projectAttrMap][items][{$i}][mapTypeId]", $mapTypes, $mapItem->getMapTypeId()) ?>
                        </div>
                    </td>
                    <td>
                        <div class="adm-list-table-cell-inner">
                            <?= InputType('text', "mapping[projectAttrMap][items][{$i}][srcAttrName]", $mapItem->getSrcAttrName(), false) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Атрибуты задачи -->
    <tr>
        <td>
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_TASK_SOURCE_ELEM') ?>
        </td>
        <td>
            <?= InputType('text', 'mapping[taskAttrMap][sourceElementName]', $mapping->getTaskAttrMap()->getSourceElementName(), false) ?>
        </td>
        <td>
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_KEY_ATTR') ?>
        </td>
        <td>
            <?= InputType('text', 'mapping[taskAttrMap][keyAttrName]', $mapping->getTaskAttrMap()->getKeyAttrName(), false) ?>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <table class="adm-list-table">
                <thead>
                <tr class="adm-list-table-header">
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DEST_ATTR') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_TYPE') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_SRC_ATTR') ?>
                        </div>
                    </td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mapping->getTaskAttrMap()->getItems() as $i => $mapItem): ?>
                    <tr>
                        <td>
                            <div class="adm-list-table-cell-inner">
                                <?= InputType('text', "mapping[taskAttrMap][items][{$i}][destAttrName]", $mapItem->getDestAttrName(), false) ?>
                            </div>
                        </td>
                        <td>
                            <div class="adm-list-table-cell-inner">
                                <?= SelectBoxFromArray("mapping[taskAttrMap][items][{$i}][mapTypeId]", $mapTypes, $mapItem->getMapTypeId()) ?>
                            </div>
                        </td>
                        <td>
                            <div class="adm-list-table-cell-inner">
                                <?= InputType('text', "mapping[taskAttrMap][items][{$i}][srcAttrName]", $mapItem->getSrcAttrName(), false) ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Типы задачи -->
    <tr>
        <td>

        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Статусы задачи -->
    <tr>
        <td>

        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Пользователи -->
    <tr>
        <td>
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_USER_SOURCE_ELEM') ?>
        </td>
        <td>
            <?= InputType('text', 'mapping[userMap][srcElementName]', $mapping->getUserMap()->getSrcElementName(), false) ?>
        </td>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_KEY_ATTR') ?>
        </td>
        <td>
            <?= InputType('text', 'mapping[userMap][keyAttrName]', $mapping->getUserMap()->getKeyAttrName(), false) ?>
        </td>
    </tr>
    <tr>
        <td>
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DISPLAY_ATTR') ?>
        </td>
        <td>
            <?= InputType('text', 'mapping[userMap][displayAttrName]', $mapping->getUserMap()->getDisplayAttrName(), false) ?>
        </td>
        <td class="adm-detail-content-cell-l" colspan="2">
            <?= InputType('button', 'get_users', Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_GET_USERS'), false); ?>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <table class="adm-list-table">
                <thead>
                <tr class="adm-list-table-header">
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_SRC_USER') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DEST_USER') ?>
                        </div>
                    </td>
                </tr>
                </thead>
                <tbody>
                <tr>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</form>
<?php
$tabControl->Buttons([
    'back_url' => 'integrations_system_exchange_type_list.php?lang=' . LANGUAGE_ID
]);
$tabControl->End();
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
