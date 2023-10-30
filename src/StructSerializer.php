<?php

declare(strict_types=1);

namespace Struct\Serializing;

use Exception\Unexpected\UnexpectedException;
use Struct\Contracts\StructInterface;
use Struct\Serializing\Enum\KeyConvert;
use Struct\Serializing\Private\Utility\SerializeUtility;
use Struct\Serializing\Private\Utility\UnSerializeUtility;

class StructSerializer
{
    /**
     * @return mixed[]
     */
    public static function serialize(StructInterface $structure, ?KeyConvert $keyConvert = null): array
    {
        $serializeUtility = new SerializeUtility();
        return $serializeUtility->serialize($structure, $keyConvert);
    }

    /**
     * @template T of StructInterface
     * @param object|array<mixed> $data
     * @param class-string<T> $type
     * @return T
     */
    public static function deserialize(object|array $data, string $type, ?KeyConvert $keyConvert = null): StructInterface
    {
        $unSerializeUtility = new UnSerializeUtility();
        return $unSerializeUtility->unSerialize($data, $type, $keyConvert);
    }

    public static function serializeToJson(StructInterface $structure, ?KeyConvert $keyConvert = null): string
    {
        $dataArray = self::serialize($structure, $keyConvert);
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
    public static function deserializeFromJson(string $dataJson, string $type, ?KeyConvert $keyConvert = null): StructInterface
    {
        try {
            /** @var mixed[] $dataArray */
            $dataArray = \json_decode($dataJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new \LogicException('Can not parse the given JSON string', 1675972764, $exception);
        }
        return self::deserialize($dataArray, $type, $keyConvert);
    }
}
