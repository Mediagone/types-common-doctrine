<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Text;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Text\NameDigit;
use Mediagone\Doctrine\Types\Common\Text\NameDigitType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Text\NameDigitType
 */
final class NameDigitTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private NameDigitType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(NameDigitType::NAME)) {
            Type::addType(NameDigitType::NAME, NameDigitType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(NameDigitType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(NameDigitType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQLPlatform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR('.NameDigit::MAX_LENGTH.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('VARCHAR('.NameDigit::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => 70], new MySqlPlatform()));
        self::assertSame('VARCHAR('.NameDigit::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => 200], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $name = NameDigit::fromString('Test name');
        $value = $this->type->convertToDatabaseValue($name, new MySqlPlatform());
        
        self::assertSame($value, (string)$name);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'Test name';
        $name = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(NameDigit::class, $name);
        self::assertSame($value, (string)$name);
    }
    
    
    
}
