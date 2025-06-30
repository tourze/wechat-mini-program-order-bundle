<?php

namespace WechatMiniProgramOrderBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Entity\Contact;
use WechatMiniProgramOrderBundle\Entity\ShippingItemList;
use WechatMiniProgramOrderBundle\Entity\ShippingList;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;

class ShippingListTest extends TestCase
{
    private ShippingList $shippingList;

    protected function setUp(): void
    {
        $this->shippingList = new ShippingList();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->shippingList->getId());
    }

    public function testGetterAndSetterForSubOrder(): void
    {
        $this->assertNull($this->shippingList->getSubOrder());
        
        $subOrder = $this->createMock(SubOrderList::class);
        $this->shippingList->setSubOrder($subOrder);
        $this->assertSame($subOrder, $this->shippingList->getSubOrder());
    }

    public function testGetterAndSetterForTrackingNo(): void
    {
        $this->assertNull($this->shippingList->getTrackingNo());
        
        $trackingNo = '1234567890';
        $this->shippingList->setTrackingNo($trackingNo);
        $this->assertSame($trackingNo, $this->shippingList->getTrackingNo());
    }

    public function testGetterAndSetterForExpressCompany(): void
    {
        $this->assertNull($this->shippingList->getExpressCompany());
        
        $expressCompany = 'SF';
        $this->shippingList->setExpressCompany($expressCompany);
        $this->assertSame($expressCompany, $this->shippingList->getExpressCompany());
    }

    public function testGetItemList(): void
    {
        $itemList = $this->shippingList->getItemList();
        $this->assertInstanceOf(ArrayCollection::class, $itemList);
        $this->assertTrue($itemList->isEmpty());
    }

    public function testAddItemList(): void
    {
        $itemList = $this->createMock(ShippingItemList::class);
        $itemList->expects($this->once())
            ->method('setShippingList')
            ->with($this->shippingList);

        $result = $this->shippingList->addItemList($itemList);
        
        $this->assertSame($this->shippingList, $result);
        $this->assertTrue($this->shippingList->getItemList()->contains($itemList));
    }

    public function testRemoveItemList(): void
    {
        $itemList = $this->createMock(ShippingItemList::class);
        $itemList->expects($this->exactly(2))
            ->method('setShippingList')
            ->willReturnCallback(function ($arg) use ($itemList) {
                return $itemList;
            });
        $itemList->expects($this->once())
            ->method('getShippingList')
            ->willReturn($this->shippingList);

        $this->shippingList->addItemList($itemList);
        $result = $this->shippingList->removeItemList($itemList);
        
        $this->assertSame($this->shippingList, $result);
        $this->assertFalse($this->shippingList->getItemList()->contains($itemList));
    }

    public function testGetterAndSetterForContact(): void
    {
        $this->assertNull($this->shippingList->getContact());
        
        $contact = $this->createMock(Contact::class);
        $this->shippingList->setContact($contact);
        $this->assertSame($contact, $this->shippingList->getContact());
    }

    public function testGetterAndSetterForTrackingInfo(): void
    {
        $this->assertNull($this->shippingList->getTrackingInfo());
        
        $trackingInfo = ['status' => 'shipped', 'location' => 'Shanghai'];
        $this->shippingList->setTrackingInfo($trackingInfo);
        $this->assertSame($trackingInfo, $this->shippingList->getTrackingInfo());
    }

    public function testGetterAndSetterForLastTrackingTime(): void
    {
        $this->assertNull($this->shippingList->getLastTrackingTime());
        
        $lastTrackingTime = new \DateTimeImmutable('2023-01-01 12:00:00');
        $this->shippingList->setLastTrackingTime($lastTrackingTime);
        $this->assertSame($lastTrackingTime, $this->shippingList->getLastTrackingTime());
    }

    public function testToString(): void
    {
        $result = (string) $this->shippingList;
        $this->assertSame('', $result);
    }
}