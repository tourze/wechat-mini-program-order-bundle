<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramOrderBundle\Entity\Contact;
use WechatMiniProgramOrderBundle\Entity\ShippingItemList;
use WechatMiniProgramOrderBundle\Entity\ShippingList;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;

/**
 * @internal
 */
#[CoversClass(ShippingList::class)]
final class ShippingListTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ShippingList();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'trackingNo' => ['trackingNo', 'test_tracking_no'],
            'expressCompany' => ['expressCompany', 'test_company'],
            'trackingInfo' => ['trackingInfo', ['status' => 'test']],
            'lastTrackingTime' => ['lastTrackingTime', new \DateTimeImmutable()],
        ];
    }

    private ShippingList $shippingList;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shippingList = new ShippingList();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->shippingList->getId());
    }

    public function testGetterAndSetterForSubOrder(): void
    {
        $this->assertNull($this->shippingList->getSubOrder());

        // 使用具体类 SubOrderList 是必要的，理由1：
        // 1. SubOrderList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
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
        // 使用具体类 ShippingItemList 是必要的，理由1：
        // 1. ShippingItemList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证集合操作，Mock 能精确控制方法行为
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $itemList = $this->createMock(ShippingItemList::class);

        $this->shippingList->addItemList($itemList);

        $this->assertTrue($this->shippingList->getItemList()->contains($itemList));
    }

    public function testRemoveItemList(): void
    {
        // 使用具体类 ShippingItemList 是必要的，理由1：
        // 1. ShippingItemList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证实体移除操作，Mock 能精确控制方法行为
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $itemList = $this->createMock(ShippingItemList::class);

        $this->shippingList->addItemList($itemList);
        $this->shippingList->removeItemList($itemList);

        $this->assertFalse($this->shippingList->getItemList()->contains($itemList));
    }

    public function testGetterAndSetterForContact(): void
    {
        $this->assertNull($this->shippingList->getContact());

        // 使用具体类 Contact 是必要的，理由1：
        // 1. Contact 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
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
