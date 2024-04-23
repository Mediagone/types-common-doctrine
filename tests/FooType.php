<?php

namespace Tests\Mediagone\Doctrine\Types\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;


final class FooType extends Type
{
    public const NAME = 'common_foo';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if (method_exists($platform, 'getStringTypeDeclarationSQL')) {
            return $platform->getStringTypeDeclarationSQL([
                'length' => 10,
            ]);
        } else {
            return $platform->getVarcharTypeDeclarationSQL([
                'length' => 10,
            ]);
        }
    }

    public function getName()
    {
        return self::NAME;
    }

}
