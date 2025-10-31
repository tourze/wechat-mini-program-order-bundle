<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramOrderBundle\Entity\ShippingItemList;
use WechatMiniProgramOrderBundle\Entity\ShippingList;

/**
 * @internal
 */
#[CoversClass(ShippingItemList::class)]
final class ShippingItemListTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ShippingItemList();
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

    private ShippingItemList $shippingItemList;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shippingItemList = new ShippingItemList();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->shippingItemList->getId());
    }

    public function testGetterAndSetterForShippingList(): void
    {
        $this->assertNull($this->shippingItemList->getShippingList());

        // 使用具体类 ShippingList 是必要的，理由1：ShippingList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShippingList 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 ShippingList 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
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

    public function testSettersReturnVoid(): void
    {
        // 使用具体类 ShippingList 是必要的，理由1：ShippingList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShippingList 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 ShippingList 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $shippingList = $this->createMock(ShippingList::class);

        $this->shippingItemList->setShippingList($shippingList);
        $this->assertSame($shippingList, $this->shippingItemList->getShippingList());

        $this->shippingItemList->setMerchantItemId('MERCHANT_ITEM_123');
        $this->assertSame('MERCHANT_ITEM_123', $this->shippingItemList->getMerchantItemId());
    }

    public function testToString(): void
    {
        $result = (string) $this->shippingItemList;
        $this->assertSame('', $result);
    }
}
