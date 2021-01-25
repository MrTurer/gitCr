<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<form class="rnsbot-settings">
    <h3>Настройки по типам задач</h3>
    <? foreach ($arResult['ENTITIES'] as $entity) {
        $isNotice = $arResult['USER_SETTINGS']['ENTITIES'][$entity['ID']]['NOTICE'];
        $onChangeDeadline = $arResult['USER_SETTINGS']['ENTITIES'][$entity['ID']]['CHANGE_DEADLINE'];
        $todayDeadline = $arResult['USER_SETTINGS']['ENTITIES'][$entity['ID']]['TODAY_DEADLINE'];
        $days = $arResult['USER_SETTINGS']['ENTITIES'][$entity['ID']]['DAYS']; ?>
        <fieldset class="form-group">
            <legend><?= $entity['NAME'] ?>:</legend>
            <label>
                Уведомлять <input type="checkbox" name="notice<?= $entity['ID'] ?>" class="form-control"
                    <?= $isNotice ? 'checked' : '' ?> >
            </label>
            <label>
                За <input class="days" type="number" name="days<?= $entity['ID'] ?>" min="0" step="1"
                    <?= $days ? "value='{$days}'" : "" ?> > дней до дедлайна
            </label>
            <label>
                Уведомлять при изменении срока <input type="checkbox" name="change<?= $entity['ID'] ?>" class="form-control"
                    <?= $onChangeDeadline ? 'checked' : '' ?> >
            </label>
            <label>
                Сегодня дедлайн <input type="checkbox" name="deadline<?= $entity['ID'] ?>" class="form-control"
                    <?= $todayDeadline ? 'checked' : '' ?> >
            </label>
        </fieldset>
    <? } ?>
    <button type="submit" class="btn btn-primary">Сохранить</button>
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

    .rnsbot-settings label {
        margin: 10px 0;
        display: block;
    }

    .rnsbot-settings .days {
        width: 50px;
    }
</style>