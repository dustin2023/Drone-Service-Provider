<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Model\Entities;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping as ORM;
use Maurerd\Webentwicklung\Model\EnumCustomerType;

Type::addType('enumCustomerType', EnumCustomerType::class);


/**
 *
 */
#[ORM\Entity]
#[ORM\Table(name: 'customer')]
class Customer
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
    private string $username = '';

    /**
     * @var string
     */
    #[ORM\Column(type: 'string')]
    private string $password = '';

    /**
     * @var string
     */
    #[ORM\Column(name: 'customer_type', type: 'string',length: 50, nullable: false)]
    private string $customer_type;

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
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getCustomerType(): EnumCustomerType
    {
        return new EnumCustomerType($this->customer_type);
    }

    public function setCustomerType(EnumCustomerType $customer_type): void
    {
        $this->customer_type = $customer_type->getValue();
    }
}
