<?php

namespace WechatMiniProgramOrderBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Entity\ShippingItemList;
use WechatMiniProgramOrderBundle\Entity\ShippingList;

class ShippingItemListTest extends TestCase
{
    private ShippingItemList $shippingItemList;

    protected function setUp(): void
    {
        $this->shippingItemList = new ShippingItemList();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->shippingItemList->getId());
    }

    public function testGetterAndSetterForShippingList(): void
    {
        $this->assertNull($this->shippingItemList->getShippingList());
        
        $shippingList = $this->createMock(ShippingList::class);
        $this->shippingItemList->setShippingList($shippingList);
        $this->assertSame($shippingList, $this->shippingItemList->getShippingList());
    }

    public function testGetterAndSetterForMerchantItemId(): void
    {
        $this->assertNull($this->shippingItemList->getMerchantItemId());
        
        $merchantItemId = 'MERCHANT_ITEM_123';
        $this->shippingItemList->setMerchantItemId($merchantItemId);
        $this->assertSame($merchantItemId, $this->shippingItemList->getMerchantItemId());
    }

    public function testFluentInterfaces(): void
    {
        $shippingList = $this->createMock(ShippingList::class);
        
        $result = $this->shippingItemList->setShippingList($shippingList);
        $this->assertSame($this->shippingItemList, $result);
        
        $result = $this->shippingItemList->setMerchantItemId('MERCHANT_ITEM_123');
        $this->assertSame($this->shippingItemList, $result);
    }

    public function testToString(): void
    {
        $result = (string) $this->shippingItemList;
        $this->assertSame('', $result);
    }
}