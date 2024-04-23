<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Geo;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Doctrine\Types\Common\Geo\LatitudeType;
use Mediagone\Types\Common\Geo\Latitude;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Geo\LatitudeType
 */
final class LatitudeTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private LatitudeType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(LatitudeType::NAME)) {
            Type::addType(LatitudeType::NAME, LatitudeType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(LatitudeType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(LatitudeType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQLPlatform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('DOUBLE PRECISION', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('DOUBLE PRECISION', $this->type->getSQLDeclaration(['scale' => '6'], new MySqlPlatform()));
        self::assertSame('DOUBLE PRECISION', $this->type->getSQLDeclaration(['precision' => '10'], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $value = 20.123456;
        $latitude = $this->type->convertToDatabaseValue(Latitude::fromFloat($value), new MySqlPlatform());
        
        self::assertSame($value, $latitude);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 20.123456;
        $latitude = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(Latitude::class, $latitude);
        self::assertSame($value, $latitude->toFloat());
    }
    
    
    
}
