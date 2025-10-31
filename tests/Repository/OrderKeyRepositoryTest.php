<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Repository\OrderKeyRepository;

/**
 * OrderKeyRepository 单元测试
 *
 * @internal
 */
#[CoversClass(OrderKeyRepository::class)]
#[RunTestsInSeparateProcesses]
final class OrderKeyRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 不需要特别的设置
    }

    /**
     * 测试根据订单ID查找订单
     */
    public function testFindByOrderIdReturnsCorrectOrder(): void
    {
        $orderKey1 = $this->createTestOrderKey(orderId: 'order_001');
        $this->createTestOrderKey(orderId: 'order_002');

        $result = $this->getRepository()->findByOrderId('order_001');

        $this->assertNotNull($result);
        $this->assertEquals('order_001', $result->getOrderId());
        $this->assertEquals($orderKey1->getId(), $result->getId());
    }

    /**
     * 创建测试订单标识
     */
    private function createTestOrderKey(
        string $orderId = 'order_123',
        string $outOrderId = 'out_order_123',
        string $openid = 'openid_test',
        string $pathId = 'path_123',
        ?string $outTradeNo = 'trade_123',
        ?string $transactionId = 'wx_123456',
        ?string $mchId = 'mch_123',
        OrderNumberType $orderNumberType = OrderNumberType::USE_MCH_ORDER,
    ): OrderKey {
        $orderKey = new OrderKey();
        $orderKey->setOrderId($orderId);
        $orderKey->setOutOrderId($outOrderId);
        $orderKey->setOpenid($openid);
        $orderKey->setPathId($pathId);
        $orderKey->setOutTradeNo($outTradeNo);
        $orderKey->setTransactionId($transactionId);
        $orderKey->setMchId($mchId);
        $orderKey->setOrderNumberType($orderNumberType);

        self::getEntityManager()->persist($orderKey);
        self::getEntityManager()->flush();

        return $orderKey;
    }

    /**
     * 测试根据不存在的订单ID查找
     */
    public function testFindByOrderIdWithNonExistentIdReturnsNull(): void
    {
        $this->createTestOrderKey(orderId: 'order_001');

        $result = $this->getRepository()->findByOrderId('nonexistent_order');

        $this->assertNull($result);
    }

    /**
     * 测试根据商户订单ID查找订单
     */
    public function testFindByOutOrderIdReturnsCorrectOrder(): void
    {
        $orderKey1 = $this->createTestOrderKey(outOrderId: 'out_order_001');
        $this->createTestOrderKey(outOrderId: 'out_order_002');

        $result = $this->getRepository()->findByOutOrderId('out_order_001');

        $this->assertNotNull($result);
        $this->assertEquals('out_order_001', $result->getOutOrderId());
        $this->assertEquals($orderKey1->getId(), $result->getId());
    }

    /**
     * 测试根据OpenID查找订单列表
     */
    public function testFindByOpenidReturnsCorrectOrders(): void
    {
        $this->createTestOrderKey(orderId: 'order_001', openid: 'openid_user1');
        $this->createTestOrderKey(orderId: 'order_002', openid: 'openid_user1');
        $this->createTestOrderKey(orderId: 'order_003', openid: 'openid_user2');

        $results = $this->getRepository()->findByOpenid('openid_user1');

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals('openid_user1', $result->getOpenid());
        }
    }

    /**
     * 测试根据路径ID查找订单列表
     */
    public function testFindByPathIdReturnsCorrectOrders(): void
    {
        $this->createTestOrderKey(orderId: 'order_001', pathId: 'path_001');
        $this->createTestOrderKey(orderId: 'order_002', pathId: 'path_001');
        $this->createTestOrderKey(orderId: 'order_003', pathId: 'path_002');

        $results = $this->getRepository()->findByPathId('path_001');

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals('path_001', $result->getPathId());
        }
    }

    /**
     * 测试根据创建者查找订单列表
     */
    public function testFindByCreatedByReturnsCorrectOrders(): void
    {
        $orderKey1 = $this->createTestOrderKey(orderId: 'order_001');
        $orderKey2 = $this->createTestOrderKey(orderId: 'order_002');

        // 设置创建者
        $orderKey1->setCreatedBy('user_001');
        $orderKey2->setCreatedBy('user_001');
        self::getEntityManager()->flush();

        $results = $this->getRepository()->findByCreatedBy('user_001');

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals('user_001', $result->getCreatedBy());
        }
    }

    /**
     * 测试根据商户号查找订单列表
     */
    public function testFindByMchIdReturnsCorrectOrders(): void
    {
        $this->createTestOrderKey(orderId: 'order_001', mchId: 'mch_001');
        $this->createTestOrderKey(orderId: 'order_002', mchId: 'mch_001');
        $this->createTestOrderKey(orderId: 'order_003', mchId: 'mch_002');

        $results = $this->getRepository()->findByMchId('mch_001');

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals('mch_001', $result->getMchId());
        }
    }

    /**
     * 测试根据商户号查找订单列表 - null值
     */
    public function testFindByMchIdWithNullReturnsCorrectOrders(): void
    {
        $this->createTestOrderKey(orderId: 'order_001', mchId: null);
        $this->createTestOrderKey(orderId: 'order_002', mchId: 'mch_001');

        $results = $this->getRepository()->findByMchId(null);

        $this->assertCount(1, $results);
        $this->assertNull($results[0]->getMchId());
    }

    /**
     * 测试根据订单号类型查找订单列表
     */
    public function testFindByOrderNumberTypeReturnsCorrectOrders(): void
    {
        $this->createTestOrderKey(orderId: 'order_001', orderNumberType: OrderNumberType::USE_MCH_ORDER);
        $this->createTestOrderKey(orderId: 'order_002', orderNumberType: OrderNumberType::USE_MCH_ORDER);
        $this->createTestOrderKey(orderId: 'order_003', orderNumberType: OrderNumberType::USE_WECHAT_ORDER);

        $results = $this->getRepository()->findByOrderNumberType(OrderNumberType::USE_MCH_ORDER);

        $this->assertGreaterThanOrEqual(2, count($results));
        foreach ($results as $result) {
            $this->assertEquals(OrderNumberType::USE_MCH_ORDER, $result->getOrderNumberType());
        }
    }

    /**
     * 测试根据商户系统内部订单号查找订单
     */
    public function testFindByOutTradeNoReturnsCorrectOrder(): void
    {
        $orderKey1 = $this->createTestOrderKey(outTradeNo: 'trade_001');
        $this->createTestOrderKey(outTradeNo: 'trade_002');

        $result = $this->getRepository()->findByOutTradeNo('trade_001');

        $this->assertNotNull($result);
        $this->assertEquals('trade_001', $result->getOutTradeNo());
        $this->assertEquals($orderKey1->getId(), $result->getId());
    }

    /**
     * 测试根据微信订单号查找订单
     */
    public function testFindByTransactionIdReturnsCorrectOrder(): void
    {
        $orderKey1 = $this->createTestOrderKey(transactionId: 'wx_001');
        $this->createTestOrderKey(transactionId: 'wx_002');

        $result = $this->getRepository()->findByTransactionId('wx_001');

        $this->assertNotNull($result);
        $this->assertEquals('wx_001', $result->getTransactionId());
        $this->assertEquals($orderKey1->getId(), $result->getId());
    }

    /**
     * 测试根据更新者查找订单列表
     */
    public function testFindByUpdatedByReturnsCorrectOrders(): void
    {
        $orderKey1 = $this->createTestOrderKey(orderId: 'order_001');
        $orderKey2 = $this->createTestOrderKey(orderId: 'order_002');

        // 设置更新者
        $orderKey1->setUpdatedBy('user_001');
        $orderKey2->setUpdatedBy('user_001');
        self::getEntityManager()->flush();

        $results = $this->getRepository()->findByUpdatedBy('user_001');

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals('user_001', $result->getUpdatedBy());
        }
    }

    /**
     * 测试保存订单标识
     */
    public function testSavePersistsOrderKeyCorrectly(): void
    {
        $orderKey = new OrderKey();
        $orderKey->setOrderId('test_order');
        $orderKey->setOutOrderId('test_out_order');
        $orderKey->setOpenid('test_openid');
        $orderKey->setPathId('test_path');

        $this->getRepository()->save($orderKey, flush: true);

        $savedOrderKey = $this->getRepository()->findByOrderId('test_order');
        $this->assertNotNull($savedOrderKey);
        $this->assertEquals('test_order', $savedOrderKey->getOrderId());
        $this->assertEquals('test_out_order', $savedOrderKey->getOutOrderId());
        $this->assertEquals('test_openid', $savedOrderKey->getOpenid());
        $this->assertEquals('test_path', $savedOrderKey->getPathId());
    }

    /**
     * 测试删除订单标识
     */
    public function testRemoveDeletesOrderKeyCorrectly(): void
    {
        $orderKey = $this->createTestOrderKey(orderId: 'order_to_delete');

        $this->getRepository()->remove($orderKey, flush: true);

        $deletedOrderKey = $this->getRepository()->findByOrderId('order_to_delete');
        $this->assertNull($deletedOrderKey);
    }

    /**
     * 测试查找不存在的商户订单号
     */
    public function testFindByOutTradeNoWithNonExistentTradeNoReturnsNull(): void
    {
        $this->createTestOrderKey(outTradeNo: 'trade_001');

        $result = $this->getRepository()->findByOutTradeNo('nonexistent_trade');

        $this->assertNull($result);
    }

    /**
     * 测试查找不存在的微信订单号
     */
    public function testFindByTransactionIdWithNonExistentIdReturnsNull(): void
    {
        $this->createTestOrderKey(transactionId: 'wx_001');

        $result = $this->getRepository()->findByTransactionId('nonexistent_wx');

        $this->assertNull($result);
    }

    /**
     * 测试边界情况 - 空OpenID列表
     */
    public function testFindByOpenidWithNonExistentOpenidReturnsEmptyArray(): void
    {
        $this->createTestOrderKey(openid: 'openid_user1');

        $results = $this->getRepository()->findByOpenid('nonexistent_openid');

        $this->assertEmpty($results);
    }

    /**
     * 测试边界情况 - 空路径ID列表
     */
    public function testFindByPathIdWithNonExistentPathIdReturnsEmptyArray(): void
    {
        $this->createTestOrderKey(pathId: 'path_001');

        $results = $this->getRepository()->findByPathId('nonexistent_path');

        $this->assertEmpty($results);
    }

    /**
     * 测试保存但不刷新
     */
    public function testSaveWithoutFlushDoesNotPersistImmediately(): void
    {
        $orderKey = new OrderKey();
        $orderKey->setOrderId('test_no_flush');
        $orderKey->setOutOrderId('test_out_order');
        $orderKey->setOpenid('test_openid');
        $orderKey->setPathId('test_path');

        $this->getRepository()->save($orderKey, flush: false);

        // 在flush前，从数据库查询应该找不到
        self::getEntityManager()->clear();
        $savedOrderKey = $this->getRepository()->findByOrderId('test_no_flush');
        $this->assertNull($savedOrderKey);

        // 重新persist实体，然后flush
        $orderKey = new OrderKey();
        $orderKey->setOrderId('test_no_flush');
        $orderKey->setOutOrderId('test_out_order');
        $orderKey->setOpenid('test_openid');
        $orderKey->setPathId('test_path');

        self::getEntityManager()->persist($orderKey);
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();

        $savedOrderKey = $this->getRepository()->findByOrderId('test_no_flush');
        $this->assertNotNull($savedOrderKey);
    }

    // Basic findBy tests required by PHPStan

    protected function onTearDown(): void
    {
        // EntityManager is managed by AbstractIntegrationTestCase
    }

    protected function createNewEntity(): object
    {
        $orderKey = new OrderKey();
        $orderKey->setOrderId('test_order_' . uniqid());
        $orderKey->setOutOrderId('test_out_order_' . uniqid());
        $orderKey->setOpenid('test_openid_' . uniqid());
        $orderKey->setPathId('test_path_' . uniqid());
        $orderKey->setOutTradeNo('test_trade_' . uniqid());
        $orderKey->setTransactionId('wx_test_' . uniqid());
        $orderKey->setMchId('mch_test_' . uniqid());
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);

        return $orderKey;
    }

    protected function getRepository(): OrderKeyRepository
    {
        $repository = self::getEntityManager()->getRepository(OrderKey::class);
        $this->assertInstanceOf(OrderKeyRepository::class, $repository);

        return $repository;
    }
}
