<?php

namespace Sprint\Migration;


class Modulesettings31122020201231142951 extends Version
{
    protected $description = "Модуль \"Производственный офис\" от 311220. Настройки модуля.";

    protected $moduleVersion = "3.22.2";

    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Option()->saveOption(array (
  'MODULE_ID' => 'industrial.office',
  'NAME' => 'BR_ST_KANBAN_BOARD_COLUMN',
  'VALUE' => '08:00',
  'DESCRIPTION' => NULL,
  'SITE_ID' => NULL,
));
        $helper->Option()->saveOption(array (
  'MODULE_ID' => 'industrial.office',
  'NAME' => 'BR_ST_WORK_DAY',
  'VALUE' => '08:00',
  'DESCRIPTION' => NULL,
  'SITE_ID' => NULL,
));
        $helper->Option()->saveOption(array (
  'MODULE_ID' => 'industrial.office',
  'NAME' => 'PMO_ST_KANBAN_BOARD_COLUMN',
  'VALUE' => '08:00',
  'DESCRIPTION' => NULL,
  'SITE_ID' => NULL,
));
        $helper->Option()->saveOption(array (
  'MODULE_ID' => 'industrial.office',
  'NAME' => 'PMO_ST_WORK_DAY',
  'VALUE' => '08:00',
  'DESCRIPTION' => NULL,
  'SITE_ID' => NULL,
));
    }

    public function down()
    {
        //your code ...
    }
}
