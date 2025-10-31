<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\CombinedShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\Contact;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;

/**
 * @internal
 */
#[CoversClass(CombinedShoppingInfo::class)]
final class CombinedShoppingInfoTest extends AbstractEntityTestCase
{
    protected function createEntity(): CombinedShoppingInfo
    {
        return new CombinedShoppingInfo();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        // Mock 对象属性在单独的测试方法中已经充分测试，这里提供简单的属性
        return [
            'orderId' => ['orderId', 'test-order-123'], // 简单的字符串属性，无需 mock
        ];
    }

    private CombinedShoppingInfo $combinedShoppingInfo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->combinedShoppingInfo = new CombinedShoppingInfo();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getId());
    }

    public function testGetterAndSetterForAccount(): void
    {
        // 使用具体类 Account 是必要的，理由1：
        // 1. Account 来自外部 Bundle，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：避免测试与其他 Bundle 的具体实现产生耦合
        $account = $this->createMock(Account::class);

        $this->combinedShoppingInfo->setAccount($account);
        $this->assertSame($account, $this->combinedShoppingInfo->getAccount());
    }

    public function testGetterAndSetterForOrderId(): void
    {
        $orderId = 'order-123';
        $this->combinedShoppingInfo->setOrderId($orderId);
        $this->assertSame($orderId, $this->combinedShoppingInfo->getOrderId());
    }

    public function testGetterAndSetterForOutOrderId(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getOutOrderId());

        $outOrderId = 'out-order-456';
        $this->combinedShoppingInfo->setOutOrderId($outOrderId);
        $this->assertSame($outOrderId, $this->combinedShoppingInfo->getOutOrderId());
    }

    public function testGetterAndSetterForPathId(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getPathId());

        $pathId = 'path-789';
        $this->combinedShoppingInfo->setPathId($pathId);
        $this->assertSame($pathId, $this->combinedShoppingInfo->getPathId());
    }

    public function testGetterAndSetterForStatus(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getStatus());

        $status = 'completed';
        $this->combinedShoppingInfo->setStatus($status);
        $this->assertSame($status, $this->combinedShoppingInfo->getStatus());
    }

    public function testGetterAndSetterForTotalAmount(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getTotalAmount());

        $totalAmount = 10000;
        $this->combinedShoppingInfo->setTotalAmount($totalAmount);
        $this->assertSame($totalAmount, $this->combinedShoppingInfo->getTotalAmount());
    }

    public function testGetterAndSetterForPayAmount(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getPayAmount());

        $payAmount = 9500;
        $this->combinedShoppingInfo->setPayAmount($payAmount);
        $this->assertSame($payAmount, $this->combinedShoppingInfo->getPayAmount());
    }

    public function testGetterAndSetterForDiscountAmount(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getDiscountAmount());

        $discountAmount = 500;
        $this->combinedShoppingInfo->setDiscountAmount($discountAmount);
        $this->assertSame($discountAmount, $this->combinedShoppingInfo->getDiscountAmount());
    }

    public function testGetterAndSetterForFreightAmount(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getFreightAmount());

        $freightAmount = 1000;
        $this->combinedShoppingInfo->setFreightAmount($freightAmount);
        $this->assertSame($freightAmount, $this->combinedShoppingInfo->getFreightAmount());
    }

    public function testGetterAndSetterForPayer(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getPayer());

        // 使用具体类 UserInterface 是必要的，理由1：
        // 1. UserInterface 是接口，使用 Mock 模拟接口实现是标准做法
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证用户接口的行为，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：避免依赖具体的用户实现类，保持测试的独立性
        $payer = $this->createMock(UserInterface::class);
        $this->combinedShoppingInfo->setPayer($payer);
        $this->assertSame($payer, $this->combinedShoppingInfo->getPayer());
    }

    public function testGetterAndSetterForContact(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getContact());

        // 使用具体类 Contact 是必要的，理由1：
        // 1. Contact 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $contact = $this->createMock(Contact::class);
        $this->combinedShoppingInfo->setContact($contact);
        $this->assertSame($contact, $this->combinedShoppingInfo->getContact());
    }

    public function testGetterAndSetterForShippingInfo(): void
    {
        $this->assertNull($this->combinedShoppingInfo->getShippingInfo());

        // 使用具体类 ShippingInfo 是必要的，理由1：
        // 1. ShippingInfo 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $shippingInfo = $this->createMock(ShippingInfo::class);
        $this->combinedShoppingInfo->setShippingInfo($shippingInfo);
        $this->assertSame($shippingInfo, $this->combinedShoppingInfo->getShippingInfo());
    }

    public function testSettersReturnVoid(): void
    {
        // 使用具体类 Account 是必要的，理由1：
        // 1. Account 来自外部 Bundle，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 具体类名 是必要的，理由3：避免测试与其他 Bundle 的具体实现产生耦合
        $account = $this->createMock(Account::class);
        // 使用具体类 UserInterface 是必要的，理由1：
        // 1. UserInterface 是接口，使用 Mock 模拟接口实现是标准做法
        // 使用具体类 具体类名 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 具体类名 是必要的，理由3：避免依赖具体的用户实现类，保持测试的独立性
        $payer = $this->createMock(UserInterface::class);
        // 使用具体类 Contact 是必要的，理由1：
        // 1. Contact 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $contact = $this->createMock(Contact::class);
        // 使用具体类 ShippingInfo 是必要的，理由1：
        // 1. ShippingInfo 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试流式接口的返回值，不需要具体实现，Mock 即可满足需求
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依账，使用 Mock 是标准做法
        $shippingInfo = $this->createMock(ShippingInfo::class);

        $this->combinedShoppingInfo->setAccount($account);
        $this->assertSame($account, $this->combinedShoppingInfo->getAccount());

        $this->combinedShoppingInfo->setOrderId('order-123');
        $this->assertEquals('order-123', $this->combinedShoppingInfo->getOrderId());

        $this->combinedShoppingInfo->setOutOrderId('out-order-456');
        $this->assertEquals('out-order-456', $this->combinedShoppingInfo->getOutOrderId());

        $this->combinedShoppingInfo->setPathId('path-789');
        $this->assertEquals('path-789', $this->combinedShoppingInfo->getPathId());

        $this->combinedShoppingInfo->setStatus('completed');
        $this->assertEquals('completed', $this->combinedShoppingInfo->getStatus());

        $this->combinedShoppingInfo->setTotalAmount(10000);
        $this->assertEquals(10000, $this->combinedShoppingInfo->getTotalAmount());

        $this->combinedShoppingInfo->setPayAmount(9500);
        $this->assertEquals(9500, $this->combinedShoppingInfo->getPayAmount());

        $this->combinedShoppingInfo->setDiscountAmount(500);
        $this->assertEquals(500, $this->combinedShoppingInfo->getDiscountAmount());

        $this->combinedShoppingInfo->setFreightAmount(1000);
        $this->assertEquals(1000, $this->combinedShoppingInfo->getFreightAmount());

        $this->combinedShoppingInfo->setPayer($payer);
        $this->assertSame($payer, $this->combinedShoppingInfo->getPayer());

        $this->combinedShoppingInfo->setContact($contact);
        $this->assertSame($contact, $this->combinedShoppingInfo->getContact());

        $this->combinedShoppingInfo->setShippingInfo($shippingInfo);
        $this->assertSame($shippingInfo, $this->combinedShoppingInfo->getShippingInfo());
    }

    public function testToString(): void
    {
        $result = (string) $this->combinedShoppingInfo;
        $this->assertSame('', $result);
    }
}
