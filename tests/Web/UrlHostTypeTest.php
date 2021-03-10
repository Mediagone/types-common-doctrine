<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Web;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSql94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Web\UrlHost;
use Mediagone\Doctrine\Types\Common\Web\UrlHostType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Web\UrlHostType
 */
final class UrlHostTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private UrlHostType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(UrlHostType::NAME)) {
            Type::addType(UrlHostType::NAME, UrlHostType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(UrlHostType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(UrlHostType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSql94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR('.UrlHost::MAX_LENGTH.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('VARCHAR('.UrlHost::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => '200'], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $originalValue = 'https://domain.com';
        $url = UrlHost::fromString($originalValue);
        $value = $this->type->convertToDatabaseValue($url, new MySqlPlatform());
        
        self::assertSame($originalValue, $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'https://domain.com';
        $url = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(UrlHost::class, $url);
        self::assertSame($value, (string)$url);
    }
    
    
    
}
