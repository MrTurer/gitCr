<?php

namespace RNS\Integrations\Models;

use RNS\Integrations\Helpers\HLBlockHelper;

class IntegrationOptions
{
    /** @var string */
    private $entitySource;
    /** @var string */
    private $entityKeyField;
    /** @var string */
    private $isSavedFieldName;
    /** @var string */
    private $createdFieldName;
    /** @var string */
    private $entityIdFieldName;
    /** @var string */
    private $entityRefFieldName;
    /** @var string */
    private $projectSource;
    /** @var string */
    private $projectKeyField;
    /** @var string */
    private $projectDisplayField;
    /** @var string */
    private $userSource;
    /** @var string */
    private $userSourceKeyField;
    /** @var string */
    private $userSourceDisplayField;

    public function __construct(string $systemCode)
    {
        $system = HLBlockHelper::getList('b_hlsys_task_source', ['ID'], [], 'ID',
          ['UF_XML_ID' => strtoupper($systemCode)], false);

        $options = HLBlockHelper::getList('b_hlsys_integration_options', [], ['ID'], 'ID',
          ['UF_SOURCE_ID' => $system[0]['ID']], false);
        $options = $options[0];

        $this->entitySource = $options['UF_ENTITY_SOURCE'];
        $this->entityKeyField = $options['UF_ENTITY_KEY_FIELD'];
        $this->isSavedFieldName = $options['UF_IS_SAVED_FIELD_NAME'];
        $this->createdFieldName = $options['UF_CREATED_FIELD_NAME'];
        $this->entityIdFieldName = $options['UF_ENTITY_ID_FIELD_NAME'];
        $this->entityRefFieldName = $options['UF_ENTITY_REF_FIELD_NAME'];
        $this->projectSource = $options['UF_PROJECT_SOURCE'];
        $this->projectKeyField = $options['UF_PROJECT_KEY_FIELD'];
        $this->projectDisplayField = $options['UF_PROJECT_DISPLAY_FIELD'];
        $this->userSource = $options['UF_USER_SOURCE'];
        $this->userSourceKeyField = $options['UF_USER_SOURCE_KEY_FIELD'];
        $this->userSourceDisplayField = $options['UF_USER_DISPLAY_FIELD'];
    }

    /**
     * @return string
     */
    public function getEntitySource(): string
    {
        return $this->entitySource;
    }

    /**
     * @param string $entitySource
     * @return IntegrationOptions
     */
    public function setEntitySource(string $entitySource): IntegrationOptions
    {
        $this->entitySource = $entitySource;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityKeyField(): string
    {
        return $this->entityKeyField;
    }

    /**
     * @param string $entityKeyField
     * @return IntegrationOptions
     */
    public function setEntityKeyField(string $entityKeyField): IntegrationOptions
    {
        $this->entityKeyField = $entityKeyField;
        return $this;
    }

    /**
     * @return string
     */
    public function getIsSavedFieldName(): string
    {
        return $this->isSavedFieldName;
    }

    /**
     * @param string $isSavedFieldName
     * @return IntegrationOptions
     */
    public function setIsSavedFieldName(string $isSavedFieldName): IntegrationOptions
    {
        $this->isSavedFieldName = $isSavedFieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedFieldName(): string
    {
        return $this->createdFieldName;
    }

    /**
     * @param string $createdFieldName
     * @return IntegrationOptions
     */
    public function setCreatedFieldName(string $createdFieldName): IntegrationOptions
    {
        $this->createdFieldName = $createdFieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityIdFieldName(): string
    {
        return $this->entityIdFieldName;
    }

    /**
     * @param string $entityIdFieldName
     * @return IntegrationOptions
     */
    public function setEntityIdFieldName(string $entityIdFieldName): IntegrationOptions
    {
        $this->entityIdFieldName = $entityIdFieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityRefFieldName(): string
    {
        return $this->entityRefFieldName;
    }

    /**
     * @param string $entityRefFieldName
     * @return IntegrationOptions
     */
    public function setEntityRefFieldName(string $entityRefFieldName): IntegrationOptions
    {
        $this->entityRefFieldName = $entityRefFieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getProjectSource(): string
    {
        return $this->projectSource;
    }

    /**
     * @param string $projectSource
     * @return IntegrationOptions
     */
    public function setProjectSource(string $projectSource): IntegrationOptions
    {
        $this->projectSource = $projectSource;
        return $this;
    }

    /**
     * @return string
     */
    public function getProjectKeyField(): string
    {
        return $this->projectKeyField;
    }

    /**
     * @param string $projectKeyField
     * @return IntegrationOptions
     */
    public function setProjectKeyField(string $projectKeyField): IntegrationOptions
    {
        $this->projectKeyField = $projectKeyField;
        return $this;
    }

    /**
     * @return string
     */
    public function getProjectDisplayField(): string
    {
        return $this->projectDisplayField;
    }

    /**
     * @param string $projectDisplayField
     * @return IntegrationOptions
     */
    public function setProjectDisplayField(string $projectDisplayField): IntegrationOptions
    {
        $this->projectDisplayField = $projectDisplayField;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserSource(): string
    {
        return $this->userSource;
    }

    /**
     * @param string $userSource
     * @return IntegrationOptions
     */
    public function setUserSource(string $userSource): IntegrationOptions
    {
        $this->userSource = $userSource;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserSourceKeyField(): string
    {
        return $this->userSourceKeyField;
    }

    /**
     * @param string $userSourceKeyField
     * @return IntegrationOptions
     */
    public function setUserSourceKeyField(string $userSourceKeyField): IntegrationOptions
    {
        $this->userSourceKeyField = $userSourceKeyField;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserSourceDisplayField(): string
    {
        return $this->userSourceDisplayField;
    }

    /**
     * @param string $userSourceDisplayField
     * @return IntegrationOptions
     */
    public function setUserSourceDisplayField(string $userSourceDisplayField): IntegrationOptions
    {
        $this->userSourceDisplayField = $userSourceDisplayField;
        return $this;
    }

}
