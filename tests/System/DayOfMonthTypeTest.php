<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\System;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQL94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\System\DayOfMonth;
use Mediagone\Doctrine\Types\Common\System\DayOfMonthType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\System\DayOfMonth
 */
final class DayOfMonthTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private DayOfMonthType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(DayOfMonthType::NAME)) {
            Type::addType(DayOfMonthType::NAME, DayOfMonthType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(DayOfMonthType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(DayOfMonthType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSql94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('INT', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('INT', $this->type->getSQLDeclaration(['length' => '70'], new MySqlPlatform()));
        self::assertSame('INT', $this->type->getSQLDeclaration(['length' => '200'], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $value = $this->type->convertToDatabaseValue(DayOfMonth::fromInt(10), new MySqlPlatform());
        
        self::assertSame(10, $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = $this->type->convertToPHPValue(10, new MySqlPlatform());
        
        self::assertInstanceOf(DayOfMonth::class, $value);
        self::assertSame(10, $value->toInteger());
    }
    
    
    
}
