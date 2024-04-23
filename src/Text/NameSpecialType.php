<?php declare(strict_types=1);

namespace Mediagone\Doctrine\Types\Common\Text;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Text\NameSpecial;


class NameSpecialType extends Type
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    public const NAME = 'common_namespecial';
    
    
    
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
    final public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) : string
    {
        if (method_exists($platform, 'getStringTypeDeclarationSQL')) {
            return $platform->getStringTypeDeclarationSQL([
                'length' => NameSpecial::MAX_LENGTH,
            ]);
        } else {
            return $platform->getVarcharTypeDeclarationSQL([
                'length' => NameSpecial::MAX_LENGTH,
            ]);
        }
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
     * @param mixed $value The value to convert.
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) : ?NameSpecial
    {
        return $value !== null ? NameSpecial::fromString($value) : null;
    }
    
    
    /**
     * Converts a value from its PHP representation to its database representation of this type.
     *
     * @param NameSpecial|null $value The value to convert.
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) : ?string
    {
        return $value ? (string)$value : null;
    }
    
    
    
}
