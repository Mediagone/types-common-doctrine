<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\System;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\System\Date;
use Mediagone\Doctrine\Types\Common\System\DateType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\System\DateType
 */
final class DateTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private DateType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(DateType::NAME)) {
            Type::addType(DateType::NAME, DateType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(DateType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(DateType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQLPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $platform = new MySqlPlatform();
        $utcDate = Date::today();
        $value = $this->type->convertToDatabaseValue($utcDate, $platform);
        
        self::assertSame($value, $utcDate->format($platform->getDateFormatString()));
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $platform = new MySqlPlatform();
        
        $referenceDate = Date::today();
        $referenceValue = $referenceDate->format($platform->getDateFormatString());
        
        $value = $this->type->convertToPHPValue($referenceValue, new MySqlPlatform());
        
        self::assertInstanceOf(Date::class, $value);
        self::assertSame((string)$referenceDate, (string)$value);
    }
    
    
    
}
