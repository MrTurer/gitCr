<?php

namespace RNS\Integrations\Processors;

use RNS\Integrations\Models\IntegrationOptions;
use RNS\Integrations\Models\Mapping;
use RNS\Integrations\Models\OptionsBase;

abstract class DataProviderBase
{
    protected $systemCode;

    protected $options;

    protected $mapping;

    /** @var IntegrationOptions */
    protected $integrationOptions;

    protected function __construct(
        string $systemCode,
        IntegrationOptions $integrationOptions,
        OptionsBase $options,
        Mapping $mapping
    ) {
        $this->systemCode = $systemCode;
        $this->integrationOptions = $integrationOptions;
        $this->options = $options;
        $this->mapping = $mapping;
    }

    public abstract function isAvailable();
    public abstract function getProjects();
    public abstract function getEntities();
    public abstract function getUsers();
    public abstract function getEntityKeyById($id);
    public abstract function setEntitySaved($id, bool $saved);
}
