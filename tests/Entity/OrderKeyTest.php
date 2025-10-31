<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;

/**
 * @internal
 */
#[CoversClass(OrderKey::class)]
final class OrderKeyTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new OrderKey();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'orderNumberType' => ['orderNumberType', OrderNumberType::USE_MCH_ORDER],
        ];
    }

    private OrderKey $orderKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderKey = new OrderKey();
    }

    public function testGetterAndSetterForOrderNumberType(): void
    {
        $this->assertSame(OrderNumberType::USE_MCH_ORDER, $this->orderKey->getOrderNumberType());

        $this->orderKey->setOrderNumberType(OrderNumberType::USE_WECHAT_ORDER);
        $this->assertSame(OrderNumberType::USE_WECHAT_ORDER, $this->orderKey->getOrderNumberType());
    }

    public function testGetterAndSetterForTransactionId(): void
    {
        $this->assertNull($this->orderKey->getTransactionId());

        $transactionId = 'transaction-123';
        $this->orderKey->setTransactionId($transactionId);
        $this->assertSame($transactionId, $this->orderKey->getTransactionId());
    }

    public function testGetterAndSetterForMchId(): void
    {
        $this->assertNull($this->orderKey->getMchId());

        $mchId = 'merchant-456';
        $this->orderKey->setMchId($mchId);
        $this->assertSame($mchId, $this->orderKey->getMchId());
    }

    public function testGetterAndSetterForOutTradeNo(): void
    {
        $this->assertNull($this->orderKey->getOutTradeNo());

        $outTradeNo = 'OUT-TRADE-789';
        $this->orderKey->setOutTradeNo($outTradeNo);
        $this->assertSame($outTradeNo, $this->orderKey->getOutTradeNo());
    }

    public function testGetterAndSetterForCreatedBy(): void
    {
        $this->assertNull($this->orderKey->getCreatedBy());

        $createdBy = 'admin-user';
        $this->orderKey->setCreatedBy($createdBy);
        $this->assertSame($createdBy, $this->orderKey->getCreatedBy());
    }

    public function testGetterAndSetterForUpdatedBy(): void
    {
        $this->assertNull($this->orderKey->getUpdatedBy());

        $updatedBy = 'editor-user';
        $this->orderKey->setUpdatedBy($updatedBy);
        $this->assertSame($updatedBy, $this->orderKey->getUpdatedBy());
    }

    public function testGetterAndSetterForCreateTime(): void
    {
        $this->assertNull($this->orderKey->getCreateTime());

        $createTime = new \DateTimeImmutable();
        $this->orderKey->setCreateTime($createTime);
        $this->assertSame($createTime, $this->orderKey->getCreateTime());
    }

    public function testGetterAndSetterForUpdateTime(): void
    {
        $this->assertNull($this->orderKey->getUpdateTime());

        $updateTime = new \DateTimeImmutable();
        $this->orderKey->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $this->orderKey->getUpdateTime());
    }

    public function testGetId(): void
    {
        $this->assertNull($this->orderKey->getId());
    }

    public function testSettersReturnVoid(): void
    {
        // 测试 setter 方法返回 void
        $this->orderKey->setOrderNumberType(OrderNumberType::USE_WECHAT_ORDER);
        $this->assertSame(OrderNumberType::USE_WECHAT_ORDER, $this->orderKey->getOrderNumberType());

        $this->orderKey->setTransactionId('transaction-123');
        $this->assertSame('transaction-123', $this->orderKey->getTransactionId());

        $this->orderKey->setMchId('merchant-456');
        $this->assertSame('merchant-456', $this->orderKey->getMchId());

        $this->orderKey->setOutTradeNo('OUT-TRADE-789');
        $this->assertSame('OUT-TRADE-789', $this->orderKey->getOutTradeNo());

        $this->orderKey->setOrderId('order-123');
        $this->assertSame('order-123', $this->orderKey->getOrderId());

        $this->orderKey->setOutOrderId('out-order-456');
        $this->assertSame('out-order-456', $this->orderKey->getOutOrderId());

        $this->orderKey->setOpenid('openid-123');
        $this->assertSame('openid-123', $this->orderKey->getOpenid());

        $this->orderKey->setPathId('path-123');
        $this->assertSame('path-123', $this->orderKey->getPathId());
    }
}
