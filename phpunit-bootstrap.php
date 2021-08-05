<?php

declare(strict_types=1);

use Brick\PhoneNumber\Doctrine\Types\PhoneNumberType;
use Doctrine\DBAL\Types\Type;

require __DIR__ . '/vendor/autoload.php';

Type::addType('PhoneNumber', PhoneNumberType::class);
