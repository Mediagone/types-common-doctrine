<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Geo;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Doctrine\Types\Common\Geo\CountryType;
use Mediagone\Types\Common\Geo\Country;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Geo\CountryType
 */
final class CountryTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private CountryType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(CountryType::NAME)) {
            Type::addType(CountryType::NAME, CountryType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(CountryType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(CountryType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQLPlatform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('CHAR(3)', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('CHAR(3)', $this->type->getSQLDeclaration(['length' => 70], new MySqlPlatform()));
        self::assertSame('CHAR(3)', $this->type->getSQLDeclaration(['length' => 200], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $country = Country::fromAlpha3('FRA');
        $value = $this->type->convertToDatabaseValue($country, new MySqlPlatform());
        
        self::assertSame($value, $country->getAlpha3());
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'FRA';
        $country = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(Country::class, $country);
        self::assertSame($value, $country->getAlpha3());
    }
    
    
    
}
