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

        // 使用真实的 SubOrderList 实体，避免 Mock
        $subOrder = new SubOrderList();
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
        // 使用真实的 ShippingItemList 实体，避免 Mock
        $itemList = new ShippingItemList();

        $this->shippingList->addItemList($itemList);

        $this->assertTrue($this->shippingList->getItemList()->contains($itemList));
    }

    public function testRemoveItemList(): void
    {
        // 使用真实的 ShippingItemList 实体，避免 Mock
        $itemList = new ShippingItemList();

        $this->shippingList->addItemList($itemList);
        $this->shippingList->removeItemList($itemList);

        $this->assertFalse($this->shippingList->getItemList()->contains($itemList));
    }

    public function testGetterAndSetterForContact(): void
    {
        $this->assertNull($this->shippingList->getContact());

        // 使用真实的 Contact 实体，避免 Mock
        $contact = new Contact();
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
