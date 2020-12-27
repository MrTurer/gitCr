<?php

namespace RNS\Integrations\Models;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Type\DateTime;
use JsonMapper;
use RNS\Integrations\ExchangeTypeTable;
use RNS\Integrations\SystemExchangeTypeTable;

class SystemExchangeType
{
    /** @var SystemExchangeTypeTable */
    private $obj;
    /** @var OptionsBase */
    private $options;
    /** @var Mapping */
    private $mapping;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->obj->getId();
    }

    /**
     * @return int
     */
    public function getSystemId(): int
    {
        return $this->obj->getSystemId();
    }

    /**
     * @return int
     */
    public function getExchangeTypeId(): int
    {
        return $this->obj->getExchangeTypeId();
    }

    /**
     * @return string
     */
    public function getExchangeTypeCode(): string
    {
        return $this->obj->getExchangeType()->getCode();
    }

    /**
     * @return int
     */
    public function getDirection(): int
    {
        return $this->obj->getDirection();
    }

    /**
     * @return string
     */
    public function getSchedule(): string
    {
        return $this->obj->getSchedule();
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->obj->getActive();
    }

    /**
     * @return OptionsBase
     */
    public function getOptions(): OptionsBase
    {
        return $this->options;
    }

    /**
     * @param OptionsBase $options
     * @return SystemExchangeType
     */
    public function setOptions(OptionsBase $options): SystemExchangeType
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return Mapping
     */
    public function getMapping(): Mapping
    {
        return $this->mapping;
    }

    /**
     * @param Mapping $mapping
     * @return SystemExchangeType
     */
    public function setMapping(Mapping $mapping): SystemExchangeType
    {
        $this->mapping = $mapping;
        return $this;
    }

    /**
     * @param int $id
     * @return SystemExchangeType
     * @throws ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    public static function getById(int $id)
    {
        $result = new self;
        if ($id > 0) {
            $result->obj = SystemExchangeTypeTable::getByPrimary($id, ['select' => ['*', 'EXCHANGE_TYPE.CODE']])
              ->fetchObject();
            $mapper = new JsonMapper();
            $mapper->bIgnoreVisibility = true;

            $result->options = $result->createDefaultOptions();
            $options = json_decode($result->obj->getOptions());
            if (!empty($options)) {
                $mapper->map($options, $result->options);
            }

            $result->mapping = Mapping::createDefault();
            $mapping = json_decode($result->obj->getMapping());
            if (!empty($mapping)) {
                $mapper->map($mapping, $result->mapping);
            }
        } else {
            $result->obj = SystemExchangeTypeTable::createObject();
            $result->mapping = Mapping::createDefault();
        }

        return $result;
    }

    /**
     * @param array $fields
     */
    public function save(array $fields)
    {
        global $USER;

        $this->obj->setSystemId($fields['externalSystem']);
        $this->obj->setExchangeTypeId($fields['exchangeType']);
        $this->obj->setDirection($fields['exchangeDirection']);
        $this->obj->setSchedule($fields['schedule']);
        $this->obj->setActive(!empty($fields['active']) ? 'Y' : 'N');
        if (!$this->obj->getId()) {
            $this->obj->setCreateddBy($USER->GetID());
        }
        $this->obj->setModifiedBy($USER->GetID());
        $this->obj->setModified(DateTime::createFromTimestamp(time()));

        $code = $this->getExchangeTypeCode();
        foreach ($fields['options'][$code] as $key => $value) {
            $this->options->{'set'.ucwords($key)}($value);
        }
        $this->options->setTaskLevel($fields['options']['taskLevel']);

        $this->obj->setOptions(json_encode($this->options, JSON_UNESCAPED_UNICODE));
        $this->obj->setMapping(json_encode($fields['mapping'], JSON_UNESCAPED_UNICODE));

        $this->obj->save();
    }

    public function createDefaultOptions()
    {
        switch ($this->getExchangeTypeCode()) {
            case ExchangeTypeTable::TYPE_API:
                return ApiOptions::createDefault();
            case ExchangeTypeTable::TYPE_DATABASE:
                return DatabaseOptions::createDefault();
            case ExchangeTypeTable::TYPE_EMAIL:
                return EmailOptions::createDefault();
            case ExchangeTypeTable::TYPE_FILES:
                return FilesOptions::createDefault();
            default:
                throw new ArgumentException('Unsupported exchange type code.', 'exchangeTypeCode');
        }
    }
}