<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Web;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSql94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Web\UrlPath;
use Mediagone\Doctrine\Types\Common\Web\UrlPathType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Web\UrlPathType
 */
final class UrlPathTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private UrlPathType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(UrlPathType::NAME)) {
            Type::addType(UrlPathType::NAME, UrlPathType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(UrlPathType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(UrlPathType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSql94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR('.UrlPath::MAX_LENGTH.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('VARCHAR('.UrlPath::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => '200'], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $originalValue = '/path/to/page.html';
        $url = UrlPath::fromString($originalValue);
        $value = $this->type->convertToDatabaseValue($url, new MySqlPlatform());
        
        self::assertSame($originalValue, $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = '/path/to/page.html';
        $url = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(UrlPath::class, $url);
        self::assertSame($value, (string)$url);
    }
    
    
    
}
