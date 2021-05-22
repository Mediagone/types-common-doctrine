<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Graphics;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSql94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Doctrine\Types\Common\Graphics\ColorType;
use Mediagone\Types\Common\Graphics\Color;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Graphics\ColorType
 */
final class ColorTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private ColorType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(ColorType::NAME)) {
            Type::addType(ColorType::NAME, ColorType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(ColorType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(ColorType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSql94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('CHAR(7)', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('CHAR(7)', $this->type->getSQLDeclaration(['length' => '70'], new MySqlPlatform()));
        self::assertSame('CHAR(7)', $this->type->getSQLDeclaration(['length' => '200'], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $color = Color::fromString('#112233');
        $value = $this->type->convertToDatabaseValue($color, new MySqlPlatform());
        
        self::assertSame('#112233', $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = '#112233';
        $color = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(Color::class, $color);
        self::assertSame($value, (string)$color);
    }
    
    
    
}
