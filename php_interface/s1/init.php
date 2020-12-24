<?
require_once(__DIR__ . '/events.php');

use Bitrix\Main\Page\Asset;

AddEventHandler('main', 'onProlog', function(){ 
    
    Asset::getInstance()->addJs("/local/js/autohint.js");
    
});