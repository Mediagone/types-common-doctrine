<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Text;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSql94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Text\TextMedium;
use Mediagone\Doctrine\Types\Common\Text\TextMediumType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Text\TextMediumType
 */
final class TextMediumTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private TextMediumType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(TextMediumType::NAME)) {
            Type::addType(TextMediumType::NAME, TextMediumType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(TextMediumType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(TextMediumType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSql94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('MEDIUMTEXT', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('MEDIUMTEXT', $this->type->getSQLDeclaration(['length' => '70'], new MySqlPlatform()));
        self::assertSame('MEDIUMTEXT', $this->type->getSQLDeclaration(['length' => '200'], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $name = TextMedium::fromString('Lorem ipsum...');
        $value = $this->type->convertToDatabaseValue($name, new MySqlPlatform());
        
        self::assertSame($value, (string)$name);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'Lorem ipsum...';
        $name = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(TextMedium::class, $name);
        self::assertSame($value, (string)$name);
    }
    
    
    
}
