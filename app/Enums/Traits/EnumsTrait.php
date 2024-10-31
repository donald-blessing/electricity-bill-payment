<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use BackedEnum;
use Throwable;

trait EnumsTrait
{
    public static function toArray(bool $values = false): array
    {
        if ($values) {
            return array_column(self::cases(), 'value', 'value');
        }

        return array_column(self::cases(), 'value', 'name');
    }

    public static function toJson(): ?string
    {
        $enumKeyValuePairs = array_reduce(
            self::cases(),
            fn ($result, $case) => $result + [$case->name => $case->value],
            []
        );

        return json_encode($enumKeyValuePairs);
    }

    /** @throws Throwable */
    public static function isValid(BackedEnum|string $enumValue): bool
    {
        if ($enumValue instanceof BackedEnum) {
            return in_array($enumValue, self::cases(), true);
        }

        return in_array($enumValue, static::toArray(), true);
    }

    public function getLabel(): ?string
    {
        return $this->name;
    }

    /** @throws Throwable */
    public function equals(BackedEnum|string $enumValue): bool
    {
        if ($enumValue instanceof BackedEnum) {
            return $enumValue === $this;
        }

        return self::tryFrom($enumValue) === $this;
    }

    public function label(): string
    {
        $value = type($this->value)->asString();

        $value = preg_replace('/([a-z])([A-Z])/', '$1 $2', $value);

        $value = str_replace(['-', '_'], ' ', $value);

        $value = preg_replace('/\s+/', ' ', $value);

        return ucwords(mb_strtolower(type($value)->asString()));
    }
}
