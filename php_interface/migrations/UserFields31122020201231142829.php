<?php

namespace Sprint\Migration;


class UserFields31122020201231142829 extends Version
{
    protected $description = "Модуль \"Производственный офис\" от 311220. Пользовательские свойства.";

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
  'FIELD_NAME' => 'UF_TYPE_ENTITY',
  'USER_TYPE_ID' => 'hlblock',
  'XML_ID' => 'UF_TYPE_ENTITY',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'I',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'DISPLAY' => 'LIST',
    'LIST_HEIGHT' => 5,
    'HLBLOCK_ID' => 'Entities',
    'HLFIELD_ID' => 'UF_NAME',
    'DEFAULT_VALUE' => 0,
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Type',
    'ru' => 'Тип',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Type',
    'ru' => 'Тип',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Type',
    'ru' => 'Тип',
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
  'FIELD_NAME' => 'UF_STATUS',
  'USER_TYPE_ID' => 'hlblock',
  'XML_ID' => 'UF_STATUS',
  'SORT' => '100',
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
    'HLBLOCK_ID' => 'StatusEntity',
    'HLFIELD_ID' => 'UF_RUS_NAME',
    'DEFAULT_VALUE' => 1,
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Status',
    'ru' => 'Статус',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Status',
    'ru' => 'Статус',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Status',
    'ru' => 'Статус',
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
  'ENTITY_ID' => 'HLBLOCK_StatusEntity',
  'FIELD_NAME' => 'UF_ACTIVE',
  'USER_TYPE_ID' => 'boolean',
  'XML_ID' => 'UF_ACTIVE',
  'SORT' => '50',
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
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'HLBLOCK_StatusEntity',
  'FIELD_NAME' => 'UF_CODE',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_CODE',
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
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'HLBLOCK_StatusEntity',
  'FIELD_NAME' => 'UF_RUS_NAME',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_RUS_NAME',
  'SORT' => '200',
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
    'en' => 'Russian name',
    'ru' => 'Русское название',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Russian name',
    'ru' => 'Русское название',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Russian name',
    'ru' => 'Русское название',
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
  'ENTITY_ID' => 'HLBLOCK_StatusEntity',
  'FIELD_NAME' => 'UF_ENG_NAME',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_ENG_NAME',
  'SORT' => '300',
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
    'en' => 'English name',
    'ru' => 'Английское название',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'English name',
    'ru' => 'Английское название',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'English name',
    'ru' => 'Английское название',
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
  'ENTITY_ID' => 'HLBLOCK_StatusEntity',
  'FIELD_NAME' => 'UF_ENTITY_TYPE_BIND',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_ENTITY_TYPE_BIND',
  'SORT' => '400',
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
    'en' => 'Binding to an entity type',
    'ru' => 'Привязка к типу сущности',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Binding to an entity type',
    'ru' => 'Привязка к типу сущности',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Binding to an entity type',
    'ru' => 'Привязка к типу сущности',
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
  'ENTITY_ID' => 'HLBLOCK_StatusEntity',
  'FIELD_NAME' => 'UF_NEXT_STATUS',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_NEXT_STATUS',
  'SORT' => '500',
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
    'en' => 'Next status',
    'ru' => 'Следующий статус',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Next status',
    'ru' => 'Следующий статус',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Next status',
    'ru' => 'Следующий статус',
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
  'ENTITY_ID' => 'HLBLOCK_StatusEntity',
  'FIELD_NAME' => 'UF_NEXT_STATUS_BUTTON_NAME',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_NEXT_STATUS_BUTTON_NAME',
  'SORT' => '600',
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
    'en' => 'Name of the button for moving to the next status',
    'ru' => 'Название кнопки перехода в следующий статус',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Name of the button for moving to the next status',
    'ru' => 'Название кнопки перехода в следующий статус',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Name of the button for moving to the next status',
    'ru' => 'Название кнопки перехода в следующий статус',
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
  'ENTITY_ID' => 'HLBLOCK_StatusEntity',
  'FIELD_NAME' => 'UF_PRESENCE_INCOMP_CHILD',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_PRESENCE_INCOMP_CHILD',
  'SORT' => '700',
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
    'en' => 'The presence of incomplete entities, children',
    'ru' => 'Наличие незавершенных сущностей, дочерних',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'The presence of incomplete entities, children',
    'ru' => 'Наличие незавершенных сущностей, дочерних',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'The presence of incomplete entities, children',
    'ru' => 'Наличие незавершенных сущностей, дочерних',
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
  'ENTITY_ID' => 'HLBLOCK_StatusEntity',
  'FIELD_NAME' => 'UF_LACK_LINKED_ENTITIES',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_LACK_LINKED_ENTITIES',
  'SORT' => '800',
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
    'en' => 'The lack of linked entities',
    'ru' => 'Отсутствие привязанных сущностей',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'The lack of linked entities',
    'ru' => 'Отсутствие привязанных сущностей',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'The lack of linked entities',
    'ru' => 'Отсутствие привязанных сущностей',
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
  'ENTITY_ID' => 'HLBLOCK_Entities',
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
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'HLBLOCK_Entities',
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
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'HLBLOCK_Entities',
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
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'HLBLOCK_Entities',
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
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'HLBLOCK_Entities',
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
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'HLBLOCK_Entities',
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
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'HLBLOCK_Entities',
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
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'HLBLOCK_Entities',
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
