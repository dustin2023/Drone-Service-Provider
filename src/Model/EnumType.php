<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Model;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class EnumType extends Type
{
    protected $name;
    protected $values = array();
    const ENUM_CUSTOMER_TYPE = 'enumCustomerType';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'ENUM("privatKunde", "Geschäftskunde")';
    }

    public function getName()
    {
        return self::ENUM_CUSTOMER_TYPE;
    }


    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, $this->values)) {
            throw new \InvalidArgumentException("Invalid '" . $this->name . "' value.");
        }
        return $value;
    }


    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    public function getMappedDatabaseTypes(AbstractPlatform $platform)
    {
        return [static::getType($this->name) => 'string'];
    }

    public static function getType($name): string
    {
        return static::class . '_' . $name;
    }
}
