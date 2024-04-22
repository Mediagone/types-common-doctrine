<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Business;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Business\Iban;
use Mediagone\Doctrine\Types\Common\Business\IbanType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Business\IbanType
 */
final class IbanTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private IbanType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(IbanType::NAME)) {
            Type::addType(IbanType::NAME, IbanType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(IbanType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(IbanType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQLPlatform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR('.Iban::MAX_LENGTH.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('VARCHAR('.Iban::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => 70], new MySqlPlatform()));
        self::assertSame('VARCHAR('.Iban::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => 200], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $name = Iban::fromString('CH9300762011623852957');
        $value = $this->type->convertToDatabaseValue($name, new MySqlPlatform());
        
        self::assertSame($value, (string)$name);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'CH9300762011623852957';
        $name = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(Iban::class, $name);
        self::assertSame($value, (string)$name);
    }
    
    
    
}
