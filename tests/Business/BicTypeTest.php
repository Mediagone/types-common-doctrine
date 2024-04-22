<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Business;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\Business\Bic;
use Mediagone\Doctrine\Types\Common\Business\BicType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Business\BicType
 */
final class BicTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private BicType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(BicType::NAME)) {
            Type::addType(BicType::NAME, BicType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(BicType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(BicType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSQLPlatform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR('.Bic::MAX_LENGTH.')', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('VARCHAR('.Bic::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => 70], new MySqlPlatform()));
        self::assertSame('VARCHAR('.Bic::MAX_LENGTH.')', $this->type->getSQLDeclaration(['length' => 200], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $name = Bic::fromString('SOGEFRPP');
        $value = $this->type->convertToDatabaseValue($name, new MySqlPlatform());
        
        self::assertSame($value, (string)$name);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'SOGEFRPP';
        $name = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(Bic::class, $name);
        self::assertSame($value, (string)$name);
    }
    
    
    
}
