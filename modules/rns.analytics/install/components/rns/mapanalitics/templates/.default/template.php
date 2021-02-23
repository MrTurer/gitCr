<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\Loader;
\Bitrix\Main\UI\Extension::load("ui.buttons");
\Bitrix\Main\UI\Extension::load("ui.forms");
Loader::includeModule('tasks');

?>
<div id="heartMapPopup">
    <p class="heartMapPopup_title">
        Пожалуйста, выберите сотрудников и отчетный период
    </p>
    <form id="heartMap__form" action="">
        <div class="heartMap__search-wrapper">
            <p>
                Сотрудники:
            </p>
            <?
            $APPLICATION->IncludeComponent(
                'bitrix:tasks.widget.member.selector',
                '',
                [
                    'TEMPLATE_CONTROLLER_ID' => $templateId . '-responsible1',
                    'DISPLAY' => 'inline',
                    'MAX' => ($editMode? 1 : 99999),
                    'MIN' => 1,
                    'TYPES' => ['USER', 'USER.EXTRANET', 'USER.MAIL'],
                    'INPUT_PREFIX' => $inputPrefix . '[SE_RESPONSIBLE]',
                    'ATTRIBUTE_PASS' => ['ID', 'NAME', 'LAST_NAME', 'EMAIL'],
                    'DATA' => $taskData['SE_RESPONSIBLE'],
                    'PATH_TO_USER_PROFILE' => $arParams['PATH_TO_USER_PROFILE'],
                    'GROUP_ID' => (array_key_exists('GROUP_ID', $taskData)) ? $taskData['GROUP_ID'] : 0,
                    'ROLE_KEY' => \Bitrix\Tasks\Access\Role\RoleDictionary::ROLE_RESPONSIBLE
                ],
                false,
                ["HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => "Y"]
            );
            ?>
        </div>
        <div class="heartMap__period">
            <p>Отчетный период:</p>
            <select size="1" id="heartMap__select" form="#heartMap__form" name="map_time">
                <option value="thisMonth" >Этот месяц</option>
                <option value="lastMonth">Прошлый месяц</option>
                <option value="thisWeek">Эта неделя</option>
                <option value="lastWeek">Прошлая неделя</option>
                <option value="last">За последние</option>
                <option value="before">Позже</option>
                <option value="after">Раньше</option>
                <option value="interval">Интервал</option>
                <option value="allTime">За все время</option>
            </select>
            <div id="heartMap__interval-wrapper">
                <label for="HMFromDate">От</label>
                <input type="text" id="HMFromDate" name="fromDate" onclick="BX.calendar({node: this, field: this, bTime: false});">
                <label for="HMFromDate">До</label>
                <input type="text" id="HMAfterDate" name="toDate" onclick="BX.calendar({node: this, field: this, bTime: false});">
            </div>
        </div>
        <input class="heartMap_submit" style="display: none;" type="submit" value="Построить диограмму">
    </form>
</div>
<script>
    let mapSelect = $('#heartMap__select'),
        mapIntWrap = $('#heartMap__interval-wrapper');
    mapSelect.on('click', function () {
        mapIntWrap.removeClass().addClass($(this).val());
    });
</script>
<style>
    #heartMapPopup {
        min-width: 700px;
        box-sizing: border-box;
        padding: 15px 15px 0;
    }
    #heartMapPopup form {
        box-sizing: border-box;
        width: 100%;
    }
    #heartMapPopup .task-form-field.inline {
        width: 100%;
    }
    #heartMap__interval-wrapper input {
        display: none;
        width: 67px;
        margin: auto 0 auto 5px;
        position: relative;
    }
    #heartMap__interval-wrapper.last input, #heartMap__interval-wrapper.interval input {
        display: inline-block!important;
    }
    #heartMap__interval-wrapper.before #HMFromDate  {
        display: inline-block;
    }
    #heartMap__interval-wrapper.after input:last-child{
        display: inline-block;
    }
    #heartMap__interval-wrapper label {
        display: none;
    }
    #heartMap__interval-wrapper.last label, #heartMap__interval-wrapper.interval label {
        display: inline-block!important;
        margin-left: 10px;
    }
    .heartMap__period, .heartMap__search-wrapper {
        display: flex;
    }
    .heartMap__period p, .heartMap__search-wrapper p {
        display: block;
        margin: auto 10px auto 0;
        width: 147px;
        font-size: 17px;
    }
    .heartMap__period, .heartMap__search-wrapper {
        padding: 10px;
        background: #faf3db;
        box-sizing: border-box;
        width: 100%;
    }
    .heartMapPopup_title {
        text-align: center;
        font-size: 18px;
        margin: 0 0 15px;
        display: block;
    }
</style>