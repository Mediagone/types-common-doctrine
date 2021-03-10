<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Text;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSql94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Text\Slug;
use Mediagone\Doctrine\Types\Common\Text\SlugType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Text\SlugType
 */
final class SlugTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private SlugType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(SlugType::NAME)) {
            Type::addType(SlugType::NAME, SlugType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(SlugType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(SlugType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSql94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR(255)', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('VARCHAR(70)', $this->type->getSQLDeclaration(['length' => '70'], new MySqlPlatform()));
        self::assertSame('VARCHAR(200)', $this->type->getSQLDeclaration(['length' => '200'], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $slug = Slug::fromString('demo-slug');
        $value = $this->type->convertToDatabaseValue($slug, new MySqlPlatform());
        
        self::assertSame((string)$slug, $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'demo-slug';
        $slug = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(Slug::class, $slug);
        self::assertSame($value, (string)$slug);
    }
    
    
    
}
