<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramOrderBundle\Entity\Contact;

/**
 * @internal
 */
#[CoversClass(Contact::class)]
final class ContactTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new Contact();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'id' => ['id', 'test_id_123'],
            'createTime' => ['createTime', new \DateTimeImmutable()],
            'updateTime' => ['updateTime', new \DateTimeImmutable()],
        ];
    }

    private Contact $contact;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contact = new Contact();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->contact->getId());
    }

    public function testGetterAndSetterForConsignorContact(): void
    {
        $this->assertNull($this->contact->getConsignorContact());

        $consignorContact = '138****1234';
        $this->contact->setConsignorContact($consignorContact);
        $this->assertSame($consignorContact, $this->contact->getConsignorContact());
    }

    public function testGetterAndSetterForReceiverContact(): void
    {
        $this->assertNull($this->contact->getReceiverContact());

        $receiverContact = '139****5678';
        $this->contact->setReceiverContact($receiverContact);
        $this->assertSame($receiverContact, $this->contact->getReceiverContact());
    }

    public function testSettersReturnVoid(): void
    {
        $this->contact->setConsignorContact('138****1234');
        $this->assertSame('138****1234', $this->contact->getConsignorContact());

        $this->contact->setReceiverContact('139****5678');
        $this->assertSame('139****5678', $this->contact->getReceiverContact());
    }

    public function testToString(): void
    {
        $result = (string) $this->contact;
        $this->assertSame('', $result);
    }
}
