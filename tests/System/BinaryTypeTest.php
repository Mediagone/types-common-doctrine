<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\System;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\System\Binary;
use Mediagone\Doctrine\Types\Common\System\BinaryType;
use PHPUnit\Framework\TestCase;
use function random_bytes;


/**
 * @covers \Mediagone\Doctrine\Types\Common\System\BinaryType
 */
final class BinaryTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private BinaryType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(BinaryType::NAME)) {
            Type::addType(BinaryType::NAME, BinaryType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(BinaryType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(BinaryType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQLPlatform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('BLOB', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('BLOB', $this->type->getSQLDeclaration(['length' => 100], new MySqlPlatform()));
        self::assertSame('BLOB', $this->type->getSQLDeclaration(['length' => 1000000], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        /** @var string $value */
        $value = $this->type->convertToDatabaseValue(Binary::fromString('bu��ZP2'), new MySqlPlatform());
        
        self::assertIsString($value);
        self::assertSame('bu��ZP2', $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = $this->type->convertToPHPValue('bu��ZP2', new MySqlPlatform());
        
        self::assertInstanceOf(Binary::class, $value);
        self::assertSame('bu��ZP2', (string)$value);
    }
    
    
    
}
