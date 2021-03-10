<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Crypto;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQL94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Crypto\Hash;
use Mediagone\Types\Common\Crypto\HashArgon2id;
use Mediagone\Types\Common\Crypto\HashBcrypt;
use Mediagone\Doctrine\Types\Common\Crypto\HashArgon2idType;
use Mediagone\Doctrine\Types\Common\Crypto\HashBcryptType;
use Mediagone\Doctrine\Types\Common\Crypto\HashType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Crypto\HashType
 */
final class HashTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private HashType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(HashType::NAME)) {
            Type::addType(HashType::NAME, HashType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(HashType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(HashType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQL94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        $maxLength = max(HashBcryptType::SIZE, HashArgon2idType::SIZE);
        self::assertSame("VARCHAR($maxLength)", $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame("VARCHAR($maxLength)", $this->type->getSQLDeclaration(['length' => $maxLength + 1], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_bcrypt_to_database_value() : void
    {
        $hash = '$2y$12$00000000000000000000000000000000000000000000000000000';
        $bcrypt = Hash::fromHash($hash);
        $value = $this->type->convertToDatabaseValue($bcrypt, new MySqlPlatform());
        
        self::assertSame($hash, $value);
    }
    
    public function test_can_convert_value_from_database_to_bcrypt() : void
    {
        $value = '$2y$12$00000000000000000000000000000000000000000000000000000';
        $bcrypt = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(HashBcrypt::class, $bcrypt);
        self::assertSame($value, (string)$bcrypt);
    }
    
    
    public function test_can_convert_argon2id_to_database_value() : void
    {
        $hash = '$argon2id$v=19$m=16384,t=1,p=1$0000000000000000000000$0000000000000000000000000000000000000000000';
        $argon = Hash::fromHash($hash);
        $value = $this->type->convertToDatabaseValue($argon, new MySqlPlatform());
        
        self::assertSame($hash, $value);
    }
    
    public function test_can_convert_value_from_database_to_argon2id() : void
    {
        $value = '$argon2id$v=19$m=16384,t=1,p=1$0000000000000000000000$0000000000000000000000000000000000000000000';
        $argon = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(HashArgon2id::class, $argon);
        self::assertSame($value, (string)$argon);
    }
    
    
    
}
