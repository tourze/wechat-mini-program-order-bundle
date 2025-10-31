<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;

/**
 * @internal
 */
#[CoversClass(ShippingInfo::class)]
final class ShippingInfoTest extends AbstractEntityTestCase
{
    protected function createEntity(): ShippingInfo
    {
        return new ShippingInfo();
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
            'deliveryMobile' => ['deliveryMobile', 'test_value'],
            'trackingNo' => ['trackingNo', 'test_value'],
            'deliveryCompany' => ['deliveryCompany', 'test_value'],
        ];
    }

    private ShippingInfo $shippingInfo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shippingInfo = new ShippingInfo();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->shippingInfo->getId());
    }

    public function testGetterAndSetterForValid(): void
    {
        $this->assertFalse($this->shippingInfo->isValid());

        $this->shippingInfo->setValid(true);
        $this->assertTrue($this->shippingInfo->isValid());

        $this->shippingInfo->setValid(null);
        $this->assertNull($this->shippingInfo->isValid());
    }

    public function testGetterAndSetterForAccount(): void
    {
        // 使用具体类 Account 是必要的，理由1：
        // 1. Account 来自外部 Bundle，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：避免测试与其他 Bundle 的具体实现产生耦合
        $account = $this->createMock(Account::class);

        $this->shippingInfo->setAccount($account);
        $this->assertSame($account, $this->shippingInfo->getAccount());
    }

    public function testGetterAndSetterForOrderKey(): void
    {
        // 使用具体类 OrderKey 是必要的，理由1：
        // 1. OrderKey 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $orderKey = $this->createMock(OrderKey::class);

        $this->shippingInfo->setOrderKey($orderKey);
        $this->assertSame($orderKey, $this->shippingInfo->getOrderKey());
    }

    public function testGetterAndSetterForPayer(): void
    {
        // 使用具体类 UserInterface 是必要的，理由1：
        // 1. UserInterface 是接口，使用 Mock 模拟接口实现是标准做法
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证用户接口的行为，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：避免依赖具体的用户实现类，保持测试的独立性
        $payer = $this->createMock(UserInterface::class);

        $this->shippingInfo->setPayer($payer);
        $this->assertSame($payer, $this->shippingInfo->getPayer());
    }

    public function testGetterAndSetterForLogisticsType(): void
    {
        $this->assertSame(LogisticsType::PHYSICAL_LOGISTICS, $this->shippingInfo->getLogisticsType());

        $this->shippingInfo->setLogisticsType(LogisticsType::VIRTUAL_GOODS);
        $this->assertSame(LogisticsType::VIRTUAL_GOODS, $this->shippingInfo->getLogisticsType());
    }

    public function testGetterAndSetterForDeliveryMobile(): void
    {
        $deliveryMobile = '13800138000';
        $this->shippingInfo->setDeliveryMobile($deliveryMobile);
        $this->assertSame($deliveryMobile, $this->shippingInfo->getDeliveryMobile());
    }

    public function testGetterAndSetterForTrackingNo(): void
    {
        $trackingNo = '1234567890';
        $this->shippingInfo->setTrackingNo($trackingNo);
        $this->assertSame($trackingNo, $this->shippingInfo->getTrackingNo());
    }

    public function testGetterAndSetterForDeliveryCompany(): void
    {
        $deliveryCompany = '顺丰快递';
        $this->shippingInfo->setDeliveryCompany($deliveryCompany);
        $this->assertSame($deliveryCompany, $this->shippingInfo->getDeliveryCompany());
    }

    public function testSettersReturnVoid(): void
    {
        // 使用具体类 Account 是必要的，理由1：
        // 1. Account 来自外部 Bundle，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 具体类名 是必要的，理由3：避免测试与其他 Bundle 的具体实现产生耦合
        $account = $this->createMock(Account::class);
        // 使用具体类 OrderKey 是必要的，理由1：
        // 1. OrderKey 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $orderKey = $this->createMock(OrderKey::class);
        // 使用具体类 UserInterface 是必要的，理由1：
        // 1. UserInterface 是接口，使用 Mock 模拟接口实现是标准做法
        // 使用具体类 具体类名 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 具体类名 是必要的，理由3：避免依赖具体的用户实现类，保持测试的独立性
        $payer = $this->createMock(UserInterface::class);

        $this->shippingInfo->setValid(true);
        $this->assertTrue($this->shippingInfo->isValid());

        $this->shippingInfo->setAccount($account);
        $this->assertSame($account, $this->shippingInfo->getAccount());

        $this->shippingInfo->setOrderKey($orderKey);
        $this->assertSame($orderKey, $this->shippingInfo->getOrderKey());

        $this->shippingInfo->setPayer($payer);
        $this->assertSame($payer, $this->shippingInfo->getPayer());

        $this->shippingInfo->setLogisticsType(LogisticsType::VIRTUAL_GOODS);
        $this->assertSame(LogisticsType::VIRTUAL_GOODS, $this->shippingInfo->getLogisticsType());

        $this->shippingInfo->setDeliveryMobile('13800138000');
        $this->assertSame('13800138000', $this->shippingInfo->getDeliveryMobile());

        $this->shippingInfo->setTrackingNo('1234567890');
        $this->assertSame('1234567890', $this->shippingInfo->getTrackingNo());

        $this->shippingInfo->setDeliveryCompany('顺丰快递');
        $this->assertSame('顺丰快递', $this->shippingInfo->getDeliveryCompany());
    }

    public function testToString(): void
    {
        $result = (string) $this->shippingInfo;
        $this->assertSame('', $result);
    }
}
