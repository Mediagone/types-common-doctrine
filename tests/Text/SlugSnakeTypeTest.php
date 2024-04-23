<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Text;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Text\SlugSnake;
use Mediagone\Doctrine\Types\Common\Text\SlugSnakeType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Text\SlugSnakeType
 */
final class SlugSnakeTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private SlugSnakeType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(SlugSnakeType::NAME)) {
            Type::addType(SlugSnakeType::NAME, SlugSnakeType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(SlugSnakeType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(SlugSnakeType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQLPlatform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR(70)', $this->type->getSQLDeclaration(['length' => 70], new MySqlPlatform()));
        self::assertSame('VARCHAR(200)', $this->type->getSQLDeclaration(['length' => 200], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $slug = SlugSnake::fromString('demo_slug');
        $value = $this->type->convertToDatabaseValue($slug, new MySqlPlatform());
        
        self::assertSame((string)$slug, $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'demo_slug';
        $slug = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(SlugSnake::class, $slug);
        self::assertSame($value, (string)$slug);
    }
    
    
    
}
