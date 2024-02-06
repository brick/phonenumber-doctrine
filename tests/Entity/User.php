<?php

declare(strict_types=1);

namespace Brick\PhoneNumber\Doctrine\Tests\Entity;

use Brick\PhoneNumber\PhoneNumber;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    public int $id;

    #[ORM\Column(type: 'PhoneNumber', nullable: true)]
    public ?PhoneNumber $phoneNumber = null;
}
