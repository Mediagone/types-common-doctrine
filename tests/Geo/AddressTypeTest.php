<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Geo;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Doctrine\Types\Common\Geo\AddressType;
use Mediagone\Types\Common\Geo\Address;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Geo\AddressType
 */
final class AddressTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private AddressType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(AddressType::NAME)) {
            Type::addType(AddressType::NAME, AddressType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(AddressType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(AddressType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQLPlatform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR('.Address::MAX_LENGTH.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('VARCHAR('.Address::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => 70], new MySqlPlatform()));
        self::assertSame('VARCHAR('.Address::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => 200], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $address = Address::fromString('Test address');
        $value = $this->type->convertToDatabaseValue($address, new MySqlPlatform());
        
        self::assertSame($value, (string)$address);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'Test address';
        $address = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(Address::class, $address);
        self::assertSame($value, (string)$address);
    }
    
    
    
}
