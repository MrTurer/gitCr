<?
require_once(__DIR__ . '/events.php');

use Bitrix\Main\Page\Asset;

\Bitrix\Main\UI\Extension::load("ui.forms");
\Bitrix\Main\UI\Extension::load("ui.tour");

$arJsConfig = array(
    'dropDownMenu' => array(
        'js'=> '/local/js/dropDownMenu.js',
    ),
    'newHintPopup' => array(
        'js'=> '/local/js/newHintPopup.js',
    ),
    'hintElementInfo' => array(
        'js'=> '/local/js/hintElementInfo.js',
    ),
    'hintsListPopup' => array(
        'js'=> '/local/js/hintsListPopup.js',
    ),
    'clearFields' => array(
        'js'=> '/local/js/clearFields.js',
    ),
    'hintItems' => array(
        'js'=> '/local/js/hintItems.js',
    ),
    //'renderHints' => array(
    //    'js'=> '/local/js/renderHints.js',
    //),
    'renderHintsOld' => array(
        'js'=> '/local/js/renderHintsOld.js',
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
    Asset::getInstance()->addJs("/local/js/newHint.js");
    Asset::getInstance()->addCss("/local/styles/style.css");
    Asset::getInstance()->addCss("/local/styles/hint-style.css");
}, 99999999);
