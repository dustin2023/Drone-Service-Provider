<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Model\Entities;

use Doctrine\ORM\Mapping as ORM;


/**
 * @Serializer\ExclusionPolicy("all")
 */
#[ORM\Entity]
#[ORM\Table(name: 'customer_order')]
class Order implements \JsonSerializable
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
    private string $address;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string')]
    private string $description;

    /**
     * @var int
     */
    #[ORM\Column(name: 'category_id', type: 'integer')]
    private int $categoryId;


    /**
     * @var int
     */
    #[ORM\Column(name: 'customer_id', type: 'integer')]
    private int $customerId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'url_key', type: 'string')]
    private string $urlKey;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string')]
    private string $package;

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
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @param int $customerId
     */
    public function setCustomerId(int $customerId): void
    {
        $this->customerId = $customerId;
    }

    /**
     * @return string
     */
    public function getUrlKey(): string
    {
        return $this->urlKey;
    }

    /**
     * @param string $urlKey
     */
    public function setUrlKey(string $urlKey): void
    {
        $this->urlKey = $urlKey;
    }

    /**
     * @return string
     */
    public function getPackage(): string
    {
        return $this->package;
    }

    /**
     * @param string $package
     */
    public function setPackage(string $package): void
    {
        $this->package = $package;
    }



    /**
     * @return \stdClass
     */
    public function jsonSerialize(): object
    {
        $object = new \stdClass();
        $object->address = $this->getAddress();
        $object->customerId = $this->getCustomerId();
        $object->description = $this->getDescription();

        return $object;
    }
}
