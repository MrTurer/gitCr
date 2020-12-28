<?php

namespace RNS\Integrations\Processors;

use RNS\Integrations\Models\Mapping;
use RNS\Integrations\Models\OptionsBase;

abstract class DataProviderBase
{
    protected $options;

    protected $mapping;

    protected function __construct(OptionsBase $options, Mapping $mapping)
    {
        $this->options = $options;
        $this->mapping = $mapping;
    }

    public abstract function getProjects();
    public abstract function getEntities();
    public abstract function getUsers();
}
