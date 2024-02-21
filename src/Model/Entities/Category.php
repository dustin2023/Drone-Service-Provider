<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_category')]
class Category
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string')]
    private string $category_type;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCategoryType(): string
    {
        return $this->category_type;
    }

    /**
     * @param string $category_type
     */
    public function setCategoryType(string $category_type): void
    {
        $this->category_type = $category_type;
    }

}