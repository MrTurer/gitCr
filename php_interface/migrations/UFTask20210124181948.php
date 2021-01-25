<?php

namespace Sprint\Migration;


class UFTask20210124181948 extends Version
{
    protected $description = "Новые поля для объекта TASKS_TASK";

    protected $moduleVersion = "3.22.2";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'TASKS_TASK',
  'FIELD_NAME' => 'UF_TASK_SOURCE',
  'USER_TYPE_ID' => 'hlblock',
  'XML_ID' => '',
  'SORT' => '900',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'DISPLAY' => 'LIST',
    'LIST_HEIGHT' => 5,
    'HLBLOCK_ID' => 'TaskSource',
    'HLFIELD_ID' => 'UF_NAME',
    'DEFAULT_VALUE' => 0,
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Entity Source',
    'ru' => 'Источник сущности',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Entity Source',
    'ru' => 'Источник сущности',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Entity Source',
    'ru' => 'Источник сущности',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'TASKS_TASK',
  'FIELD_NAME' => 'UF_EXTERNAL_ID',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => '',
  'SORT' => '1000',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'External ID',
    'ru' => 'Внешний идентификатор',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'External ID',
    'ru' => 'Внешний идентификатор',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'External ID',
    'ru' => 'Внешний идентификатор',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
    }

    public function down()
    {
        //your code ...
    }
}
