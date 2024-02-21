<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Model\Repositories;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\DBAL\Types\Type;
use Maurerd\Webentwicklung\Model\EnumCustomerType;
use Maurerd\Webentwicklung\Model\EnumType;
use Maurerd\Webentwicklung\Model\EntityManagerFactory;


/**
 *
 */
abstract class AbstractRepository
{
    /**
     * @var ObjectRepository
     */
    protected ObjectRepository $ormRepository;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     *
     * @throws Exception
     */
    public function __construct()
    {
        $entityManagerFactory = new EntityManagerFactory();
        try {
            $this->entityManager = $entityManagerFactory->getInstance();
        } catch (Exception|MissingMappingDriverImplementation $e) {
        }
        $this->ormRepository = $this->entityManager->getRepository($this->getEntityClassName());
    }

    /**
     * @return string
     */
    abstract protected function getEntityClassName(): string;
}
