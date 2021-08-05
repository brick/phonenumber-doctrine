<?php

declare(strict_types=1);

namespace Brick\PhoneNumber\Doctrine\Types;

use Brick\PhoneNumber\PhoneNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

final class PhoneNumberType extends Type
{
    public function getName()
    {
        return 'PhoneNumber';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof PhoneNumber) {
            return (string) $value;
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [PhoneNumber::class, 'null']);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return PhoneNumber::parse($value);
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['string', 'null']);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        // E.164 defines the maximum length as 15 digits, to which we add 1 char for the leading + sign.
        if (!isset($column['length'])) {
            $column['length'] = 16;
        }

        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
