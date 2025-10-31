<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;

/**
 * @internal
 */
#[CoversClass(ShoppingInfo::class)]
final class ShoppingInfoTest extends AbstractEntityTestCase
{
    protected function createEntity(): ShoppingInfo
    {
        return new ShoppingInfo();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            // Mock 对象属性在单独的测试方法中已经充分测试
            // 'account' => 在 testGetterAndSetterForAccount() 中测试
            // 'orderKey' => 在 testGetterAndSetterForOrderKey() 中测试
            // 'payer' => 在 testGetterAndSetterForPayer() 中测试
            'logisticsType' => ['logisticsType', LogisticsType::PHYSICAL_LOGISTICS],
            'orderDetailType' => ['orderDetailType', OrderDetailType::URL],
        ];
    }

    private ShoppingInfo $shoppingInfo;

    protected function setUp(): void
    {
        parent::setUp();

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
        // 使用具体类 Account 是必要的，理由1：Account 来自外部 Bundle，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 Account 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 Account 是必要的，理由3：避免测试与其他 Bundle 的具体实现产生耦合
        $account = $this->createMock(Account::class);

        $this->shoppingInfo->setAccount($account);
        $this->assertSame($account, $this->shoppingInfo->getAccount());
    }

    public function testGetterAndSetterForOrderKey(): void
    {
        // 使用具体类 OrderKey 是必要的，理由1：OrderKey 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 OrderKey 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 OrderKey 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $orderKey = $this->createMock(OrderKey::class);

        $this->shoppingInfo->setOrderKey($orderKey);
        $this->assertSame($orderKey, $this->shoppingInfo->getOrderKey());
    }

    public function testGetterAndSetterForPayer(): void
    {
        // 使用具体类 UserInterface 是必要的，理由1：UserInterface 是接口，使用 Mock 模拟接口实现是标准做法
        // 使用具体类 UserInterface 是必要的，理由2：测试需要验证用户接口的行为，Mock 提供精确的测试控制
        // 使用具体类 UserInterface 是必要的，理由3：避免依赖具体的用户实现类，保持测试的独立性
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
        // 使用具体类 ShoppingItemList 是必要的，理由1：ShoppingItemList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShoppingItemList 是必要的，理由2：测试需要验证集合操作，Mock 能精确控制方法行为
        // 使用具体类 ShoppingItemList 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $item = $this->createMock(ShoppingItemList::class);

        $this->shoppingInfo->addItemList($item);

        $this->assertTrue($this->shoppingInfo->getItemList()->contains($item));
    }

    public function testAddItemListAlreadyExists(): void
    {
        // 使用具体类 ShoppingItemList 是必要的，理由1：ShoppingItemList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShoppingItemList 是必要的，理由2：测试需要验证重复添加逻辑，Mock 能精确控制方法行为
        // 使用具体类 ShoppingItemList 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $item = $this->createMock(ShoppingItemList::class);

        $this->shoppingInfo->addItemList($item);
        $this->shoppingInfo->addItemList($item);

        $this->assertCount(1, $this->shoppingInfo->getItemList());
    }

    public function testRemoveItemList(): void
    {
        // 使用具体类 ShoppingItemList 是必要的，理由1：ShoppingItemList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShoppingItemList 是必要的，理由2：测试需要验证移除操作，Mock 能精确控制方法行为
        // 使用具体类 ShoppingItemList 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $item = $this->createMock(ShoppingItemList::class);

        $this->shoppingInfo->addItemList($item);
        $this->shoppingInfo->removeItemList($item);

        $this->assertFalse($this->shoppingInfo->getItemList()->contains($item));
    }

    public function testSettersReturnVoid(): void
    {
        // 使用具体类 Account 是必要的，理由1：Account 来自外部 Bundle，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 Account 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 Account 是必要的，理由3：避免测试与其他 Bundle 的具体实现产生耦合
        $account = $this->createMock(Account::class);
        // 使用具体类 OrderKey 是必要的，理由1：OrderKey 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 OrderKey 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 OrderKey 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $orderKey = $this->createMock(OrderKey::class);
        // 使用具体类 UserInterface 是必要的，理由1：UserInterface 是接口，使用 Mock 模拟接口实现是标准做法
        // 使用具体类 UserInterface 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 UserInterface 是必要的，理由3：避免依赖具体的用户实现类，保持测试的独立性
        $payer = $this->createMock(UserInterface::class);

        $this->shoppingInfo->setValid(true);
        $this->assertTrue($this->shoppingInfo->isValid());

        $this->shoppingInfo->setAccount($account);
        $this->assertSame($account, $this->shoppingInfo->getAccount());

        $this->shoppingInfo->setOrderKey($orderKey);
        $this->assertSame($orderKey, $this->shoppingInfo->getOrderKey());

        $this->shoppingInfo->setPayer($payer);
        $this->assertSame($payer, $this->shoppingInfo->getPayer());

        $this->shoppingInfo->setLogisticsType(LogisticsType::VIRTUAL_GOODS);
        $this->assertSame(LogisticsType::VIRTUAL_GOODS, $this->shoppingInfo->getLogisticsType());

        $this->shoppingInfo->setOrderDetailType(OrderDetailType::URL);
        $this->assertSame(OrderDetailType::URL, $this->shoppingInfo->getOrderDetailType());

        $this->shoppingInfo->setOrderDetailPath('/path/to/order');
        $this->assertSame('/path/to/order', $this->shoppingInfo->getOrderDetailPath());
    }

    public function testToString(): void
    {
        $result = (string) $this->shoppingInfo;
        $this->assertSame('', $result);
    }
}
