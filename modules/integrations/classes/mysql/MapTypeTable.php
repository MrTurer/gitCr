<?php

namespace RNS\Integrations;

use Bitrix\Main\Entity;

class MapTypeTable extends Entity\DataManager
{
    const FTF = 1;
    const FTC = 2;
    const OTO = 3;
    const LTF = 4;

    public static function getTableName()
    {
        return 'integration_map_type';
    }

    public static function getMap()
    {
        return array(
          new Entity\IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
          new Entity\StringField('NAME'),
          new Entity\StringField('CODE'),
          new Entity\TextField('DESCRIPTION')
        );
    }
}