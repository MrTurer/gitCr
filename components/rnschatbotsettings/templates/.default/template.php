<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
\Bitrix\Main\UI\Extension::load("ui.forms");
?>
<form class="rnsbot-settings">
    <h3>Настройка уведомлений чат-бота</h3>
    <? foreach ($arResult['ENTITIES'] as $entity) {
        $isNotice = $arResult['USER_SETTINGS']['ENTITIES'][$entity['ID']]['NOTICE'];
        $onChangeDeadline = $arResult['USER_SETTINGS']['ENTITIES'][$entity['ID']]['CHANGE_DEADLINE'];
        $todayDeadline = $arResult['USER_SETTINGS']['ENTITIES'][$entity['ID']]['TODAY_DEADLINE'];
        $days = $arResult['USER_SETTINGS']['ENTITIES'][$entity['ID']]['DAYS']; ?>
        <fieldset class="form-group">
            <legend>Тип сущности: <?= $entity['NAME'] ?></legend>
            <label class="ui-ctl ui-ctl-checkbox ui-ctl-inline">
                <input type="checkbox" name="notice<?= $entity['ID'] ?>" value="Y" class="ui-ctl-element"
                    <?= $isNotice == 'Y' ? 'checked' : '' ?> >
            </label>
            <label>
                <div class="ui-ctl ui-ctl-inline">
                    <span class="ui-ctl-label-text">За</span>
                </div>
                <div class="ui-ctl ui-ctl-textbox ui-ctl-inline ui-ctl-w25 ui-ctl-xs">
                    <input class="days ui-ctl-element" type="number" name="days<?= $entity['ID'] ?>" min="0" step="1"
                        <?= $days ? "value='{$days}'" : "" ?> >
                </div>
                <div class="ui-ctl ui-ctl-inline">
                    <span class="ui-ctl-label-text">дней до дедлайна</span>
                </div>
            </label>

            <label class="ui-ctl ui-ctl-checkbox">
                <input type="checkbox" class="ui-ctl-element"
                       name="change<?= $entity['ID'] ?>" value="Y" <?= $onChangeDeadline == 'Y' ? 'checked' : '' ?> >
                <div class="ui-ctl-label-text">При изменении срока</div>
            </label>
            <label class="ui-ctl ui-ctl-checkbox">
                <input type="checkbox" class="ui-ctl-element"
                       name="deadline<?= $entity['ID'] ?>" value="Y" <?= $todayDeadline == 'Y' ? 'checked' : '' ?> >
                <div class="ui-ctl-label-text">Сегодня дедлайн</div>
            </label>
        </fieldset>
    <? } ?>
    <button type="submit" class="ui-btn ui-btn-success">Сохранить</button>
</form>

<script>
    $(document).ready(function () {
        $(".rnsbot-settings").submit(function (e) {
            e.preventDefault();
            var data = $(this).serializeArray().reduce(function (obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
            BX.ajax.runComponentAction(
                "rnschatbotsettings",
                "saveForm",
                {
                    mode: "class",
                    data: {
                        settings: data
                    }
                }
            ).then(function (response) {
                if (response.status === 'success') {
                    BX.SidePanel.Instance.close();
                }
            });
        });
    });
</script>


<style>
    .rnsbot-settings {
        margin: 20px;
    }
    .rnsbot-settings fieldset {
        margin: 0 0 15px 0;
        border: #aaa solid 1px;
    }

    .rnsbot-settings .ui-ctl-checkbox {
        margin: 0;
    }
    .rnsbot-settings .ui-ctl-label-text {
        padding: 0;
    }
</style>