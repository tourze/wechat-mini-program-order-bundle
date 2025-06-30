<?php

namespace WechatMiniProgramOrderBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;

class CombinedShippingInfoTest extends TestCase
{
    private CombinedShippingInfo $combinedShippingInfo;

    protected function setUp(): void
    {
        $this->combinedShippingInfo = new CombinedShippingInfo();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->combinedShippingInfo->getId());
    }

    public function testGetterAndSetterForAccount(): void
    {
        $account = $this->createMock(Account::class);
        
        $this->combinedShippingInfo->setAccount($account);
        $this->assertSame($account, $this->combinedShippingInfo->getAccount());
    }

    public function testGetterAndSetterForOrderKey(): void
    {
        $this->assertNull($this->combinedShippingInfo->getOrderKey());
        
        $orderKey = $this->createMock(OrderKey::class);
        $this->combinedShippingInfo->setOrderKey($orderKey);
        $this->assertSame($orderKey, $this->combinedShippingInfo->getOrderKey());
    }

    public function testGetSubOrders(): void
    {
        $subOrders = $this->combinedShippingInfo->getSubOrders();
        $this->assertInstanceOf(ArrayCollection::class, $subOrders);
        $this->assertTrue($subOrders->isEmpty());
    }

    public function testAddSubOrder(): void
    {
        $subOrder = $this->createMock(SubOrderList::class);
        $subOrder->expects($this->once())
            ->method('setCombinedShippingInfo')
            ->with($this->combinedShippingInfo);

        $result = $this->combinedShippingInfo->addSubOrder($subOrder);
        
        $this->assertSame($this->combinedShippingInfo, $result);
        $this->assertTrue($this->combinedShippingInfo->getSubOrders()->contains($subOrder));
    }

    public function testAddSubOrderAlreadyExists(): void
    {
        $subOrder = $this->createMock(SubOrderList::class);
        $subOrder->expects($this->once())
            ->method('setCombinedShippingInfo')
            ->with($this->combinedShippingInfo);

        $this->combinedShippingInfo->addSubOrder($subOrder);
        $this->combinedShippingInfo->addSubOrder($subOrder);
        
        $this->assertCount(1, $this->combinedShippingInfo->getSubOrders());
    }

    public function testRemoveSubOrder(): void
    {
        $subOrder = $this->createMock(SubOrderList::class);
        $subOrder->expects($this->exactly(2))
            ->method('setCombinedShippingInfo')
            ->willReturnCallback(function ($arg) use ($subOrder) {
                return $subOrder;
            });
        $subOrder->expects($this->once())
            ->method('getCombinedShippingInfo')
            ->willReturn($this->combinedShippingInfo);

        $this->combinedShippingInfo->addSubOrder($subOrder);
        $result = $this->combinedShippingInfo->removeSubOrder($subOrder);
        
        $this->assertSame($this->combinedShippingInfo, $result);
        $this->assertFalse($this->combinedShippingInfo->getSubOrders()->contains($subOrder));
    }

    public function testGetterAndSetterForPayer(): void
    {
        $this->assertNull($this->combinedShippingInfo->getPayer());
        
        $payer = $this->createMock(UserInterface::class);
        $this->combinedShippingInfo->setPayer($payer);
        $this->assertSame($payer, $this->combinedShippingInfo->getPayer());
    }

    public function testGetterAndSetterForUploadTime(): void
    {
        $uploadTime = $this->combinedShippingInfo->getUploadTime();
        $this->assertInstanceOf(\DateTimeImmutable::class, $uploadTime);
        
        $newUploadTime = new \DateTimeImmutable('2023-01-01 12:00:00');
        $this->combinedShippingInfo->setUploadTime($newUploadTime);
        $this->assertSame($newUploadTime, $this->combinedShippingInfo->getUploadTime());
    }

    public function testGetterAndSetterForValid(): void
    {
        $this->assertFalse($this->combinedShippingInfo->isValid());
        
        $this->combinedShippingInfo->setValid(true);
        $this->assertTrue($this->combinedShippingInfo->isValid());
        
        $this->combinedShippingInfo->setValid(null);
        $this->assertNull($this->combinedShippingInfo->isValid());
    }

    public function testFluentInterfaces(): void
    {
        $account = $this->createMock(Account::class);
        $orderKey = $this->createMock(OrderKey::class);
        $payer = $this->createMock(UserInterface::class);
        $uploadTime = new \DateTimeImmutable();
        
        $result = $this->combinedShippingInfo->setAccount($account);
        $this->assertSame($this->combinedShippingInfo, $result);
        
        $result = $this->combinedShippingInfo->setOrderKey($orderKey);
        $this->assertSame($this->combinedShippingInfo, $result);
        
        $result = $this->combinedShippingInfo->setPayer($payer);
        $this->assertSame($this->combinedShippingInfo, $result);
        
        $result = $this->combinedShippingInfo->setUploadTime($uploadTime);
        $this->assertSame($this->combinedShippingInfo, $result);
        
        $result = $this->combinedShippingInfo->setValid(true);
        $this->assertSame($this->combinedShippingInfo, $result);
    }

    public function testToString(): void
    {
        $result = (string) $this->combinedShippingInfo;
        $this->assertSame('', $result);
    }
}