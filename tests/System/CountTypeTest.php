<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\System;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\System\Count;
use Mediagone\Doctrine\Types\Common\System\CountType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\System\CountType
 */
final class CountTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private CountType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(CountType::NAME)) {
            Type::addType(CountType::NAME, CountType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(CountType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(CountType::NAME, $this->type->getName());
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
        $value = $this->type->convertToDatabaseValue(Count::fromInt(10), new MySqlPlatform());
        
        self::assertSame(10, $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = $this->type->convertToPHPValue(10, new MySqlPlatform());
        
        self::assertInstanceOf(Count::class, $value);
        self::assertSame(10, $value->toInteger());
    }
    
    
    
}
