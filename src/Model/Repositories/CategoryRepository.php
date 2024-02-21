<?php

namespace Maurerd\Webentwicklung\Model\Repositories;


use Maurerd\Webentwicklung\Model\Entities\Category;

/**
 *
 */
class CategoryRepository extends AbstractRepository
{
    /**
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category
    {
        return $this->entityManager->find(Category::class, $id);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Category::class)->findAll();
    }

    /**
     * @return string
     */
    protected function getEntityClassName(): string
    {
        return Category::class;
    }
}