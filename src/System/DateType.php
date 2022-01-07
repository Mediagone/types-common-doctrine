<?php declare(strict_types=1);

namespace Mediagone\Doctrine\Types\Common\System;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeType;
use Mediagone\Types\Common\System\Date;


class DateType extends DateTimeType
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    public const NAME = 'common_date';
    
    
    
    //========================================================================================================
    // Type implementation
    //========================================================================================================
    
    public function getName() : string
    {
        return self::NAME;
    }
    
    
    /**
     * Adds an SQL comment to typehint the actual Doctrine Type for reverse schema engineering.
     */
    final public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
    
    
    /**
     * Converts a value from its database representation to its PHP representation of this type.
     *
     * @param string|null $value The value to convert.
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) : ?Date
    {
        return $value !== null ? Date::fromFormat($value, $platform->getDateFormatString()) : null;
    }
    
    
    /**
     * Converts a value from its PHP representation to its database representation of this type.
     *
     * @param Date|null $value The value to convert.
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) : ?string
    {
        return $value ? $value->format($platform->getDateFormatString()) : null;
    }
    
    
    
}
