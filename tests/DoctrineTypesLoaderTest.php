<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common;

use Doctrine\DBAL\Types\Type;
use Mediagone\Doctrine\Types\Common\DoctrineTypesLoader;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\DoctrineTypesLoader
 */
final class DoctrineTypesLoaderTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_register_a_type() : void
    {
        self::assertFalse(Type::hasType(FooType::NAME));
        
        $loader = new DoctrineTypesLoader();
        $loader->registerTypes([FooType::class]);
        
        self::assertTrue(Type::hasType(FooType::NAME));
        self::assertInstanceOf(FooType::class, Type::getType(FooType::NAME));
    }
    
    
    
}
