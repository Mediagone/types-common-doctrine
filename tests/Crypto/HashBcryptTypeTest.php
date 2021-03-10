<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Crypto;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQL94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Crypto\HashBcrypt;
use Mediagone\Doctrine\Types\Common\Crypto\HashBcryptType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Crypto\HashBcryptType
 */
final class HashBcryptTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private HashBcryptType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(HashBcryptType::NAME)) {
            Type::addType(HashBcryptType::NAME, HashBcryptType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(HashBcryptType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(HashBcryptType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQL94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('CHAR('.HashBcryptType::SIZE.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('CHAR('.HashBcryptType::SIZE.')', $this->type->getSQLDeclaration(['length' => HashBcryptType::SIZE + 1], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $hash = '$2y$12$00000000000000000000000000000000000000000000000000000';
        $bcrypt = HashBcrypt::fromHash($hash);
        $value = $this->type->convertToDatabaseValue($bcrypt, new MySqlPlatform());
        
        self::assertSame($hash, $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = '$2y$12$00000000000000000000000000000000000000000000000000000';
        $bcrypt = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(HashBcrypt::class, $bcrypt);
        self::assertSame($value, (string)$bcrypt);
    }
    
    
    
}
