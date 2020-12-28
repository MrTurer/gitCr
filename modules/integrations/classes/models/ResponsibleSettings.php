<?php

namespace RNS\Integrations\Models;

class ResponsibleSettings
{
    /** @var int */
    private $defaultResponsibleId;
    /** @var bool */
    private $executorLoading;
    /** @var int */
    private $defaultAuthorId;
    /** @var bool */
    private $authorLoading;
    /** @var int */
    private $defaultDeadlineDays;

    /**
     * @return int
     */
    public function getDefaultResponsibleId(): int
    {
        return $this->defaultResponsibleId;
    }

    /**
     * @param int $defaultResponsibleId
     * @return ResponsibleSettings
     */
    public function setDefaultResponsibleId(int $defaultResponsibleId): ResponsibleSettings
    {
        $this->defaultResponsibleId = $defaultResponsibleId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isExecutorLoading(): bool
    {
        return $this->executorLoading;
    }

    /**
     * @param bool $executorLoading
     * @return ResponsibleSettings
     */
    public function setExecutorLoading(bool $executorLoading): ResponsibleSettings
    {
        $this->executorLoading = $executorLoading;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultAuthorId(): int
    {
        return $this->defaultAuthorId;
    }

    /**
     * @param int $defaultAuthorId
     * @return ResponsibleSettings
     */
    public function setDefaultAuthorId(int $defaultAuthorId): ResponsibleSettings
    {
        $this->defaultAuthorId = $defaultAuthorId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuthorLoading(): bool
    {
        return $this->authorLoading;
    }

    /**
     * @param bool $authorLoading
     * @return ResponsibleSettings
     */
    public function setAuthorLoading(bool $authorLoading): ResponsibleSettings
    {
        $this->authorLoading = $authorLoading;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultDeadlineDays(): int
    {
        return $this->defaultDeadlineDays;
    }

    /**
     * @param int $defaultDeadlineDays
     * @return ResponsibleSettings
     */
    public function setDefaultDeadlineDays(int $defaultDeadlineDays): ResponsibleSettings
    {
        $this->defaultDeadlineDays = $defaultDeadlineDays;
        return $this;
    }
}
