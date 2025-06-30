<?php

namespace WechatMiniProgramOrderBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;

class ShoppingItemListTest extends TestCase
{
    private ShoppingItemList $shoppingItemList;

    protected function setUp(): void
    {
        $this->shoppingItemList = new ShoppingItemList();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->shoppingItemList->getId());
    }

    public function testGetterAndSetterForMerchantItemId(): void
    {
        $this->assertNull($this->shoppingItemList->getMerchantItemId());
        
        $merchantItemId = 'ITEM-001';
        $this->shoppingItemList->setMerchantItemId($merchantItemId);
        $this->assertSame($merchantItemId, $this->shoppingItemList->getMerchantItemId());
    }

    public function testGetterAndSetterForItemName(): void
    {
        $this->assertNull($this->shoppingItemList->getItemName());
        
        $itemName = 'Test Product';
        $this->shoppingItemList->setItemName($itemName);
        $this->assertSame($itemName, $this->shoppingItemList->getItemName());
    }

    public function testGetterAndSetterForItemCount(): void
    {
        $this->assertNull($this->shoppingItemList->getItemCount());
        
        $itemCount = 5;
        $this->shoppingItemList->setItemCount($itemCount);
        $this->assertSame($itemCount, $this->shoppingItemList->getItemCount());
    }

    public function testGetterAndSetterForItemPrice(): void
    {
        $this->assertNull($this->shoppingItemList->getItemPrice());
        
        $itemPrice = '99.99';
        $this->shoppingItemList->setItemPrice($itemPrice);
        $this->assertSame($itemPrice, $this->shoppingItemList->getItemPrice());
    }

    public function testGetterAndSetterForItemAmount(): void
    {
        $this->assertNull($this->shoppingItemList->getItemAmount());
        
        $itemAmount = '499.95';
        $this->shoppingItemList->setItemAmount($itemAmount);
        $this->assertSame($itemAmount, $this->shoppingItemList->getItemAmount());
    }

    public function testGetterAndSetterForShoppingInfo(): void
    {
        $this->assertNull($this->shoppingItemList->getShoppingInfo());
        
        $shoppingInfo = $this->createMock(ShoppingInfo::class);
        $this->shoppingItemList->setShoppingInfo($shoppingInfo);
        $this->assertSame($shoppingInfo, $this->shoppingItemList->getShoppingInfo());
        
        $this->shoppingItemList->setShoppingInfo(null);
        $this->assertNull($this->shoppingItemList->getShoppingInfo());
    }

    public function testFluentInterfaces(): void
    {
        $shoppingInfo = $this->createMock(ShoppingInfo::class);
        
        $result = $this->shoppingItemList->setMerchantItemId('ITEM-001');
        $this->assertSame($this->shoppingItemList, $result);
        
        $result = $this->shoppingItemList->setItemName('Test Product');
        $this->assertSame($this->shoppingItemList, $result);
        
        $result = $this->shoppingItemList->setItemCount(5);
        $this->assertSame($this->shoppingItemList, $result);
        
        $result = $this->shoppingItemList->setItemPrice('99.99');
        $this->assertSame($this->shoppingItemList, $result);
        
        $result = $this->shoppingItemList->setItemAmount('499.95');
        $this->assertSame($this->shoppingItemList, $result);
        
        $result = $this->shoppingItemList->setShoppingInfo($shoppingInfo);
        $this->assertSame($this->shoppingItemList, $result);
    }

    public function testToString(): void
    {
        $result = (string) $this->shoppingItemList;
        $this->assertSame('', $result);
    }
}