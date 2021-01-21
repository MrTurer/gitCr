<?php

namespace Sprint\Migration;


class HLEntitiesSettings31122020210115144522 extends Version
{
    protected $description = "Модуль \"Производственный офис\" от 311220. HL \"Сущности\". Настройки HL";

    protected $moduleVersion = "3.22.2";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $hlblockId = $helper->Hlblock()->saveHlblock(array (
  'NAME' => 'Entities',
  'TABLE_NAME' => 'b_hlsys_entities',
  'LANG' => 
  array (
    'ru' => 
    array (
      'NAME' => 'Сущности',
    ),
    'en' => 
    array (
      'NAME' => 'Entities',
    ),
  ),
));
        $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_NAME',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_NAME',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'Y',
  'SHOW_FILTER' => 'I',
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
    'en' => 'Name',
    'ru' => 'Название',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Name',
    'ru' => 'Название',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Name',
    'ru' => 'Название',
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
        $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_ACTIVE',
  'USER_TYPE_ID' => 'boolean',
  'XML_ID' => 'UF_ACTIVE',
  'SORT' => '200',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'I',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'DEFAULT_VALUE' => 1,
    'DISPLAY' => 'CHECKBOX',
    'LABEL' => 
    array (
      0 => 'Нет',
      1 => 'Да',
    ),
    'LABEL_CHECKBOX' => 'Да',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Active',
    'ru' => 'Активность',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Active',
    'ru' => 'Активность',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Active',
    'ru' => 'Активность',
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
        $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_CODE',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_CODE',
  'SORT' => '300',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'Y',
  'SHOW_FILTER' => 'I',
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
    'en' => 'Character code',
    'ru' => 'Символьный код',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Character code',
    'ru' => 'Символьный код',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Character code',
    'ru' => 'Символьный код',
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
        $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_NESTED_ENTITY_TYPES',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_NESTED_ENTITY_TYPES',
  'SORT' => '400',
  'MULTIPLE' => 'Y',
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
    'en' => 'Nested entity types',
    'ru' => 'Вложенные типы сущностей',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Nested entity types',
    'ru' => 'Вложенные типы сущностей',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Nested entity types',
    'ru' => 'Вложенные типы сущностей',
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
        $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_CONSIDER_TIME',
  'USER_TYPE_ID' => 'boolean',
  'XML_ID' => 'UF_CONSIDER_TIME',
  'SORT' => '500',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'I',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'DEFAULT_VALUE' => 0,
    'DISPLAY' => 'CHECKBOX',
    'LABEL' => 
    array (
      0 => 'Нет',
      1 => 'Да',
    ),
    'LABEL_CHECKBOX' => 'Нет',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Binding to an entity type',
    'ru' => 'Учитывать время',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Binding to an entity type',
    'ru' => 'Учитывать время',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Binding to an entity type',
    'ru' => 'Учитывать время',
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
        $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_DECISION_REQUIRED',
  'USER_TYPE_ID' => 'boolean',
  'XML_ID' => 'UF_DECISION_REQUIRED',
  'SORT' => '600',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'DEFAULT_VALUE' => 1,
    'DISPLAY' => 'CHECKBOX',
    'LABEL' => 
    array (
      0 => 'Нет',
      1 => 'Да',
    ),
    'LABEL_CHECKBOX' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Required resolution (decision)',
    'ru' => 'Обязательность резолюции (решения)',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Required resolution (decision)',
    'ru' => 'Обязательность резолюции (решения)',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Required resolution (decision)',
    'ru' => 'Обязательность резолюции (решения)',
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
        $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_ENTITY_ICON',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_ENTITY_ICON',
  'SORT' => '700',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'I',
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
    'en' => 'Icon',
    'ru' => 'Изображение',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Icon',
    'ru' => 'Изображение',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Icon',
    'ru' => 'Изображение',
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
        $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_ENTITY_COLOR',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_ENTITY_COLOR',
  'SORT' => '800',
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
    'en' => 'Color',
    'ru' => 'Цвет',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Color',
    'ru' => 'Цвет',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Color',
    'ru' => 'Цвет',
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
