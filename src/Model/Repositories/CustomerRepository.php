<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Model\Repositories;

use Maurerd\Webentwicklung\Model\Entities\Customer;

/**
 *
 */
class CustomerRepository extends AbstractRepository
{
    /**
     * @return string
     */
    protected function getEntityClassName(): string
    {
        return Customer::class;
    }

    /**
     * @param string $username
     * @return Customer|null
     */
    public function getByUsername(string $username): ?Customer
    {
        return $this->ormRepository->findOneBy(['username' => $username]);
    }

    /**
     * @param int $id
     * @return Customer|null
     */
    public function getByCustomerId(int $id): ?Customer
    {
        return $this->ormRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param Customer $customer
     * @return Customer|null
     */
    public function update(Customer $customer): ?Customer
    {
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
        return $customer;
    }

    /**
     * @param Customer $customer
     * @return Customer
     */
    public function insert(Customer $customer): Customer
    {
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
        return $customer;
    }

}
