<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramAuthBundle\Entity\User;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShippingList;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Repository\ShippingListRepository;

/**
 * ShippingListRepository 单元测试
 *
 * @internal
 */
#[CoversClass(ShippingListRepository::class)]
#[RunTestsInSeparateProcesses]
final class ShippingListRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 不需要特别的设置
    }

    /**
     * 测试根据订单ID查找物流信息
     */
    public function testFindByOrderIdReturnsCorrectShippingLists(): void
    {
        // 创建不同时间的物流信息来验证排序
        $older = new \DateTimeImmutable('2024-01-01 10:00:00');
        $newer = new \DateTimeImmutable('2024-01-01 12:00:00');

        $this->createTestShippingList(orderId: 'ORDER001', trackingNo: 'TN001', createTime: $older);
        $this->createTestShippingList(orderId: 'ORDER001', trackingNo: 'TN002', createTime: $newer);
        $this->createTestShippingList(orderId: 'ORDER002', trackingNo: 'TN003');

        $results = $this->getRepository()->findByOrderId('ORDER001');

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $subOrder = $result->getSubOrder();
            $this->assertNotNull($subOrder);
            $orderKey = $subOrder->getOrderKey();
            $this->assertNotNull($orderKey);
            $this->assertEquals('ORDER001', $orderKey->getOrderId());
        }

        // 验证按创建时间倒序排列（新的在前）
        $this->assertEquals('TN002', $results[0]->getTrackingNo());
        $this->assertEquals('TN001', $results[1]->getTrackingNo());
    }

    /**
     * 创建测试物流信息
     */
    private function createTestShippingList(
        string $orderId = 'ORDER123',
        string $trackingNo = 'TN123456789',
        ?\DateTimeInterface $lastTrackingTime = null,
        ?\DateTimeInterface $createTime = null,
    ): ShippingList {
        // 创建必要的相关实体
        $account = $this->createTestAccount();
        $user = new User();
        $user->setOpenId('test_user_id_' . uniqid());
        $user->setUnionId('test_union_id_' . uniqid());
        $user->setAvatarUrl('https://example.com/avatar.jpg');
        $orderKey = $this->createTestOrderKey($orderId);
        $combinedShippingInfo = $this->createTestCombinedShippingInfo($account, $orderKey, $user);

        // 创建 SubOrderList
        $subOrder = new SubOrderList();
        $subOrder->setOrderKey($orderKey);
        $subOrder->setCombinedShippingInfo($combinedShippingInfo);

        // 创建 ShippingList
        $shippingList = new ShippingList();
        $shippingList->setTrackingNo($trackingNo);
        $shippingList->setExpressCompany('TEST_EXPRESS');
        $shippingList->setSubOrder($subOrder);

        if (null !== $lastTrackingTime) {
            if (!$lastTrackingTime instanceof \DateTimeImmutable) {
                $lastTrackingTime = \DateTimeImmutable::createFromInterface($lastTrackingTime);
            }
            $shippingList->setLastTrackingTime($lastTrackingTime);
        }

        if (null !== $createTime) {
            if (!$createTime instanceof \DateTimeImmutable) {
                $createTime = \DateTimeImmutable::createFromInterface($createTime);
            }
            $shippingList->setCreateTime($createTime);
        }

        self::getEntityManager()->persist($account);
        self::getEntityManager()->persist($user);
        self::getEntityManager()->persist($orderKey);
        self::getEntityManager()->persist($combinedShippingInfo);
        self::getEntityManager()->persist($subOrder);
        self::getEntityManager()->persist($shippingList);
        self::getEntityManager()->flush();

        return $shippingList;
    }

    private function createTestAccount(): Account
    {
        $account = new Account();
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');
        $account->setName('Test Account');

        return $account;
    }

  
    private function createTestOrderKey(string $orderId): OrderKey
    {
        $orderKey = new OrderKey();
        $orderKey->setOrderId($orderId);
        $orderKey->setOutOrderId('OUT_' . $orderId);
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_transaction_' . uniqid());
        $orderKey->setMchId('test_mch_' . uniqid());
        $orderKey->setOutTradeNo('test_out_trade_' . uniqid());

        return $orderKey;
    }

    private function createTestCombinedShippingInfo(Account $account, OrderKey $orderKey, UserInterface $user): CombinedShippingInfo
    {
        $combinedShippingInfo = new CombinedShippingInfo();
        $combinedShippingInfo->setAccount($account);
        $combinedShippingInfo->setOrderKey($orderKey);
        $combinedShippingInfo->setPayer($user);
        $combinedShippingInfo->setUploadTime(new \DateTimeImmutable());
        $combinedShippingInfo->setValid(true);

        return $combinedShippingInfo;
    }

    /**
     * 测试根据物流单号查找物流信息
     */
    public function testFindByTrackingNoReturnsCorrectShippingList(): void
    {
        $shippingList1 = $this->createTestShippingList(trackingNo: 'TN001');
        $this->createTestShippingList(trackingNo: 'TN002');

        $result = $this->getRepository()->findByTrackingNo('TN001');

        $this->assertNotNull($result);
        $this->assertEquals('TN001', $result->getTrackingNo());
        $this->assertEquals($shippingList1->getId(), $result->getId());
    }

    /**
     * 测试根据不存在的物流单号查找
     */
    public function testFindByTrackingNoWithNonExistentNoReturnsNull(): void
    {
        $this->createTestShippingList(trackingNo: 'TN001');

        $result = $this->getRepository()->findByTrackingNo('NONEXISTENT');

        $this->assertNull($result);
    }

    /**
     * 测试查找需要更新物流信息的记录
     */
    public function testFindNeedUpdateTrackingReturnsCorrectRecords(): void
    {
        $beforeTime = new \DateTimeImmutable('2024-01-01 12:00:00');
        $oldTime = new \DateTimeImmutable('2024-01-01 10:00:00');
        $newTime = new \DateTimeImmutable('2024-01-01 14:00:00');

        // 创建需要更新的记录（lastTrackingTime为null）
        $this->createTestShippingList(orderId: 'ORDER001', lastTrackingTime: null);

        // 创建需要更新的记录（lastTrackingTime早于beforeTime）
        $this->createTestShippingList(orderId: 'ORDER002', lastTrackingTime: $oldTime);

        // 创建不需要更新的记录（lastTrackingTime晚于beforeTime）
        $this->createTestShippingList(orderId: 'ORDER003', lastTrackingTime: $newTime);

        $results = $this->getRepository()->findNeedUpdateTracking($beforeTime);

        $this->assertCount(2, $results);

        $orderIds = [];
        foreach ($results as $r) {
            $subOrder = $r->getSubOrder();
            if (null !== $subOrder) {
                $orderKey = $subOrder->getOrderKey();
                if (null !== $orderKey) {
                    $orderIds[] = $orderKey->getOrderId();
                }
            }
        }
        $this->assertContains('ORDER001', $orderIds);
        $this->assertContains('ORDER002', $orderIds);
        $this->assertNotContains('ORDER003', $orderIds);
    }

    /**
     * 测试保存物流信息
     */
    public function testSavePersistsShippingListCorrectly(): void
    {
        $shippingList = $this->createTestShippingList(orderId: 'TEST_SAVE_ORDER', trackingNo: 'TEST_SAVE_TN');

        $savedShippingList = $this->getRepository()->findByTrackingNo('TEST_SAVE_TN');
        $this->assertNotNull($savedShippingList);
        $subOrder = $savedShippingList->getSubOrder();
        $this->assertNotNull($subOrder);
        $orderKey = $subOrder->getOrderKey();
        $this->assertNotNull($orderKey);
        $this->assertEquals('TEST_SAVE_ORDER', $orderKey->getOrderId());
        $this->assertEquals('TEST_SAVE_TN', $savedShippingList->getTrackingNo());
    }

    /**
     * 测试删除物流信息
     */
    public function testRemoveDeletesShippingListCorrectly(): void
    {
        $shippingList = $this->createTestShippingList(trackingNo: 'TO_DELETE');

        $this->getRepository()->remove($shippingList, flush: true);

        $deletedShippingList = $this->getRepository()->findByTrackingNo('TO_DELETE');
        $this->assertNull($deletedShippingList);
    }

    /**
     * 测试边界情况 - 不存在的订单ID
     */
    public function testFindByOrderIdWithNonExistentIdReturnsEmptyArray(): void
    {
        $this->createTestShippingList(orderId: 'EXISTING_ORDER');

        $results = $this->getRepository()->findByOrderId('NONEXISTENT_ORDER');

        $this->assertEmpty($results);
    }

    /**
     * 测试保存但不刷新
     */
    public function testSaveWithoutFlushDoesNotPersistImmediately(): void
    {
        // 使用现有的 createTestShippingList 但先清理数据库状态
        self::getEntityManager()->clear();

        $shippingList = $this->createTestShippingList(trackingNo: 'TEST_NO_FLUSH_TN');

        // 创建一个新的 ShippingList 来测试保存但不刷新的功能
        $account = $this->createTestAccount();
        $user = new User();
        $user->setOpenId('test_user_id_' . uniqid());
        $user->setUnionId('test_union_id_' . uniqid());
        $user->setAvatarUrl('https://example.com/avatar.jpg');
        $orderKey = $this->createTestOrderKey('TEST_NO_FLUSH_2');
        $combinedShippingInfo = $this->createTestCombinedShippingInfo($account, $orderKey, $user);

        $subOrder = new SubOrderList();
        $subOrder->setOrderKey($orderKey);
        $subOrder->setCombinedShippingInfo($combinedShippingInfo);

        $newShippingList = new ShippingList();
        $newShippingList->setTrackingNo('TEST_NO_FLUSH_TN_2');
        $newShippingList->setExpressCompany('TEST_EXPRESS');
        $newShippingList->setSubOrder($subOrder);

        // 先持久化依赖实体
        self::getEntityManager()->persist($account);
        self::getEntityManager()->persist($user);
        self::getEntityManager()->persist($orderKey);
        self::getEntityManager()->persist($combinedShippingInfo);
        self::getEntityManager()->persist($subOrder);
        self::getEntityManager()->flush();

        // 现在测试保存但不刷新
        $this->getRepository()->save($newShippingList, flush: false);

        // 在flush前，实体应该在实体管理器中管理
        $this->assertTrue(self::getEntityManager()->contains($newShippingList));

        // 手动flush后应该能找到
        self::getEntityManager()->flush();
        $savedShippingList = $this->getRepository()->findByTrackingNo('TEST_NO_FLUSH_TN_2');
        $this->assertNotNull($savedShippingList);
    }

    /**
     * 测试查找需要更新物流信息的记录 - 边界时间
     */
    public function testFindNeedUpdateTrackingWithBoundaryTimeReturnsCorrectRecords(): void
    {
        $exactTime = new \DateTimeImmutable('2024-01-01 12:00:00');

        // 创建lastTrackingTime等于边界时间的记录
        $this->createTestShippingList(orderId: 'ORDER_EXACT', lastTrackingTime: $exactTime);

        // 查询边界时间，应该不包含等于边界时间的记录
        $results = $this->getRepository()->findNeedUpdateTracking($exactTime);

        $orderIds = [];
        foreach ($results as $r) {
            $subOrder = $r->getSubOrder();
            if (null !== $subOrder) {
                $orderKey = $subOrder->getOrderKey();
                if (null !== $orderKey) {
                    $orderIds[] = $orderKey->getOrderId();
                }
            }
        }
        $this->assertNotContains('ORDER_EXACT', $orderIds);
    }

    /**
     * 测试查找需要更新物流信息的记录 - 全部都不需要更新
     */
    public function testFindNeedUpdateTrackingWithAllRecentReturnsEmptyArray(): void
    {
        $beforeTime = new \DateTimeImmutable('2024-01-01 12:00:00');
        $recentTime = new \DateTimeImmutable('2024-01-01 14:00:00');

        // 创建所有记录的lastTrackingTime都晚于beforeTime
        $this->createTestShippingList(orderId: 'ORDER001', lastTrackingTime: $recentTime);
        $this->createTestShippingList(orderId: 'ORDER002', lastTrackingTime: $recentTime);

        $results = $this->getRepository()->findNeedUpdateTracking($beforeTime);

        $this->assertEmpty($results);
    }

    /**
     * 测试特殊字符的订单ID和物流单号
     */
    public function testFindByOrderIdWithSpecialCharactersReturnsCorrectResults(): void
    {
        $specialOrderId = 'ORDER-001_TEST@2024';
        $this->createTestShippingList(orderId: $specialOrderId, trackingNo: 'TN001');
        $this->createTestShippingList(orderId: $specialOrderId, trackingNo: 'TN002');

        $results = $this->getRepository()->findByOrderId($specialOrderId);

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $subOrder = $result->getSubOrder();
            $this->assertNotNull($subOrder);
            $orderKey = $subOrder->getOrderKey();
            $this->assertNotNull($orderKey);
            $this->assertEquals($specialOrderId, $orderKey->getOrderId());
        }
    }

    /**
     * 测试特殊字符的物流单号
     */
    public function testFindByTrackingNoWithSpecialCharactersReturnsCorrectResult(): void
    {
        $specialTrackingNo = 'TN-001_TEST@2024';
        $shippingList = $this->createTestShippingList(trackingNo: $specialTrackingNo);

        $result = $this->getRepository()->findByTrackingNo($specialTrackingNo);

        $this->assertNotNull($result);
        $this->assertEquals($specialTrackingNo, $result->getTrackingNo());
        $this->assertEquals($shippingList->getId(), $result->getId());
    }

    /**
     * 测试大量数据查询性能
     */
    public function testFindByOrderIdWithLargeDatasetReturnsCorrectResults(): void
    {
        // 为同一个订单创建多个物流记录
        for ($i = 1; $i <= 10; ++$i) {
            $this->createTestShippingList(
                orderId: 'BULK_ORDER',
                trackingNo: "TN{$i}",
                createTime: new \DateTimeImmutable('2024-01-01 ' . sprintf('%02d', $i) . ':00:00')
            );
        }

        // 为其他订单创建记录
        for ($i = 1; $i <= 5; ++$i) {
            $this->createTestShippingList(
                orderId: "OTHER_ORDER{$i}",
                trackingNo: "OTHER_TN{$i}"
            );
        }

        $results = $this->getRepository()->findByOrderId('BULK_ORDER');

        $this->assertCount(10, $results);
        foreach ($results as $result) {
            $subOrder = $result->getSubOrder();
            $this->assertNotNull($subOrder);
            $orderKey = $subOrder->getOrderKey();
            $this->assertNotNull($orderKey);
            $this->assertEquals('BULK_ORDER', $orderKey->getOrderId());
        }

        // 验证按创建时间倒序排列
        $trackingNos = array_map(fn ($r) => $r->getTrackingNo(), $results);
        $this->assertEquals('TN10', $trackingNos[0]); // 最新的在前
        $this->assertEquals('TN1', $trackingNos[9]);  // 最旧的在后
    }

    /**
     * 测试删除操作的完整性
     */
    public function testRemoveWithFlushFalseDoesNotDeleteImmediately(): void
    {
        $shippingList = $this->createTestShippingList(trackingNo: 'DELETE_TEST');

        $this->getRepository()->remove($shippingList, flush: false);

        // 在flush前，应该还能查到（因为还在EntityManager中）
        $result = $this->getRepository()->findByTrackingNo('DELETE_TEST');
        $this->assertNotNull($result);

        // 手动flush后应该删除
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
        $result = $this->getRepository()->findByTrackingNo('DELETE_TEST');
        $this->assertNull($result);
    }

    // Standard Doctrine Repository tests

    protected function onTearDown(): void
    {
        // EntityManager is managed by AbstractIntegrationTestCase
    }

    protected function createNewEntity(): object
    {
        // 创建不依赖持久化的独立ShippingList
        // 注意: AbstractRepositoryTestCase要求createNewEntity不要持久化实体
        $account = new Account();
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');
        $account->setName('Test Account');

        $user = new User();
        $user->setOpenId('test_user_id_' . uniqid());
        $user->setUnionId('test_union_id_' . uniqid());
        $user->setAvatarUrl('https://example.com/avatar.jpg');

        $orderKey = new OrderKey();
        $orderKey->setOrderId('TEST_ENTITY_' . uniqid());
        $orderKey->setOutOrderId('OUT_TEST_ENTITY');
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_transaction_' . uniqid());
        $orderKey->setMchId('test_mch_' . uniqid());
        $orderKey->setOutTradeNo('test_out_trade_' . uniqid());

        $combinedShippingInfo = new CombinedShippingInfo();
        $combinedShippingInfo->setAccount($account);
        $combinedShippingInfo->setOrderKey($orderKey);
        $combinedShippingInfo->setPayer($user);
        $combinedShippingInfo->setUploadTime(new \DateTimeImmutable());
        $combinedShippingInfo->setValid(true);

        $subOrder = new SubOrderList();
        $subOrder->setOrderKey($orderKey);
        $subOrder->setCombinedShippingInfo($combinedShippingInfo);

        $shippingList = new ShippingList();
        $shippingList->setTrackingNo('TN_' . uniqid());
        $shippingList->setExpressCompany('TEST_EXPRESS');
        $shippingList->setSubOrder($subOrder);

        return $shippingList;
    }

    
    protected function getRepository(): ShippingListRepository
    {
        $repository = self::getEntityManager()->getRepository(ShippingList::class);
        $this->assertInstanceOf(ShippingListRepository::class, $repository);

        return $repository;
    }
}
