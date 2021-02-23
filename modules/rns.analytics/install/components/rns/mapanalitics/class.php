<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\Response\Component;
use Bitrix\Main\Loader;
//use Rns\Analytics\Controller;

class mapanalitics extends \CBitrixComponent implements Controllerable
{
    public function configureActions()
    {
        return [];
    }

    public function getFormAction()
    {
        return new Component('rns:mapanalitics');
    }

    public function executeComponent()
    {
        if (!Loader::includeModule('rns.analytics') && !Loader::includeModule('tasks')) {
            echo GetMessage("ACCESS_DENIED");
            return false;
        }
        $this->includeComponentTemplate();
    }

//    public function executeComponent()
//    {
//        $this->includeComponentTemplate();
//    }
}