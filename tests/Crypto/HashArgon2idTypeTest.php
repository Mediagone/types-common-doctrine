<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Crypto;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQL94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Crypto\HashArgon2id;
use Mediagone\Doctrine\Types\Common\Crypto\HashArgon2idType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Crypto\HashArgon2idType
 */
final class HashArgon2idTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private HashArgon2idType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(HashArgon2idType::NAME)) {
            Type::addType(HashArgon2idType::NAME, HashArgon2idType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(HashArgon2idType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(HashArgon2idType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQL94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR('.HashArgon2idType::SIZE.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('VARCHAR('.HashArgon2idType::SIZE.')', $this->type->getSQLDeclaration(['length' => HashArgon2idType::SIZE + 1], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $hash = '$argon2id$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI';
        $bcrypt = HashArgon2id::fromHash($hash);
        $value = $this->type->convertToDatabaseValue($bcrypt, new MySqlPlatform());
        
        self::assertSame($hash, $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = '$argon2id$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI';
        $bcrypt = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(HashArgon2id::class, $bcrypt);
        self::assertSame($value, (string)$bcrypt);
    }
    
    
    
}
