<?php

namespace WechatMiniProgramOrderBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\CombinedShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\Contact;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;

class CombinedShoppingInfoTest extends TestCase
{
    private CombinedShoppingInfo $combinedShoppingInfo;

    protected function setUp(): void
    {
        $this->combinedShoppingInfo = new CombinedShoppingInfo();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getId());
    }

    public function testGetterAndSetterForAccount(): void
    {
        $account = $this->createMock(Account::class);
        
        $this->combinedShoppingInfo->setAccount($account);
        $this->assertSame($account, $this->combinedShoppingInfo->getAccount());
    }

    public function testGetterAndSetterForOrderId(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getOrderId());
        
        $orderId = 'order-123';
        $this->combinedShoppingInfo->setOrderId($orderId);
        $this->assertSame($orderId, $this->combinedShoppingInfo->getOrderId());
    }

    public function testGetterAndSetterForOutOrderId(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getOutOrderId());
        
        $outOrderId = 'out-order-456';
        $this->combinedShoppingInfo->setOutOrderId($outOrderId);
        $this->assertSame($outOrderId, $this->combinedShoppingInfo->getOutOrderId());
    }

    public function testGetterAndSetterForPathId(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getPathId());
        
        $pathId = 'path-789';
        $this->combinedShoppingInfo->setPathId($pathId);
        $this->assertSame($pathId, $this->combinedShoppingInfo->getPathId());
    }

    public function testGetterAndSetterForStatus(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getStatus());
        
        $status = 'completed';
        $this->combinedShoppingInfo->setStatus($status);
        $this->assertSame($status, $this->combinedShoppingInfo->getStatus());
    }

    public function testGetterAndSetterForTotalAmount(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getTotalAmount());
        
        $totalAmount = 10000;
        $this->combinedShoppingInfo->setTotalAmount($totalAmount);
        $this->assertSame($totalAmount, $this->combinedShoppingInfo->getTotalAmount());
    }

    public function testGetterAndSetterForPayAmount(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getPayAmount());
        
        $payAmount = 9500;
        $this->combinedShoppingInfo->setPayAmount($payAmount);
        $this->assertSame($payAmount, $this->combinedShoppingInfo->getPayAmount());
    }

    public function testGetterAndSetterForDiscountAmount(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getDiscountAmount());
        
        $discountAmount = 500;
        $this->combinedShoppingInfo->setDiscountAmount($discountAmount);
        $this->assertSame($discountAmount, $this->combinedShoppingInfo->getDiscountAmount());
    }

    public function testGetterAndSetterForFreightAmount(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getFreightAmount());
        
        $freightAmount = 1000;
        $this->combinedShoppingInfo->setFreightAmount($freightAmount);
        $this->assertSame($freightAmount, $this->combinedShoppingInfo->getFreightAmount());
    }

    public function testGetterAndSetterForPayer(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getPayer());
        
        $payer = $this->createMock(UserInterface::class);
        $this->combinedShoppingInfo->setPayer($payer);
        $this->assertSame($payer, $this->combinedShoppingInfo->getPayer());
    }

    public function testGetterAndSetterForContact(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getContact());
        
        $contact = $this->createMock(Contact::class);
        $this->combinedShoppingInfo->setContact($contact);
        $this->assertSame($contact, $this->combinedShoppingInfo->getContact());
    }

    public function testGetterAndSetterForShippingInfo(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getShippingInfo());
        
        $shippingInfo = $this->createMock(ShippingInfo::class);
        $this->combinedShoppingInfo->setShippingInfo($shippingInfo);
        $this->assertSame($shippingInfo, $this->combinedShoppingInfo->getShippingInfo());
    }

    public function testFluentInterfaces(): void
    {
        $account = $this->createMock(Account::class);
        $payer = $this->createMock(UserInterface::class);
        $contact = $this->createMock(Contact::class);
        $shippingInfo = $this->createMock(ShippingInfo::class);
        
        $result = $this->combinedShoppingInfo->setAccount($account);
        $this->assertSame($this->combinedShoppingInfo, $result);
        
        $result = $this->combinedShoppingInfo->setOrderId('order-123');
        $this->assertSame($this->combinedShoppingInfo, $result);
        
        $result = $this->combinedShoppingInfo->setOutOrderId('out-order-456');
        $this->assertSame($this->combinedShoppingInfo, $result);
        
        $result = $this->combinedShoppingInfo->setPathId('path-789');
        $this->assertSame($this->combinedShoppingInfo, $result);
        
        $result = $this->combinedShoppingInfo->setStatus('completed');
        $this->assertSame($this->combinedShoppingInfo, $result);
        
        $result = $this->combinedShoppingInfo->setTotalAmount(10000);
        $this->assertSame($this->combinedShoppingInfo, $result);
        
        $result = $this->combinedShoppingInfo->setPayAmount(9500);
        $this->assertSame($this->combinedShoppingInfo, $result);
        
        $result = $this->combinedShoppingInfo->setDiscountAmount(500);
        $this->assertSame($this->combinedShoppingInfo, $result);
        
        $result = $this->combinedShoppingInfo->setFreightAmount(1000);
        $this->assertSame($this->combinedShoppingInfo, $result);
        
        $result = $this->combinedShoppingInfo->setPayer($payer);
        $this->assertSame($this->combinedShoppingInfo, $result);
        
        $result = $this->combinedShoppingInfo->setContact($contact);
        $this->assertSame($this->combinedShoppingInfo, $result);
        
        $result = $this->combinedShoppingInfo->setShippingInfo($shippingInfo);
        $this->assertSame($this->combinedShoppingInfo, $result);
    }

    public function testToString(): void
    {
        $result = (string) $this->combinedShoppingInfo;
        $this->assertSame('', $result);
    }
}