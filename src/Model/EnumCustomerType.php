<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Model;

use AllowDynamicProperties;

/**
 *
 */
#[AllowDynamicProperties] class EnumCustomerType extends EnumType
{
    protected $name = 'enumCustomerType';
    protected $values = ['privatKunde', 'Geschäftskunde'];
    private string $value;

    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
    public function getValue(): string
    {
        return $this->value;
    }


    public static function getType($name): string
    {
        return static::class;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function setValues(string $value): void
    {
        if (!in_array($value, $this->values)) {
            throw new \InvalidArgumentException('Invalid \'enumCustomerType\' value.');
        }
        $this->value = $value;
    }
}


