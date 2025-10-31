<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShippingList;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;
use WechatMiniProgramOrderBundle\Enum\DeliveryMode;

/**
 * @internal
 */
#[CoversClass(SubOrderList::class)]
final class SubOrderListTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new SubOrderList();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'deliveryMode' => ['deliveryMode', DeliveryMode::UNIFIED_DELIVERY],
        ];
    }

    private SubOrderList $subOrderList;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subOrderList = new SubOrderList();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->subOrderList->getId());
    }

    public function testGetterAndSetterForCombinedShippingInfo(): void
    {
        $this->assertNull($this->subOrderList->getCombinedShippingInfo());

        // 使用具体类 CombinedShippingInfo 是必要的，理由1：CombinedShippingInfo 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 CombinedShippingInfo 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 CombinedShippingInfo 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $combinedShippingInfo = $this->createMock(CombinedShippingInfo::class);
        $this->subOrderList->setCombinedShippingInfo($combinedShippingInfo);
        $this->assertSame($combinedShippingInfo, $this->subOrderList->getCombinedShippingInfo());

        $this->subOrderList->setCombinedShippingInfo(null);
        $this->assertNull($this->subOrderList->getCombinedShippingInfo());
    }

    public function testGetterAndSetterForOrderKey(): void
    {
        $this->assertNull($this->subOrderList->getOrderKey());

        // 使用具体类 OrderKey 是必要的，理由1：OrderKey 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 OrderKey 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 OrderKey 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
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
        // 使用具体类 ShippingList 是必要的，理由1：ShippingList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShippingList 是必要的，理由2：测试需要验证集合操作，Mock 能精确控制方法行为
        // 使用具体类 ShippingList 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $shipping = $this->createMock(ShippingList::class);

        $this->subOrderList->addShippingList($shipping);

        $this->assertTrue($this->subOrderList->getShippingList()->contains($shipping));
    }

    public function testAddShippingListAlreadyExists(): void
    {
        // 使用具体类 ShippingList 是必要的，理由1：ShippingList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShippingList 是必要的，理由2：测试需要验证重复添加逻辑，Mock 能精确控制方法行为
        // 使用具体类 ShippingList 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $shipping = $this->createMock(ShippingList::class);

        $this->subOrderList->addShippingList($shipping);
        $this->subOrderList->addShippingList($shipping);

        $this->assertCount(1, $this->subOrderList->getShippingList());
    }

    public function testRemoveShippingList(): void
    {
        // 使用具体类 ShippingList 是必要的，理由1：ShippingList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShippingList 是必要的，理由2：测试需要验证移除操作，Mock 能精确控制方法行为
        // 使用具体类 ShippingList 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $shipping = $this->createMock(ShippingList::class);

        $this->subOrderList->addShippingList($shipping);
        $this->subOrderList->removeShippingList($shipping);

        $this->assertFalse($this->subOrderList->getShippingList()->contains($shipping));
    }

    public function testSettersReturnVoid(): void
    {
        // 使用具体类 CombinedShippingInfo 是必要的，理由1：CombinedShippingInfo 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 CombinedShippingInfo 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 CombinedShippingInfo 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $combinedShippingInfo = $this->createMock(CombinedShippingInfo::class);
        // 使用具体类 OrderKey 是必要的，理由1：OrderKey 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 OrderKey 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 OrderKey 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $orderKey = $this->createMock(OrderKey::class);

        $this->subOrderList->setCombinedShippingInfo($combinedShippingInfo);
        $this->assertSame($combinedShippingInfo, $this->subOrderList->getCombinedShippingInfo());

        $this->subOrderList->setOrderKey($orderKey);
        $this->assertSame($orderKey, $this->subOrderList->getOrderKey());

        $this->subOrderList->setDeliveryMode(DeliveryMode::SPLIT_DELIVERY);
        $this->assertSame(DeliveryMode::SPLIT_DELIVERY, $this->subOrderList->getDeliveryMode());
    }

    public function testToString(): void
    {
        $result = (string) $this->subOrderList;
        $this->assertSame('', $result);
    }
}
