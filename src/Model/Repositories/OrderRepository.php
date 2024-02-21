<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Model\Repositories;

use Maurerd\Webentwicklung\Model\Entities\Order;

/**
 *
 */
class OrderRepository extends AbstractRepository
{
    /**
     * @param Order $blogPost
     * @return void
     */
    public function add(Order $blogPost): void
    {
        $this->entityManager->persist($blogPost);
        $this->entityManager->flush();
    }

    /**
     * @param string $urlKey
     * @return Order|null
     */
    public function findByUrlKey(string $urlKey): ?Order
    {
        return $this->ormRepository->findOneBy(['urlKey' => $urlKey]);
    }

    /**
     * @return string
     */
    protected function getEntityClassName(): string
    {
        return Order::class;
    }
}
