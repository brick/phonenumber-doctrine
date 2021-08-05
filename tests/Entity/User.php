<?php

declare(strict_types=1);

namespace Brick\PhoneNumber\Doctrine\Tests\Entity;

use Brick\PhoneNumber\PhoneNumber;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="PhoneNumber", nullable=true)
     *
     * @var PhoneNumber|null
     */
    public $phoneNumber = null;
}
