<?
require_once(__DIR__ . '/events.php');

use Bitrix\Main\Page\Asset;

\Bitrix\Main\UI\Extension::load("ui.forms");
\Bitrix\Main\UI\Extension::load("ui.tour");

$arJsConfig = array(
    'hintStorage' => array(
        'js' => '/local/js/hint-storage.js',
    ),
    'rnsHintsEdit' => array(
        'js'=> '/local/js/rns.hints.edit.js',
    ),
    'newHintPopup' => array(
        'js'=> '/local/js/popups/newHintPopup.js',
    ),
    'newGroupPopup' => array(
        'js'=> '/local/js/popups/newGroupPopup.js',
    ),
    'hintsListPopup' => array(
        'js'=> '/local/js/popups/hintsListPopup.js',
    ),
);

foreach ($arJsConfig as $ext => $arExt) {
    \CJSCore::RegisterExt($ext, $arExt);
}


CJSCore::Init(array('ajax', 'popup', 'jquery',
'hintStorage',
'rnsHintsEdit',
'newHintPopup',
'newGroupPopup',
'hintsListPopup'));

AddEventHandler('main', 'onProlog', function(){
    Asset::getInstance()->addJs("/local/js/rns.hints.view.js");
    Asset::getInstance()->addCss("/local/styles/hint-style.css");
}, 99999999);

// TODO: отделить редактор от показа
// hint-core, hint-runner (rel -> hint-core)
// npm?
