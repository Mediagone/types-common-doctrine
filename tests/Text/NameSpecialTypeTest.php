<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Text;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSql94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Doctrine\Types\Common\Text\NameSpecialType;
use Mediagone\Types\Common\Text\NameSpecial;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Text\NameSpecialType
 */
final class NameSpecialTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private NameSpecialType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(NameSpecialType::NAME)) {
            Type::addType(NameSpecialType::NAME, NameSpecialType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(NameSpecialType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(NameSpecialType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSql94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR('.NameSpecial::MAX_LENGTH.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('VARCHAR('.NameSpecial::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => '70'], new MySqlPlatform()));
        self::assertSame('VARCHAR('.NameSpecial::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => '200'], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $name = NameSpecial::fromString('Test name');
        $value = $this->type->convertToDatabaseValue($name, new MySqlPlatform());
        
        self::assertSame($value, (string)$name);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'Test name';
        $name = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(NameSpecial::class, $name);
        self::assertSame($value, (string)$name);
    }
    
    
    
}
