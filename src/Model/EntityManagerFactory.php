<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Model;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class EntityManagerFactory
{
    /**
     * @return EntityManagerInterface
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
     */
    public function getInstance(): EntityManagerInterface
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: array(__DIR__ . "/Entities"),
            isDevMode: true,
        );

        // configuring the database connection
        $connection = DriverManager::getConnection(
            [
                'driver' => 'pdo_mysql',
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
                'host' => $_ENV['DB_HOST'],
            ], $config);

        // obtaining the entity manager
        return new EntityManager($connection, $config);
    }
}
