<?php

declare(strict_types=1);

namespace Brick\PhoneNumber\Doctrine\Tests\Types;

use Brick\PhoneNumber\Doctrine\Types\PhoneNumberType;
use Brick\PhoneNumber\PhoneNumber;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

class PhoneNumberTypeTest extends TestCase
{
    #[DataProvider('providerConvertToDatabaseValue')]
    public function testConvertToDatabaseValue(?PhoneNumber $value, ?string $expectedValue): void
    {
        $type = $this->getPhoneNumberType();
        $actualValue = $type->convertToDatabaseValue($value, new SQLitePlatform());

        self::assertSame($expectedValue, $actualValue);
    }

    public static function providerConvertToDatabaseValue(): array
    {
        return [
            [null, null],
            [PhoneNumber::parse('+331234567890'), '+331234567890'],
            [PhoneNumber::parse('+447553848951'), '+447553848951'],
        ];
    }

    #[DataProvider('providerConvertToDatabaseValueWithInvalidValue')]
    public function testConvertToDatabaseValueWithInvalidValue(mixed $value): void
    {
        $type = $this->getPhoneNumberType();

        $this->expectException(ConversionException::class);
        $type->convertToDatabaseValue($value, new SQLitePlatform());
    }

    public static function providerConvertToDatabaseValueWithInvalidValue(): array
    {
        return [
            [123],
            [false],
            [true],
            ['string'],
            [new stdClass()],
        ];
    }

    #[DataProvider('providerConvertToPHPValue')]
    public function testConvertToPHPValue(?string $value): void
    {
        $type = $this->getPhoneNumberType();
        $convertedValue = $type->convertToPHPValue($value, new SQLitePlatform());

        if ($value === null) {
            self::assertNull($convertedValue);
        } else {
            self::assertInstanceOf(PhoneNumber::class, $convertedValue);
            self::assertSame($value, (string) $convertedValue);
        }
    }

    public static function providerConvertToPHPValue(): array
    {
        return [
            [null],
            ['+331234567890'],
            ['+447553848951'],
        ];
    }

    #[DataProvider('providerConvertToPHPValueWithInvalidValue')]
    public function testConvertToPHPValueWithInvalidValue(mixed $value): void
    {
        $type = $this->getPhoneNumberType();

        $this->expectException(ConversionException::class);
        $type->convertToPHPValue($value, new SQLitePlatform());
    }

    public static function providerConvertToPHPValueWithInvalidValue(): array
    {
        return [
            [123],
            [''],
            ['+33'],
        ];
    }

    private function getPhoneNumberType(): PhoneNumberType
    {
        return Type::getType('PhoneNumber');
    }
}
