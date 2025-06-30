<?php

namespace WechatMiniProgramOrderBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;

class ShoppingInfoTest extends TestCase
{
    private ShoppingInfo $shoppingInfo;

    protected function setUp(): void
    {
        $this->shoppingInfo = new ShoppingInfo();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->shoppingInfo->getId());
    }

    public function testGetterAndSetterForValid(): void
    {
        $this->assertFalse($this->shoppingInfo->isValid());
        
        $this->shoppingInfo->setValid(true);
        $this->assertTrue($this->shoppingInfo->isValid());
        
        $this->shoppingInfo->setValid(null);
        $this->assertNull($this->shoppingInfo->isValid());
    }

    public function testGetterAndSetterForAccount(): void
    {
        $account = $this->createMock(Account::class);
        
        $this->shoppingInfo->setAccount($account);
        $this->assertSame($account, $this->shoppingInfo->getAccount());
    }

    public function testGetterAndSetterForOrderKey(): void
    {
        $orderKey = $this->createMock(OrderKey::class);
        
        $this->shoppingInfo->setOrderKey($orderKey);
        $this->assertSame($orderKey, $this->shoppingInfo->getOrderKey());
    }

    public function testGetterAndSetterForPayer(): void
    {
        $payer = $this->createMock(UserInterface::class);
        
        $this->shoppingInfo->setPayer($payer);
        $this->assertSame($payer, $this->shoppingInfo->getPayer());
    }

    public function testGetterAndSetterForLogisticsType(): void
    {
        $this->assertSame(LogisticsType::PHYSICAL_LOGISTICS, $this->shoppingInfo->getLogisticsType());
        
        $this->shoppingInfo->setLogisticsType(LogisticsType::VIRTUAL_GOODS);
        $this->assertSame(LogisticsType::VIRTUAL_GOODS, $this->shoppingInfo->getLogisticsType());
    }

    public function testGetterAndSetterForOrderDetailType(): void
    {
        $this->assertSame(OrderDetailType::MINI_PROGRAM, $this->shoppingInfo->getOrderDetailType());
        
        $this->shoppingInfo->setOrderDetailType(OrderDetailType::URL);
        $this->assertSame(OrderDetailType::URL, $this->shoppingInfo->getOrderDetailType());
    }

    public function testGetterAndSetterForOrderDetailPath(): void
    {
        $this->assertNull($this->shoppingInfo->getOrderDetailPath());
        
        $path = '/pages/order/detail?id=123';
        $this->shoppingInfo->setOrderDetailPath($path);
        $this->assertSame($path, $this->shoppingInfo->getOrderDetailPath());
    }

    public function testGetItemList(): void
    {
        $itemList = $this->shoppingInfo->getItemList();
        $this->assertInstanceOf(ArrayCollection::class, $itemList);
        $this->assertTrue($itemList->isEmpty());
    }

    public function testAddItemList(): void
    {
        $item = $this->createMock(ShoppingItemList::class);
        $item->expects($this->once())
            ->method('setShoppingInfo')
            ->with($this->shoppingInfo);

        $result = $this->shoppingInfo->addItemList($item);
        
        $this->assertSame($this->shoppingInfo, $result);
        $this->assertTrue($this->shoppingInfo->getItemList()->contains($item));
    }

    public function testAddItemListAlreadyExists(): void
    {
        $item = $this->createMock(ShoppingItemList::class);
        $item->expects($this->once())
            ->method('setShoppingInfo')
            ->with($this->shoppingInfo);

        $this->shoppingInfo->addItemList($item);
        $this->shoppingInfo->addItemList($item);
        
        $this->assertCount(1, $this->shoppingInfo->getItemList());
    }

    public function testRemoveItemList(): void
    {
        $item = $this->createMock(ShoppingItemList::class);
        $item->expects($this->exactly(2))
            ->method('setShoppingInfo')
            ->willReturnCallback(function ($arg) use ($item) {
                static $count = 0;
                $count++;
                if ($count === 1) {
                    $this->assertSame($this->shoppingInfo, $arg);
                } else {
                    $this->assertNull($arg);
                }
                return $item;
            });
        $item->expects($this->once())
            ->method('getShoppingInfo')
            ->willReturn($this->shoppingInfo);

        $this->shoppingInfo->addItemList($item);
        $result = $this->shoppingInfo->removeItemList($item);
        
        $this->assertSame($this->shoppingInfo, $result);
        $this->assertFalse($this->shoppingInfo->getItemList()->contains($item));
    }

    public function testFluentInterfaces(): void
    {
        $account = $this->createMock(Account::class);
        $orderKey = $this->createMock(OrderKey::class);
        $payer = $this->createMock(UserInterface::class);
        
        $result = $this->shoppingInfo->setValid(true);
        $this->assertSame($this->shoppingInfo, $result);
        
        $result = $this->shoppingInfo->setAccount($account);
        $this->assertSame($this->shoppingInfo, $result);
        
        $result = $this->shoppingInfo->setOrderKey($orderKey);
        $this->assertSame($this->shoppingInfo, $result);
        
        $result = $this->shoppingInfo->setPayer($payer);
        $this->assertSame($this->shoppingInfo, $result);
        
        $result = $this->shoppingInfo->setLogisticsType(LogisticsType::VIRTUAL_GOODS);
        $this->assertSame($this->shoppingInfo, $result);
        
        $result = $this->shoppingInfo->setOrderDetailType(OrderDetailType::URL);
        $this->assertSame($this->shoppingInfo, $result);
        
        $result = $this->shoppingInfo->setOrderDetailPath('/path/to/order');
        $this->assertSame($this->shoppingInfo, $result);
    }

    public function testToString(): void
    {
        $result = (string) $this->shoppingInfo;
        $this->assertSame('', $result);
    }
}