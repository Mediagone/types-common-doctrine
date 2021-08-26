<?php declare(strict_types=1);

namespace Mediagone\Doctrine\Types\Common\Geo;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Geo\Longitude;


final class LongitudeType extends Type
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    public const NAME = 'common_longitude';
    
    
    
    //========================================================================================================
    // Type implementation
    //========================================================================================================
    
    public function getName() : string
    {
        return self::NAME;
    }
    
    
    /**
     * Gets the SQL declaration snippet for a field of this type.
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) : string
    {
        return $platform->getFloatDeclarationSQL([]);
    }
    
    
    /**
     * Adds an SQL comment to typehint the actual Doctrine Type for reverse schema engineering.
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
    
    
    /**
     * Converts a value from its database representation to its PHP representation of this type.
     *
     * @param float|null $value The value to convert.
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) : ?Longitude
    {
        return $value !== null ? Longitude::fromFloat($value) : null;
    }
    
    
    /**
     * Converts a value from its PHP representation to its database representation of this type.
     *
     * @param Longitude|null $value The value to convert.
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) : ?float
    {
        return $value ? $value->toFloat() : null;
    }
    
    
    
}
