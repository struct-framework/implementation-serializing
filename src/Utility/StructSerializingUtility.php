<?php

declare(strict_types=1);

namespace Struct\Serializing\Utility;

use Exception\Unexpected\UnexpectedException;
use Struct\Contracts\StructInterface;
use Struct\Serializing\Private\Utility\SerializeUtility;
use Struct\Serializing\Private\Utility\UnSerializeUtility;

class StructSerializingUtility
{
    protected SerializeUtility $serializeUtility;
    protected UnSerializeUtility $unSerializeUtility;

    public function __construct()
    {
        $this->serializeUtility = new SerializeUtility();
        $this->unSerializeUtility = new UnSerializeUtility();
    }

    /**
     * @return mixed[]
     */
    public function serialize(StructInterface $structure): array
    {
        return $this->serializeUtility->serialize($structure);
    }

    /**
     * @template T of StructInterface
     * @param object|array<mixed> $data
     * @param class-string<T> $type
     * @return T
     */
    public function deserialize(object|array $data, string $type): StructInterface
    {
        return $this->unSerializeUtility->unSerialize($data, $type);
    }

    public function serializeToJson(StructInterface $structure): string
    {
        $dataArray = $this->serialize($structure);
        $dataJson = \json_encode($dataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($dataJson === false) {
            throw new UnexpectedException(1675972511);
        }
        return $dataJson;
    }

    /**
     * @template T of StructInterface
     * @param string $dataJson
     * @param class-string<T> $type
     * @return T
     */
    public function deserializeFromJson(string $dataJson, string $type): StructInterface
    {
        try {
            /** @var mixed[] $dataArray */
            $dataArray = \json_decode($dataJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new \LogicException('Can not parse the given JSON string', 1675972764, $exception);
        }
        return $this->unSerializeUtility->unSerialize($dataArray, $type);
    }
}
