<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\System;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSql94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Common\Types\System\DateTimeUTC;
use Mediagone\Doctrine\Types\Common\System\DateTimeUTCType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\System\DateTimeUTCType
 */
final class DateTimeUTCTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private DateTimeUTCType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(DateTimeUTCType::NAME)) {
            Type::addType(DateTimeUTCType::NAME, DateTimeUTCType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(DateTimeUTCType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(DateTimeUTCType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSql94Platform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $platform = new MySqlPlatform();
        $utcDate = DateTimeUTC::now();
        $value = $this->type->convertToDatabaseValue($utcDate, $platform);
        
        self::assertSame($value, $utcDate->format($platform->getDateTimeFormatString()));
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $platform = new MySqlPlatform();
        
        $referenceDate = DateTimeUTC::now();
        $referenceValue = $referenceDate->format($platform->getDateTimeFormatString());
        
        $value = $this->type->convertToPHPValue($referenceValue, new MySqlPlatform());
        
        self::assertInstanceOf(DateTimeUTC::class, $value);
        self::assertSame((string)$referenceDate, (string)$value);
    }
    
    
    
}
