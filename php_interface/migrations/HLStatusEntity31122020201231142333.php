<?php

namespace Sprint\Migration;


class HLStatusEntity31122020201231142333 extends Version
{
    protected $description = "Модуль \"Производственный офис\" от 311220. HL \"Статусы сущности\"";

    protected $moduleVersion = "3.22.2";

    /**
     * @throws Exceptions\ExchangeException
     * @throws Exceptions\RestartException
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $this->getExchangeManager()
            ->HlblockElementsImport()
            ->setExchangeResource('hlblock_elements.xml')
            ->setLimit(20)
            ->execute(function ($item) {
                $this->getHelperManager()
                    ->Hlblock()
                    ->addElement(
                        $item['hlblock_id'],
                        $item['fields']
                    );
            });
    }

    public function down()
    {
        //your code ...
    }
}
