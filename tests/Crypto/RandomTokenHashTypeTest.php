<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Crypto;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQL94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Doctrine\Types\Common\Crypto\RandomTokenHashType;
use Mediagone\Types\Common\Crypto\RandomTokenHash;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Crypto\RandomTokenHashType
 */
final class RandomTokenHashTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private RandomTokenHashType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(RandomTokenHashType::NAME)) {
            Type::addType(RandomTokenHashType::NAME, RandomTokenHashType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(RandomTokenHashType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(RandomTokenHashType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQL94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('CHAR('.RandomTokenHash::BINARY_LENGTH.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('CHAR('.RandomTokenHash::BINARY_LENGTH.')', $this->type->getSQLDeclaration(['length' => RandomTokenHash::BINARY_LENGTH + 1], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $hash = 'b299d0ac5eb4189a2d504bb110be4f0743b08fb70b492b2b4eacf40db445a0e3537c0011253f86c9d4b3158849e8fa9246a7745e415ac04fb8f4e3eb0d88c522';
        $tokenHash = RandomTokenHash::fromHash($hash);
        $value = $this->type->convertToDatabaseValue($tokenHash, new MySqlPlatform());
        
        self::assertSame(hex2bin($hash), $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'b299d0ac5eb4189a2d504bb110be4f0743b08fb70b492b2b4eacf40db445a0e3537c0011253f86c9d4b3158849e8fa9246a7745e415ac04fb8f4e3eb0d88c522';
        $tokenHash = $this->type->convertToPHPValue(hex2bin($value), new MySqlPlatform());
        
        self::assertInstanceOf(RandomTokenHash::class, $tokenHash);
        self::assertSame($value, (string)$tokenHash);
    }
    
    
    
}
