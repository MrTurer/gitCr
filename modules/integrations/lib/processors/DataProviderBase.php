<?php

namespace RNS\Integrations\Processors;

use RNS\Integrations\Models\Mapping;
use RNS\Integrations\Models\OptionsBase;

abstract class DataProviderBase
{
    protected $options;

    protected $mapping;

    protected $moduleOptions;

    protected function __construct(OptionsBase $options, Mapping $mapping)
    {
        $this->options = $options;
        $this->mapping = $mapping;
        $this->moduleOptions = include($_SERVER['DOCUMENT_ROOT'] . '/local/modules/integrations/options.php');
    }

    public abstract function isAvailable();
    public abstract function getProjects();
    public abstract function getEntities(string $systemCode);
    public abstract function getUsers();
}
