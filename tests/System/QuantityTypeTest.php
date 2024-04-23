<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\System;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\System\Quantity;
use Mediagone\Doctrine\Types\Common\System\QuantityType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\System\QuantityType
 */
final class QuantityTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private QuantityType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(QuantityType::NAME)) {
            Type::addType(QuantityType::NAME, QuantityType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(QuantityType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(QuantityType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQLPlatform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('INT', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('INT', $this->type->getSQLDeclaration(['length' => 70], new MySqlPlatform()));
        self::assertSame('INT', $this->type->getSQLDeclaration(['length' => 200], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $value = $this->type->convertToDatabaseValue(Quantity::fromInt(10), new MySqlPlatform());
        
        self::assertSame(10, $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = $this->type->convertToPHPValue(10, new MySqlPlatform());
        
        self::assertInstanceOf(Quantity::class, $value);
        self::assertSame(10, $value->toInteger());
    }
    
    
    
}
