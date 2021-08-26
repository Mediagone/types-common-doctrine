<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Geo;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSql94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Doctrine\Types\Common\Geo\LongitudeType;
use Mediagone\Types\Common\Geo\Longitude;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Geo\LongitudeType
 */
final class LongitudeTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private LongitudeType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(LongitudeType::NAME)) {
            Type::addType(LongitudeType::NAME, LongitudeType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(LongitudeType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(LongitudeType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSql94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('DOUBLE PRECISION', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('DOUBLE PRECISION', $this->type->getSQLDeclaration(['scale' => '6'], new MySqlPlatform()));
        self::assertSame('DOUBLE PRECISION', $this->type->getSQLDeclaration(['precision' => '10'], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $value = 120.123456;
        $latitude = $this->type->convertToDatabaseValue(Longitude::fromFloat($value), new MySqlPlatform());
        
        self::assertSame($value, $latitude);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 120.123456;
        $latitude = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(Longitude::class, $latitude);
        self::assertSame($value, $latitude->toFloat());
    }
    
    
    
}
