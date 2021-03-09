<?php declare(strict_types=1);

namespace Tests\Mediagone\Doctrine\Types\Common\Web;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSql94Platform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Common\Types\Web\EmailAddress;
use Mediagone\Doctrine\Types\Common\Web\EmailAddressType;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Doctrine\Types\Common\Web\EmailAddressType
 */
final class EmailAddressTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private EmailAddressType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(EmailAddressType::NAME)) {
            Type::addType(EmailAddressType::NAME, EmailAddressType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(EmailAddressType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(EmailAddressType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSql94Platform()));
    }
    
    
    public function test_declare_sql() : void
    {
        self::assertSame('VARCHAR(70)', $this->type->getSQLDeclaration([], new MySqlPlatform()));
        self::assertSame('VARCHAR(70)', $this->type->getSQLDeclaration(['length' => '200'], new MySqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $originalValue = 'local@domain.com';
        $email = EmailAddress::fromString('local@domain.com');
        $value = $this->type->convertToDatabaseValue($email, new MySqlPlatform());
        
        self::assertSame($originalValue, $value);
    }
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = 'local@domain.com';
        $email = $this->type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(EmailAddress::class, $email);
        self::assertSame($value, (string)$email);
    }
    
    
    
}
