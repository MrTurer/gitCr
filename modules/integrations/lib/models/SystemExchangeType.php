<?php

namespace RNS\Integrations\Models;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Type\DateTime;
use CAgent;
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
     * @return int|null
     */
    public function getSystemId(): ?int
    {
        return $this->obj->getSystemId();
    }

    /**
     * @return string|null
     */
    public function getSystemCode(): ?string
    {
        return $this->obj->getSystem()->getCode();
    }

    /**
     * @return int|null
     */
    public function getExchangeTypeId(): ?int
    {
        return $this->obj->getExchangeTypeId();
    }

    /**
     * @return string|null
     */
    public function getExchangeTypeCode(): ?string
    {
        return $this->obj->getExchangeType() ? $this->obj->getExchangeType()->getCode() : null;
    }

    /**
     * @return int|null
     */
    public function getDirection(): ?int
    {
        return $this->obj->getDirection();
    }

    /**
     * @return string|null
     */
    public function getSchedule(): ?string
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
     * @return OptionsBase|null
     */
    public function getOptions(): ?OptionsBase
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
     * @param array $fields
     * @return array
     * @throws ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getList(array $fields = []): array
    {
        $res = SystemExchangeTypeTable::getList([
            'select' => empty($fields) ? ['*'] : $fields,
            'filter' => ['=ACTIVE' => 'Y']
        ]);
        return $res->fetchAll();
    }

    /**
     * @param int $id
     * @return SystemExchangeType
     * @throws ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    public static function getById(?int $id)
    {
        $result = new self;
        if ($id > 0) {
            $result->obj = SystemExchangeTypeTable::getByPrimary($id, ['select' => ['*', 'EXCHANGE_TYPE.CODE', 'SYSTEM.CODE']])
              ->fetchObject();
            $mapper = new JsonMapper();
            $mapper->bIgnoreVisibility = true;

            $result->options = $result->createDefaultOptions();
            $options = json_decode($result->obj->getOptions());
            if (!empty($options)) {
                $mapper->map($options, $result->options);
            }

            $result->mapping = new Mapping();
            $mapping = json_decode($result->obj->getMapping());
            if (!empty($mapping)) {
                $mapper->map($mapping, $result->mapping);
            }
        } else {
            $result->obj = SystemExchangeTypeTable::createObject();
            $result->options = new OptionsBase();
            $result->mapping = new Mapping();
        }

        return $result;
    }

    /**
     * @param array $fields
     */
    public function save(array $fields)
    {
        global $USER;

        if (!empty($fields['mapping'])) {
            $mappings = ['projectMap', 'entityTypeMap', 'entityStatusMap', 'entityPropertyMap', 'userMap'];
            foreach ($mappings as $mapping) {
                if (empty($fields['mapping'][$mapping]) || empty($fields['mapping'][$mapping]['items'])) {
                    continue;
                }
                $fields['mapping'][$mapping]['items'] = $this->filterDeletedItems($fields['mapping'][$mapping]['items']);
            }
        }

        $this->obj->setSystemId($fields['externalSystem']);
        $this->obj->setExchangeTypeId($fields['exchangeType']);
        $this->obj->setDirection($fields['exchangeDirection']);
        $this->obj->setSchedule($fields['schedule']);
        $this->obj->setActive(!empty($fields['active']) ? 'Y' : 'N');
        if (!$this->obj->getId()) {
            $this->obj->setCreatedBy($USER->GetID());
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

        $this->addOrUpdateAgent();
    }

    /**
     * @return ApiOptions|DatabaseOptions|EmailOptions|FilesOptions
     * @throws ArgumentException
     */
    public function createDefaultOptions()
    {
        switch ($this->getExchangeTypeCode()) {
            case ExchangeTypeTable::TYPE_API:
                return new ApiOptions();
            case ExchangeTypeTable::TYPE_DATABASE:
                return new DatabaseOptions();
            case ExchangeTypeTable::TYPE_EMAIL:
                return new EmailOptions();
            case ExchangeTypeTable::TYPE_FILES:
                return new FilesOptions();
            default:
                throw new ArgumentException('Unsupported exchange type code.', 'exchangeTypeCode');
        }
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function delete($id)
    {
        return SystemExchangeTypeTable::delete($id)->isSuccess();
    }

    private function filterDeletedItems(array $arr)
    {
        return array_values(array_filter($arr, function($item) {
              return !isset($item['deleted']);
          },
          ARRAY_FILTER_USE_BOTH
        ));
    }

    private function addOrUpdateAgent()
    {
        $name = "\\RNS\\Integrations\\Processors\\IntegrationAgent::run({$this->getId()});";
        $res = $list = CAgent::GetList([], ['NAME' => $name]);
        if ($row = $res->Fetch()) {
            CAgent::Update($row['ID'], [
              'AGENT_INTERVAL' => $this->parseInterval(),
              'ACTIVE' => $this->obj->getActive() ? 'Y' : 'N'
            ]);
        } else {
            CAgent::AddAgent($name, 'integrations', 'N', $this->parseInterval());
        }
    }

    private function parseInterval()
    {
        $result = 3600;
        $schedule = $this->getSchedule();
        if (!preg_match('/(\d+)\s*([hmd]+)/i', $schedule, $matches)) {
            return $result;
        }
        $value = $matches[1];
        switch ($matches[2]) {
            case 'h':
                $result = $value * 3600;
                break;
            case 'm':
                $result = $value * 60;
                break;
            default:
                $result = $value * 86400;
                break;
        }
        return $result;
    }
}
