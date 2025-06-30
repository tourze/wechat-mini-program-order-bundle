<?php

namespace WechatMiniProgramOrderBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShippingList;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;
use WechatMiniProgramOrderBundle\Enum\DeliveryMode;

class SubOrderListTest extends TestCase
{
    private SubOrderList $subOrderList;

    protected function setUp(): void
    {
        $this->subOrderList = new SubOrderList();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->subOrderList->getId());
    }

    public function testGetterAndSetterForCombinedShippingInfo(): void
    {
        $this->assertNull($this->subOrderList->getCombinedShippingInfo());
        
        $combinedShippingInfo = $this->createMock(CombinedShippingInfo::class);
        $this->subOrderList->setCombinedShippingInfo($combinedShippingInfo);
        $this->assertSame($combinedShippingInfo, $this->subOrderList->getCombinedShippingInfo());
        
        $this->subOrderList->setCombinedShippingInfo(null);
        $this->assertNull($this->subOrderList->getCombinedShippingInfo());
    }

    public function testGetterAndSetterForOrderKey(): void
    {
        $this->assertNull($this->subOrderList->getOrderKey());
        
        $orderKey = $this->createMock(OrderKey::class);
        $this->subOrderList->setOrderKey($orderKey);
        $this->assertSame($orderKey, $this->subOrderList->getOrderKey());
        
        $this->subOrderList->setOrderKey(null);
        $this->assertNull($this->subOrderList->getOrderKey());
    }

    public function testGetterAndSetterForDeliveryMode(): void
    {
        $this->assertSame(DeliveryMode::UNIFIED_DELIVERY, $this->subOrderList->getDeliveryMode());
        
        $this->subOrderList->setDeliveryMode(DeliveryMode::SPLIT_DELIVERY);
        $this->assertSame(DeliveryMode::SPLIT_DELIVERY, $this->subOrderList->getDeliveryMode());
    }

    public function testGetShippingList(): void
    {
        $shippingList = $this->subOrderList->getShippingList();
        $this->assertInstanceOf(ArrayCollection::class, $shippingList);
        $this->assertTrue($shippingList->isEmpty());
    }

    public function testAddShippingList(): void
    {
        $shipping = $this->createMock(ShippingList::class);
        $shipping->expects($this->once())
            ->method('setSubOrder')
            ->with($this->subOrderList);

        $result = $this->subOrderList->addShippingList($shipping);
        
        $this->assertSame($this->subOrderList, $result);
        $this->assertTrue($this->subOrderList->getShippingList()->contains($shipping));
    }

    public function testAddShippingListAlreadyExists(): void
    {
        $shipping = $this->createMock(ShippingList::class);
        $shipping->expects($this->once())
            ->method('setSubOrder')
            ->with($this->subOrderList);

        $this->subOrderList->addShippingList($shipping);
        $this->subOrderList->addShippingList($shipping);
        
        $this->assertCount(1, $this->subOrderList->getShippingList());
    }

    public function testRemoveShippingList(): void
    {
        $shipping = $this->createMock(ShippingList::class);
        $shipping->expects($this->exactly(2))
            ->method('setSubOrder')
            ->willReturnCallback(function ($arg) use ($shipping) {
                static $count = 0;
                $count++;
                if ($count === 1) {
                    $this->assertSame($this->subOrderList, $arg);
                } else {
                    $this->assertNull($arg);
                }
                return $shipping;
            });
        $shipping->expects($this->once())
            ->method('getSubOrder')
            ->willReturn($this->subOrderList);

        $this->subOrderList->addShippingList($shipping);
        $result = $this->subOrderList->removeShippingList($shipping);
        
        $this->assertSame($this->subOrderList, $result);
        $this->assertFalse($this->subOrderList->getShippingList()->contains($shipping));
    }

    public function testFluentInterfaces(): void
    {
        $combinedShippingInfo = $this->createMock(CombinedShippingInfo::class);
        $orderKey = $this->createMock(OrderKey::class);
        
        $result = $this->subOrderList->setCombinedShippingInfo($combinedShippingInfo);
        $this->assertSame($this->subOrderList, $result);
        
        $result = $this->subOrderList->setOrderKey($orderKey);
        $this->assertSame($this->subOrderList, $result);
        
        $result = $this->subOrderList->setDeliveryMode(DeliveryMode::SPLIT_DELIVERY);
        $this->assertSame($this->subOrderList, $result);
    }

    public function testToString(): void
    {
        $result = (string) $this->subOrderList;
        $this->assertSame('', $result);
    }
}