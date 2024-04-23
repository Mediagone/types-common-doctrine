<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Geo;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Doctrine\Types\Common\Geo\CityType;
use Mediagone\Types\Common\Geo\City;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Geo\CityType
 */
final class CityTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private CityType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(CityType::NAME)) {
            Type::addType(CityType::NAME, CityType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(CityType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(CityType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQLPlatform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR('.City::MAX_LENGTH.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('VARCHAR('.City::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => 70], new MySqlPlatform()));
        self::assertSame('VARCHAR('.City::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => 200], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $city = City::fromString('Test city');
        $value = $this->type->convertToDatabaseValue($city, new MySqlPlatform());
        
        self::assertSame($value, (string)$city);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'Test city';
        $city = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(City::class, $city);
        self::assertSame($value, (string)$city);
    }
    
    
    
}
