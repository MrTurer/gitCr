<?
require_once(__DIR__ . '/events.php');

use Bitrix\Main\Page\Asset;

\Bitrix\Main\UI\Extension::load("ui.forms");
\Bitrix\Main\UI\Extension::load("ui.tour");

$arJsConfig = array(
    'dropDownMenu' => array(
        'js'=> '/local/js/dropDownMenu.js?v=20210209',
    ),
    'newHintPopup' => array(
        'js'=> '/local/js/newHintPopup.js?v=20210209',
    ),
    'hintElementInfo' => array(
        'js'=> '/local/js/hintElementInfo.js?v=20210209',
    ),
    'hintsListPopup' => array(
        'js'=> '/local/js/hintsListPopup.js?v=20210209',
    ),
    'clearFields' => array(
        'js'=> '/local/js/clearFields.js?v=20210209',
    ),
    'hintItems' => array(
        'js'=> '/local/js/hintItems.js?v=20210209',
    ),
    //'renderHints' => array(
    //    'js'=> '/local/js/renderHints.js?v=20210209',
    //),
    'renderHintsOld' => array(
        'js'=> '/local/js/renderHintsOld.js?v=20210209',
    )
);

foreach ($arJsConfig as $ext => $arExt) {
    \CJSCore::RegisterExt($ext, $arExt);
}

CUtil::InitJSCore(array('dropDownMenu'));
CUtil::InitJSCore(array('newHintPopup'));
CUtil::InitJSCore(array('hintElementInfo'));
CUtil::InitJSCore(array('hintsListPopup'));
CUtil::InitJSCore(array('clearFields'));
CUtil::InitJSCore(array('hintItems'));
//CUtil::InitJSCore(array('renderHints'));
CUtil::InitJSCore(array('renderHintsOld'));

CJSCore::Init(array('ajax', 'popup', 'jquery'));

AddEventHandler('main', 'onProlog', function(){
    Asset::getInstance()->addJs("/local/js/newHint.js?v=20210209");
    Asset::getInstance()->addCss("/local/styles/style.css?v=20210209");
    Asset::getInstance()->addCss("/local/styles/hint-style.css?v=20210209");
}, 99999999);
