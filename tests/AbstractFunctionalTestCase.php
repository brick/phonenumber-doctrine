<?php

declare(strict_types=1);

namespace Brick\PhoneNumber\Doctrine\Tests;

use Brick\PhoneNumber\Doctrine\Tests\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

abstract class AbstractFunctionalTestCase extends TestCase
{
    final protected static function createConnection(): Connection
    {
        return DriverManager::getConnection(['url' => 'sqlite:///:memory:']);
    }

    final protected static function createEntityManager(Connection $connection): EntityManager
    {
        return EntityManager::create($connection, self::createConfiguration());
    }

    private static function createConfiguration(): Configuration
    {
        $config = new Configuration();

        $driverImpl = $config->newDefaultAnnotationDriver([__DIR__ . '/tests/Entity'], false);
        $config->setMetadataDriverImpl($driverImpl);

        $config->setProxyDir(sys_get_temp_dir());
        $config->setProxyNamespace('Brick\PhoneNumber\Doctrine\Tests\Proxy');

        return $config;
    }

    final protected static function truncateEntityTable(EntityManager $em): void
    {
        $em->createQueryBuilder()
            ->delete(User::class, 's')
            ->getQuery()
            ->execute();
    }

    final protected function getFirstEntity(EntityManager $em): ?User
    {
        return $em->createQueryBuilder()
            ->select('s')
            ->from(User::class, 's')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
