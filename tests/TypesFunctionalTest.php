<?php

declare(strict_types=1);

namespace Brick\PhoneNumber\Doctrine\Tests;

use Brick\PhoneNumber\Doctrine\Tests\Entity\User;
use Brick\PhoneNumber\PhoneNumber;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Tools\SchemaTool;

class TypesFunctionalTest extends AbstractFunctionalTestCase
{
    public function testCreateSchema(): Connection
    {
        $connection = self::createConnection();
        $entityManager = self::createEntityManager($connection);
        $schemaTool = new SchemaTool($entityManager);

        $classMetadata = $entityManager->getClassMetadata(User::class);

        $sql = $schemaTool->getUpdateSchemaSql([$classMetadata]);
        self::assertCount(1, $sql);
        $sql = $sql[0];

        self::assertStringContainsString('phoneNumber VARCHAR(16) DEFAULT NULL', $sql);

        $connection->executeStatement($sql);

        return $connection;
    }

    /**
     * @depends testCreateSchema
     */
    public function testSaveNull(Connection $connection): Connection
    {
        $em = self::createEntityManager($connection);
        self::truncateEntityTable($em);

        $entity = new User();

        $em->persist($entity);
        $em->flush();

        // https://github.com/sebastianbergmann/phpunit/issues/3016
        self::assertTrue(true);

        return $connection;
    }

    /**
     * @depends testSaveNull
     */
    public function testLoadNull(Connection $connection): void
    {
        $em = self::createEntityManager($connection);

        $entity = self::getFirstEntity($em);

        self::assertNotNull($entity);
        self::assertNull($entity->phoneNumber);
    }

    /**
     * @depends testCreateSchema
     */
    public function testSaveValues(Connection $connection): Connection
    {
        $em = self::createEntityManager($connection);
        self::truncateEntityTable($em);

        $entity = new User();

        $entity->phoneNumber = PhoneNumber::parse('+331234567890');

        $em->persist($entity);
        $em->flush();

        // https://github.com/sebastianbergmann/phpunit/issues/3016
        self::assertTrue(true);

        return $connection;
    }

    /**
     * @depends testSaveValues
     */
    public function testLoadValues(Connection $connection): void
    {
        $em = self::createEntityManager($connection);

        $entity = self::getFirstEntity($em);

        self::assertNotNull($entity);

        self::assertInstanceOf(PhoneNumber::class, $entity->phoneNumber);
        self::assertSame('+331234567890', (string) $entity->phoneNumber);
    }
}
