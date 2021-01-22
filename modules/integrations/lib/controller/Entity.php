<?php

namespace RNS\Integrations\Controller;

use Bitrix\Main\Engine\Controller;
use RNS\Integrations\Helpers\HLBlockHelper;

class Entity extends Controller
{
    public function statusesAction()
    {
        $request = $this->getRequest();

        $entityType = $request->get('entityType');

        $list = HLBlockHelper::getList('b_hlsys_status_entity', ['ID', 'UF_CODE', 'UF_RUS_NAME'], ['ID'],
          'UF_CODE', ['UF_ENTITY_TYPE_BIND' => $entityType, 'UF_ACTIVE' => 1], false);

        return ['list' => $list];
    }
}
