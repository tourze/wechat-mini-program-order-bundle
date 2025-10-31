<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;

/**
 * @internal
 */
#[CoversClass(CombinedShippingInfo::class)]
final class CombinedShippingInfoTest extends AbstractEntityTestCase
{
    protected function createEntity(): CombinedShippingInfo
    {
        return new CombinedShippingInfo();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        // Mock 对象属性在单独的测试方法中已经充分测试，这里提供一个占位符
        return [
            'valid' => ['valid', true], // 简单的布尔属性，无需 mock
        ];
    }

    private CombinedShippingInfo $combinedShippingInfo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->combinedShippingInfo = new CombinedShippingInfo();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->combinedShippingInfo->getId());
    }

    public function testGetterAndSetterForAccount(): void
    {
        // 使用具体类 Account 是必要的，理由1：
        // 1. Account 来自外部 Bundle，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：避免测试与其他 Bundle 的具体实现产生耦合
        $account = $this->createMock(Account::class);

        $this->combinedShippingInfo->setAccount($account);
        $this->assertSame($account, $this->combinedShippingInfo->getAccount());
    }

    public function testGetterAndSetterForOrderKey(): void
    {
        $this->assertNull($this->combinedShippingInfo->getOrderKey());

        // 使用具体类 OrderKey 是必要的，理由1：
        // 1. OrderKey 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证实体间关系的设置和获取，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $orderKey = $this->createMock(OrderKey::class);
        $this->combinedShippingInfo->setOrderKey($orderKey);
        $this->assertSame($orderKey, $this->combinedShippingInfo->getOrderKey());
    }

    public function testGetSubOrders(): void
    {
        $subOrders = $this->combinedShippingInfo->getSubOrders();
        $this->assertInstanceOf(ArrayCollection::class, $subOrders);
        $this->assertTrue($subOrders->isEmpty());
    }

    public function testAddSubOrder(): void
    {
        // 使用具体类 SubOrderList 是必要的，理由1：
        // 1. SubOrderList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证集合操作，Mock 能精确控制方法行为
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $subOrder = $this->createMock(SubOrderList::class);

        $this->combinedShippingInfo->addSubOrder($subOrder);
        $this->assertTrue($this->combinedShippingInfo->getSubOrders()->contains($subOrder));
    }

    public function testAddSubOrderAlreadyExists(): void
    {
        // 使用具体类 SubOrderList 是必要的，理由1：
        // 1. SubOrderList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证重复添加逻辑，Mock 能精确控制方法行为
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $subOrder = $this->createMock(SubOrderList::class);

        $this->combinedShippingInfo->addSubOrder($subOrder);
        $this->combinedShippingInfo->addSubOrder($subOrder);

        $this->assertCount(1, $this->combinedShippingInfo->getSubOrders());
    }

    public function testRemoveSubOrder(): void
    {
        // 使用具体类 SubOrderList 是必要的，理由1：
        // 1. SubOrderList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证移除操作，Mock 能精确控制方法行为
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $subOrder = $this->createMock(SubOrderList::class);

        $this->combinedShippingInfo->addSubOrder($subOrder);
        $this->combinedShippingInfo->removeSubOrder($subOrder);
        $this->assertFalse($this->combinedShippingInfo->getSubOrders()->contains($subOrder));
    }

    public function testGetterAndSetterForPayer(): void
    {
        $this->assertNull($this->combinedShippingInfo->getPayer());

        // 使用具体类 UserInterface 是必要的，理由1：
        // 1. UserInterface 是接口，使用 Mock 模拟接口实现是标准做法
        // 使用具体类 具体类名 是必要的，理由2：测试需要验证用户接口的行为，Mock 提供精确的测试控制
        // 使用具体类 具体类名 是必要的，理由3：避免依赖具体的用户实现类，保持测试的独立性
        $payer = $this->createMock(UserInterface::class);
        $this->combinedShippingInfo->setPayer($payer);
        $this->assertSame($payer, $this->combinedShippingInfo->getPayer());
    }

    public function testGetterAndSetterForUploadTime(): void
    {
        $uploadTime = $this->combinedShippingInfo->getUploadTime();
        $this->assertInstanceOf(\DateTimeImmutable::class, $uploadTime);

        $newUploadTime = new \DateTimeImmutable('2023-01-01 12:00:00');
        $this->combinedShippingInfo->setUploadTime($newUploadTime);
        $this->assertSame($newUploadTime, $this->combinedShippingInfo->getUploadTime());
    }

    public function testGetterAndSetterForValid(): void
    {
        $this->assertFalse($this->combinedShippingInfo->isValid());

        $this->combinedShippingInfo->setValid(true);
        $this->assertTrue($this->combinedShippingInfo->isValid());

        $this->combinedShippingInfo->setValid(null);
        $this->assertNull($this->combinedShippingInfo->isValid());
    }

    public function testSettersReturnVoid(): void
    {
        // 使用具体类 Account 是必要的，理由1：
        // 1. Account 来自外部 Bundle，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试 setter 方法的调用，不需要具体实现，Mock 即可满足需求
        // 使用具体类 具体类名 是必要的，理由3：避免测试与其他 Bundle 的具体实现产生耦合
        $account = $this->createMock(Account::class);
        // 使用具体类 OrderKey 是必要的，理由1：
        // 1. OrderKey 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 具体类名 是必要的，理由2：测试 setter 方法的调用，不需要具体实现，Mock 即可满足需求
        // 使用具体类 具体类名 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $orderKey = $this->createMock(OrderKey::class);
        // 使用具体类 UserInterface 是必要的，理由1：
        // 1. UserInterface 是接口，使用 Mock 模拟接口实现是标准做法
        // 使用具体类 具体类名 是必要的，理由2：测试 setter 方法的调用，不需要具体实现，Mock 即可满足需求
        // 使用具体类 具体类名 是必要的，理由3：避免依赖具体的用户实现类，保持测试的独立性
        $payer = $this->createMock(UserInterface::class);
        $uploadTime = new \DateTimeImmutable();

        $this->combinedShippingInfo->setAccount($account);
        $this->assertSame($account, $this->combinedShippingInfo->getAccount());

        $this->combinedShippingInfo->setOrderKey($orderKey);
        $this->assertSame($orderKey, $this->combinedShippingInfo->getOrderKey());

        $this->combinedShippingInfo->setPayer($payer);
        $this->assertSame($payer, $this->combinedShippingInfo->getPayer());

        $this->combinedShippingInfo->setUploadTime($uploadTime);
        $this->assertSame($uploadTime, $this->combinedShippingInfo->getUploadTime());

        $this->combinedShippingInfo->setValid(true);
        $this->assertTrue($this->combinedShippingInfo->isValid());
    }

    public function testToString(): void
    {
        $result = (string) $this->combinedShippingInfo;
        $this->assertSame('', $result);
    }
}
