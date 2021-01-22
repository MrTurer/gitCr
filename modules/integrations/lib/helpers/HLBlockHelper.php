<?php

namespace RNS\Integrations\Helpers;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Iblock\ORM\Query;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

class HLBlockHelper
{
    /**
     * @param $id
     * @param array $arSelect
     * @param array $arOrder
     * @param string $key
     * @return array
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getList(
      $id,
      array $arSelect = [],
      array $arOrder = [],
      string $key = 'ID',
      array $arWhere = [],
      bool $assoc = true
    ): array
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new LoaderException('Module Highload Blocks not installed.');
        }

        if ((int)$id) {
            $arFilter = array('ID' => $id);
        } else {
            $arFilter = array('TABLE_NAME' => $id);
        }

        $arHl = HighloadBlockTable::getList(array('filter' => $arFilter))
            ->fetch();

        $hlQuery = new Query(HighloadBlockTable::compileEntity($arHl));

        if ($arSelect) {
            $hlQuery->setSelect($arSelect);
        } else {
            $hlQuery->addSelect("*");
        }

        if ($arWhere) {
            foreach ($arWhere as $field => $value) {
                $hlQuery->addFilter($field, $value);
            }
        }

        if ($arOrder) {
            if ($arOrder[1]) {
                $hlQuery->addOrder($arOrder[0], $arOrder[1]);

            } else {
                $hlQuery->addOrder($arOrder[0]);
            }
        }

        $rsData = $hlQuery->exec();

        $arItems = [];
        if ($assoc) {
            while ($arItem = $rsData->Fetch()) {
                $arItems[$arItem[$key]] = $arItem;
            }
        } else {
            while ($arItem = $rsData->Fetch()) {
                $arItems[] = $arItem;
            }
        }

        return $arItems;
    }
}
