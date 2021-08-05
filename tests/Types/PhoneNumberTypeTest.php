<?php

declare(strict_types=1);

namespace Brick\PhoneNumber\Doctrine\Tests\Types;

use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\Doctrine\Types\PhoneNumberType;
use Brick\PhoneNumber\PhoneNumberParseException;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use stdClass;

class PhoneNumberTypeTest extends TestCase
{
    private function getPhoneNumberType(): PhoneNumberType
    {
        return Type::getType('PhoneNumber');
    }

    /**
     * @dataProvider providerConvertToDatabaseValue
     */
    public function testConvertToDatabaseValue(?PhoneNumber $value, ?string $expectedValue): void
    {
        $type = $this->getPhoneNumberType();
        $actualValue = $type->convertToDatabaseValue($value, new SqlitePlatform());

        self::assertSame($expectedValue, $actualValue);
    }

    public function providerConvertToDatabaseValue(): array
    {
        return [
            [null, null],
            [PhoneNumber::parse('+331234567890'), '+331234567890'],
            [PhoneNumber::parse('+447553848951'), '+447553848951'],
        ];
    }

    /**
     * @dataProvider providerConvertToDatabaseValueWithInvalidValue
     */
    public function testConvertToDatabaseValueWithInvalidValue($value): void
    {
        $type = $this->getPhoneNumberType();

        $this->expectException(ConversionException::class);
        $type->convertToDatabaseValue($value, new SqlitePlatform());
    }

    public function providerConvertToDatabaseValueWithInvalidValue(): array
    {
        return [
            [123],
            [false],
            [true],
            ['string'],
            [new stdClass()],
        ];
    }

    /**
     * @dataProvider providerConvertToPHPValue
     */
    public function testConvertToPHPValue(?string $value): void
    {
        $type = $this->getPhoneNumberType();
        $convertedValue = $type->convertToPHPValue($value, new SqlitePlatform());

        if ($value === null) {
            self::assertNull($convertedValue);
        } else {
            self::assertInstanceOf(PhoneNumber::class, $convertedValue);
            self::assertSame($value, (string) $convertedValue);
        }
    }

    public function providerConvertToPHPValue(): array
    {
        return [
            [null],
            ['+331234567890'],
            ['+447553848951'],
        ];
    }

    /**
     * @dataProvider providerConvertToPHPValueWithInvalidValue
     */
    public function testConvertToPHPValueWithInvalidValue($value, string $expectedExceptionClass): void
    {
        $type = $this->getPhoneNumberType();

        $this->expectException($expectedExceptionClass);
        $type->convertToPHPValue($value, new SqlitePlatform());
    }

    public function providerConvertToPHPValueWithInvalidValue(): array
    {
        return [
            [123, ConversionException::class],
            ['', PhoneNumberParseException::class],
            ['+33', PhoneNumberParseException::class],
        ];
    }
}
