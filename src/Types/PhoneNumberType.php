<?php

declare(strict_types=1);

namespace Brick\PhoneNumber\Doctrine\Types;

use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberParseException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\Type;
use Override;
use Throwable;

use function is_string;

final class PhoneNumberType extends Type
{
    #[Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            try {
                $value = PhoneNumber::parse($value);
            } catch (Throwable $exception) {
                throw InvalidType::new(
                    $value,
                    self::class,
                    [PhoneNumber::class, 'null', 'phone number string'],
                    $exception,
                );
            }
        }

        if ($value instanceof PhoneNumber) {
            return (string) $value;
        }

        throw InvalidType::new(
            $value,
            self::class,
            [PhoneNumber::class, 'null', 'phone number string'],
        );
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?PhoneNumber
    {
        if ($value === null) {
            return null;
        }

        try {
            /** @phpstan-ignore cast.string */
            return PhoneNumber::parse((string) $value);
        } catch (PhoneNumberParseException $e) {
            throw ValueNotConvertible::new(
                $value,
                PhoneNumber::class,
                $e->getMessage(),
                $e,
            );
        }
    }

    #[Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        // E.164 defines the maximum length as 15 digits, to which we add 1 char for the leading + sign.
        if (! isset($column['length'])) {
            $column['length'] = 16;
        }

        return $platform->getStringTypeDeclarationSQL($column);
    }
}
