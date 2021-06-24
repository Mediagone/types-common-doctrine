<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Crypto;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQL94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Doctrine\Types\Common\Crypto\RandomTokenType;
use Mediagone\Types\Common\Crypto\RandomToken;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Crypto\RandomTokenType
 */
final class RandomTokenTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private RandomTokenType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(RandomTokenType::NAME)) {
            Type::addType(RandomTokenType::NAME, RandomTokenType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(RandomTokenType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(RandomTokenType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQL94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('CHAR('.RandomToken::LENGTH.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('CHAR('.RandomToken::LENGTH.')', $this->type->getSQLDeclaration(['length' => RandomToken::LENGTH + 1], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $hex = 'ee201de6d1692527230eee201de6d1692527230e';
        $token = RandomToken::fromHexString($hex);
        $value = $this->type->convertToDatabaseValue($token, new MySqlPlatform());
        
        self::assertSame($hex, $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'ee201de6d1692527230eee201de6d1692527230e';
        $token = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(RandomToken::class, $token);
        self::assertSame($value, (string)$token);
    }
    
    
    
}
