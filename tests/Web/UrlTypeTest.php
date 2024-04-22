<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Web;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Web\Url;
use Mediagone\Doctrine\Types\Common\Web\UrlType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Web\UrlType
 */
final class UrlTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private UrlType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(UrlType::NAME)) {
            Type::addType(UrlType::NAME, UrlType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(UrlType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(UrlType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQLPlatform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR('.Url::MAX_LENGTH.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('VARCHAR('.Url::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => 200], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $originalValue = 'https://domain.com/about';
        $url = Url::fromString($originalValue);
        $value = $this->type->convertToDatabaseValue($url, new MySqlPlatform());
        
        self::assertSame($originalValue, $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'https://domain.com/about';
        $url = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(Url::class, $url);
        self::assertSame($value, (string)$url);
    }
    
    
    
}
