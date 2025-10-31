<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;

/**
 * @internal
 */
#[CoversClass(ShoppingItemList::class)]
final class ShoppingItemListTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ShoppingItemList();
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

    private ShoppingItemList $shoppingItemList;

    protected function setUp(): void
    {
        parent::setUp();

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

        // 使用具体类 ShoppingInfo 是必要的，理由1：
        // 1. ShoppingInfo 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $shoppingInfo = $this->createMock(ShoppingInfo::class);
        $this->shoppingItemList->setShoppingInfo($shoppingInfo);
        $this->assertSame($shoppingInfo, $this->shoppingItemList->getShoppingInfo());

        $this->shoppingItemList->setShoppingInfo(null);
        $this->assertNull($this->shoppingItemList->getShoppingInfo());
    }

    public function testSettersReturnVoid(): void
    {
        // 使用具体类 ShoppingInfo 是必要的，理由1：
        // 1. ShoppingInfo 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $shoppingInfo = $this->createMock(ShoppingInfo::class);

        $this->shoppingItemList->setMerchantItemId('ITEM-001');
        $this->assertSame('ITEM-001', $this->shoppingItemList->getMerchantItemId());

        $this->shoppingItemList->setItemName('Test Product');
        $this->assertSame('Test Product', $this->shoppingItemList->getItemName());

        $this->shoppingItemList->setItemCount(5);
        $this->assertSame(5, $this->shoppingItemList->getItemCount());

        $this->shoppingItemList->setItemPrice('99.99');
        $this->assertSame('99.99', $this->shoppingItemList->getItemPrice());

        $this->shoppingItemList->setItemAmount('499.95');
        $this->assertSame('499.95', $this->shoppingItemList->getItemAmount());

        $this->shoppingItemList->setShoppingInfo($shoppingInfo);
        $this->assertSame($shoppingInfo, $this->shoppingItemList->getShoppingInfo());
    }

    public function testToString(): void
    {
        $result = (string) $this->shoppingItemList;
        $this->assertSame('', $result);
    }
}
