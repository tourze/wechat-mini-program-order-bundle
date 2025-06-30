<?php

namespace WechatMiniProgramOrderBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Entity\Contact;

class ContactTest extends TestCase
{
    private Contact $contact;

    protected function setUp(): void
    {
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

    public function testFluentInterfaces(): void
    {
        $result = $this->contact->setConsignorContact('138****1234');
        $this->assertSame($this->contact, $result);
        
        $result = $this->contact->setReceiverContact('139****5678');
        $this->assertSame($this->contact, $result);
    }

    public function testToString(): void
    {
        $result = (string) $this->contact;
        $this->assertSame('', $result);
    }
}