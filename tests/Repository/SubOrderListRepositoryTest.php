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
use WechatMiniProgramOrderBundle\Entity\SubOrderList;
use WechatMiniProgramOrderBundle\Enum\DeliveryMode;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Repository\SubOrderListRepository;

/**
 * @internal
 */
#[CoversClass(SubOrderListRepository::class)]
#[RunTestsInSeparateProcesses]
final class SubOrderListRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 不需要特别的设置
    }

    private Account $testAccount;

    private UserInterface $testUser;

    private CombinedShippingInfo $testCombinedShippingInfo;

    private OrderKey $testOrderKey;

    private function initializeTestData(): void
    {
        if (isset($this->testAccount)) {
            return; // 已经初始化过了
        }

        // 创建测试用的基础数据，按依赖顺序
        $this->testAccount = $this->createTestAccount();
        $this->testOrderKey = $this->createTestOrderKey();
        $this->testUser = new User();
        $this->testUser->setOpenId('test_user_id_' . uniqid());
        $this->testUser->setUnionId('test_union_id_' . uniqid());
        $this->testUser->setAvatarUrl('https://example.com/avatar.jpg');
        $this->testCombinedShippingInfo = $this->createTestCombinedShippingInfo($this->testAccount, $this->testOrderKey, $this->testUser);

        $this->persistAndFlush($this->testAccount);
        $this->persistAndFlush($this->testOrderKey);
        $this->persistAndFlush($this->testCombinedShippingInfo);
    }

    private function createTestAccount(): Account
    {
        $account = new Account();
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');
        $account->setName('Test Account');

        return $account;
    }

    
    private function createTestOrderKey(): OrderKey
    {
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_transaction_' . uniqid());
        $orderKey->setMchId('test_mch_' . uniqid());
        $orderKey->setOutTradeNo('test_out_trade_' . uniqid());

        return $orderKey;
    }

    private function createTestCombinedShippingInfo(?Account $account = null, ?OrderKey $orderKey = null, ?UserInterface $user = null): CombinedShippingInfo
    {
        $combinedShippingInfo = new CombinedShippingInfo();
        $combinedShippingInfo->setAccount($account ?? $this->testAccount);
        $combinedShippingInfo->setOrderKey($orderKey ?? $this->testOrderKey);
        $combinedShippingInfo->setPayer($user ?? $this->testUser);
        $combinedShippingInfo->setUploadTime(new \DateTimeImmutable());
        $combinedShippingInfo->setValid(true);

        return $combinedShippingInfo;
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private function createTestSubOrderList(array $overrides = []): SubOrderList
    {
        $this->initializeTestData(); // 确保测试数据已初始化

        $subOrderKey = new OrderKey();
        $subOrderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $subOrderKey->setTransactionId('test_sub_transaction_' . uniqid());
        $subOrderKey->setMchId('test_sub_mch_' . uniqid());
        $subOrderKey->setOutTradeNo('test_sub_out_trade_' . uniqid());

        $subOrderList = new SubOrderList();
        $subOrderList->setCombinedShippingInfo($this->testCombinedShippingInfo);
        $subOrderList->setOrderKey($subOrderKey);
        $subOrderList->setDeliveryMode(DeliveryMode::UNIFIED_DELIVERY);

        // 应用覆盖参数（类型安全）
        if (isset($overrides['deliveryMode'])) {
            /** @var DeliveryMode $deliveryMode */
            $deliveryMode = $overrides['deliveryMode'];
            $subOrderList->setDeliveryMode($deliveryMode);
        }
        if (isset($overrides['orderKey'])) {
            /** @var OrderKey|null $overrideOrderKey */
            $overrideOrderKey = $overrides['orderKey'];
            $subOrderList->setOrderKey($overrideOrderKey);
        }
        if (isset($overrides['combinedShippingInfo'])) {
            /** @var CombinedShippingInfo|null $overrideShippingInfo */
            $overrideShippingInfo = $overrides['combinedShippingInfo'];
            $subOrderList->setCombinedShippingInfo($overrideShippingInfo);
        }

        return $subOrderList;
    }

    // 基础功能测试

    public function testSaveAndFlushEntity(): void
    {
        $entity = $this->createTestSubOrderList();

        $this->getRepository()->save($entity, true);

        $this->assertEntityPersisted($entity);
        self::assertNotNull($entity->getId());
    }

    public function testSaveWithoutFlush(): void
    {
        $entity = $this->createTestSubOrderList();

        $this->getRepository()->save($entity, false);

        // 检查实体是否被正确标记为持久化
        self::assertTrue(self::getEntityManager()->contains($entity));
        self::assertTrue(self::getEntityManager()->getUnitOfWork()->isScheduledForInsert($entity));

        // 手动flush后应该能找到
        self::getEntityManager()->flush();
        $found = $this->getRepository()->find($entity->getId());
        self::assertNotNull($found);
    }

    public function testRemoveAndFlushEntity(): void
    {
        $entity = $this->createTestSubOrderList();
        $this->persistAndFlush($entity);
        $entityId = $entity->getId();

        $this->getRepository()->remove($entity, true);

        $this->assertEntityNotExists(SubOrderList::class, $entityId);

        // Ensure the entity was actually removed
        $found = $this->getRepository()->find($entityId);
        self::assertNull($found);
    }

    // findByCombinedShippingInfoId 测试

    public function testFindByCombinedShippingInfoIdReturnsEmptyArray(): void
    {
        $result = $this->getRepository()->findByCombinedShippingInfoId('nonexistent_id');

        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    public function testFindByCombinedShippingInfoIdWithValidId(): void
    {
        $entity = $this->createTestSubOrderList();
        $this->persistAndFlush($entity);

        $combinedShippingInfoId = $this->testCombinedShippingInfo->getId();
        self::assertNotNull($combinedShippingInfoId);
        $result = $this->getRepository()->findByCombinedShippingInfoId($combinedShippingInfoId);

        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertSame($entity, $result[0]);
    }

    public function testFindByCombinedShippingInfoIdWithMultipleResults(): void
    {
        $entity1 = $this->createTestSubOrderList();
        $orderKey2 = new OrderKey();
        $orderKey2->setOrderNumberType(OrderNumberType::USE_WECHAT_ORDER);
        $orderKey2->setTransactionId('test_sub_transaction_2_' . uniqid());
        $orderKey2->setMchId('test_sub_mch_2_' . uniqid());
        $orderKey2->setOutTradeNo('test_sub_out_trade_2_' . uniqid());

        $entity2 = $this->createTestSubOrderList([
            'orderKey' => $orderKey2,
            'deliveryMode' => DeliveryMode::SPLIT_DELIVERY,
        ]);

        $this->persistEntities([$entity1, $entity2]);
        self::getEntityManager()->flush();

        $combinedShippingInfoId = $this->testCombinedShippingInfo->getId();
        self::assertNotNull($combinedShippingInfoId);
        $result = $this->getRepository()->findByCombinedShippingInfoId($combinedShippingInfoId);

        self::assertIsArray($result);
        self::assertCount(2, $result);
    }

    public function testFindByCombinedShippingInfoIdWithEmptyString(): void
    {
        $result = $this->getRepository()->findByCombinedShippingInfoId('');

        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    // findByDeliveryMode 测试

    public function testFindByDeliveryModeUnified(): void
    {
        // 首先清理所有现有的实体
        $existingEntities = $this->getRepository()->findByDeliveryMode(DeliveryMode::UNIFIED_DELIVERY);
        foreach ($existingEntities as $existingEntity) {
            $this->getRepository()->remove($existingEntity, false);
        }
        self::getEntityManager()->flush();

        $entity = $this->createTestSubOrderList(['deliveryMode' => DeliveryMode::UNIFIED_DELIVERY]);
        $this->persistAndFlush($entity);

        $result = $this->getRepository()->findByDeliveryMode(DeliveryMode::UNIFIED_DELIVERY);

        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertSame($entity, $result[0]);
    }

    public function testFindByDeliveryModeSplit(): void
    {
        $entity = $this->createTestSubOrderList(['deliveryMode' => DeliveryMode::SPLIT_DELIVERY]);
        $this->persistAndFlush($entity);

        $result = $this->getRepository()->findByDeliveryMode(DeliveryMode::SPLIT_DELIVERY);

        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertSame($entity, $result[0]);
    }

    public function testFindByDeliveryModeWithNoResults(): void
    {
        // 创建统一发货模式的实体
        $entity = $this->createTestSubOrderList(['deliveryMode' => DeliveryMode::UNIFIED_DELIVERY]);
        $this->persistAndFlush($entity);

        // 查找分拆发货模式，应该没有结果
        $result = $this->getRepository()->findByDeliveryMode(DeliveryMode::SPLIT_DELIVERY);

        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    public function testFindByDeliveryModeWithMultipleResults(): void
    {
        // 首先清理所有现有的 UNIFIED_DELIVERY 实体
        $existingEntities = $this->getRepository()->findByDeliveryMode(DeliveryMode::UNIFIED_DELIVERY);
        foreach ($existingEntities as $existingEntity) {
            $this->getRepository()->remove($existingEntity, false);
        }
        self::getEntityManager()->flush();

        $entity1 = $this->createTestSubOrderList(['deliveryMode' => DeliveryMode::UNIFIED_DELIVERY]);
        $orderKey2 = new OrderKey();
        $orderKey2->setOrderNumberType(OrderNumberType::USE_WECHAT_ORDER);
        $orderKey2->setTransactionId('test_multi_transaction_' . uniqid());
        $orderKey2->setMchId('test_multi_mch_' . uniqid());
        $orderKey2->setOutTradeNo('test_multi_out_trade_' . uniqid());

        $entity2 = $this->createTestSubOrderList([
            'deliveryMode' => DeliveryMode::UNIFIED_DELIVERY,
            'orderKey' => $orderKey2,
        ]);

        $this->persistEntities([$entity1, $entity2]);
        self::getEntityManager()->flush();

        $result = $this->getRepository()->findByDeliveryMode(DeliveryMode::UNIFIED_DELIVERY);

        self::assertIsArray($result);
        self::assertCount(2, $result);
    }

    // findByOrderKey 测试 (复杂查询测试)

    public function testFindByOrderKeyReturnsNull(): void
    {
        $result = $this->getRepository()->findByOrderKey('nonexistent_order_id', 'nonexistent_out_order_id');

        self::assertNull($result);
    }

    public function testFindByOrderKeyWithValidParameters(): void
    {
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_findby_transaction_' . uniqid());
        $orderKey->setOrderId('test_findby_order_id_123');
        $orderKey->setOutOrderId('test_findby_out_order_id_456');

        $entity = $this->createTestSubOrderList(['orderKey' => $orderKey]);
        $this->persistAndFlush($entity);

        $result = $this->getRepository()->findByOrderKey('test_findby_order_id_123', 'test_findby_out_order_id_456');

        self::assertSame($entity, $result);
    }

    public function testFindByOrderKeyWithEmptyStrings(): void
    {
        $result = $this->getRepository()->findByOrderKey('', '');

        self::assertNull($result);
    }

    public function testFindByOrderKeyWithPartialMatch(): void
    {
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_partial_transaction_' . uniqid());
        $orderKey->setMchId('test_partial_mch_123');
        $orderKey->setOutTradeNo('test_partial_out_trade_456');

        $entity = $this->createTestSubOrderList(['orderKey' => $orderKey]);
        $this->persistAndFlush($entity);

        // 只匹配一个参数，应该返回null
        $result = $this->getRepository()->findByOrderKey('test_partial_mch_123', 'wrong_out_trade');

        self::assertNull($result);
    }

    // 可空字段的 IS NULL 查询测试

    public function testFindWithNullCombinedShippingInfo(): void
    {
        // 创建一个没有关联CombinedShippingInfo的SubOrderList (虽然在实际业务中不太可能)
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_null_combined_transaction_' . uniqid());
        $orderKey->setMchId('test_null_combined_mch_' . uniqid());
        $orderKey->setOutTradeNo('test_null_combined_out_trade_' . uniqid());

        $entity = new SubOrderList();
        $entity->setCombinedShippingInfo(null);
        $entity->setOrderKey($orderKey);
        $entity->setDeliveryMode(DeliveryMode::UNIFIED_DELIVERY);

        // 注意：由于combinedShippingInfo在实体中标记为nullable=false，
        // 这个测试可能会失败，这是预期的业务约束
        try {
            $this->persistAndFlush($entity);

            // 如果能够持久化，测试查询功能
            $entityManager = self::getEntityManager();
            $result = $entityManager
                ->createQuery('SELECT s FROM ' . SubOrderList::class . ' s WHERE s.combinedShippingInfo IS NULL')
                ->getResult()
            ;

            self::assertIsArray($result);
            self::assertCount(1, $result);
        } catch (\Exception $e) {
            // 这是预期的，因为combinedShippingInfo是必需的
            self::assertStringContainsString('NOT NULL', $e->getMessage());
        }
    }

    // 边界情况和错误处理测试

    public function testDeliveryModeDefaultValue(): void
    {
        // 创建必要的依赖实体
        $account = new Account();
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');
        $account->setName('Test Account');

        $user = new User();
        $user->setOpenId('test_user_id_' . uniqid());
        $user->setUnionId('test_union_id_' . uniqid());
        $user->setAvatarUrl('https://example.com/avatar.jpg');

        $combinedOrderKey = new OrderKey();
        $combinedOrderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $combinedOrderKey->setOrderId('TEST_COMBINED_ORDER_' . uniqid());
        $combinedOrderKey->setOutOrderId('OUT_TEST_COMBINED_ORDER_' . uniqid());
        $combinedOrderKey->setTransactionId('test_combined_transaction_' . uniqid());
        $combinedOrderKey->setMchId('test_combined_mch_' . uniqid());
        $combinedOrderKey->setOutTradeNo('test_combined_out_trade_' . uniqid());

        $combinedShippingInfo = new CombinedShippingInfo();
        $combinedShippingInfo->setAccount($account);
        $combinedShippingInfo->setOrderKey($combinedOrderKey);
        $combinedShippingInfo->setPayer($user);
        $combinedShippingInfo->setUploadTime(new \DateTimeImmutable());
        $combinedShippingInfo->setValid(true);

        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setOrderId('TEST_ORDER_' . uniqid());
        $orderKey->setOutOrderId('OUT_TEST_ORDER_' . uniqid());
        $orderKey->setTransactionId('test_default_transaction_' . uniqid());
        $orderKey->setMchId('test_default_mch_' . uniqid());
        $orderKey->setOutTradeNo('test_default_out_trade_' . uniqid());

        $entity = new SubOrderList();
        $entity->setCombinedShippingInfo($combinedShippingInfo);
        $entity->setOrderKey($orderKey);
        // 不设置deliveryMode，应该使用默认值

        self::assertEquals(DeliveryMode::UNIFIED_DELIVERY, $entity->getDeliveryMode());
    }

    public function testShippingListCollectionInitialization(): void
    {
        $entity = $this->createTestSubOrderList();

        self::assertCount(0, $entity->getShippingList());
    }

    // 性能测试

    public function testRepositoryPerformanceWithMultipleEntities(): void
    {
        // 创建多个实体
        $entities = [];
        for ($i = 0; $i < 15; ++$i) {
            $orderKey = new OrderKey();
            $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
            $orderKey->setTransactionId('test_transaction_perf_' . $i . '_' . uniqid());
            $orderKey->setMchId('test_mch_perf_' . $i . '_' . uniqid());
            $orderKey->setOutTradeNo('test_out_trade_perf_' . $i . '_' . uniqid());

            $entities[] = $this->createTestSubOrderList([
                'orderKey' => $orderKey,
                'deliveryMode' => 0 === $i % 2 ? DeliveryMode::UNIFIED_DELIVERY : DeliveryMode::SPLIT_DELIVERY,
            ]);
        }

        $this->persistEntities($entities);
        self::getEntityManager()->flush();

        // 测试批量查找
        $combinedShippingInfoId = $this->testCombinedShippingInfo->getId();
        self::assertNotNull($combinedShippingInfoId);
        $result = $this->getRepository()->findByCombinedShippingInfoId($combinedShippingInfoId);

        self::assertCount(15, $result);
        self::assertIsArray($result);
    }

    // 复杂查询和业务逻辑测试

    public function testRepositoryHandlesComplexBusinessScenarios(): void
    {
        // 创建不同发货模式的子订单
        $unifiedEntity1 = $this->createTestSubOrderList(['deliveryMode' => DeliveryMode::UNIFIED_DELIVERY]);
        $orderKey2 = new OrderKey();
        $orderKey2->setOrderNumberType(OrderNumberType::USE_WECHAT_ORDER);
        $orderKey2->setTransactionId('test_complex_transaction_1_' . uniqid());
        $orderKey2->setMchId('test_complex_mch_1_' . uniqid());
        $orderKey2->setOutTradeNo('test_complex_out_trade_1_' . uniqid());

        $unifiedEntity2 = $this->createTestSubOrderList([
            'deliveryMode' => DeliveryMode::UNIFIED_DELIVERY,
            'orderKey' => $orderKey2,
        ]);

        $orderKey3 = new OrderKey();
        $orderKey3->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey3->setTransactionId('test_complex_transaction_2_' . uniqid());
        $orderKey3->setMchId('test_complex_mch_2_' . uniqid());
        $orderKey3->setOutTradeNo('test_complex_out_trade_2_' . uniqid());

        $splitEntity = $this->createTestSubOrderList([
            'deliveryMode' => DeliveryMode::SPLIT_DELIVERY,
            'orderKey' => $orderKey3,
        ]);

        $this->persistEntities([$unifiedEntity1, $unifiedEntity2, $splitEntity]);
        self::getEntityManager()->flush();

        // 测试不同查询方法的组合
        $combinedShippingInfoId = $this->testCombinedShippingInfo->getId();
        $this->assertNotNull($combinedShippingInfoId);
        $allByCombined = $this->getRepository()->findByCombinedShippingInfoId($combinedShippingInfoId);
        $unifiedDelivery = $this->getRepository()->findByDeliveryMode(DeliveryMode::UNIFIED_DELIVERY);
        $splitDelivery = $this->getRepository()->findByDeliveryMode(DeliveryMode::SPLIT_DELIVERY);

        // 只检查我们创建的实体
        $createdEntities = [$unifiedEntity1, $unifiedEntity2, $splitEntity];
        self::assertCount(3, $allByCombined);

        // 检查我们创建的实体是否在结果中
        $foundUnified = array_filter($unifiedDelivery, function ($entity) use ($createdEntities) {
            return in_array($entity, $createdEntities, true);
        });
        $foundSplit = array_filter($splitDelivery, function ($entity) use ($createdEntities) {
            return in_array($entity, $createdEntities, true);
        });

        self::assertCount(2, $foundUnified);
        self::assertCount(1, $foundSplit);
    }

    // 数据完整性测试

    public function testEntityRelationshipsAreProperlyMaintained(): void
    {
        $entity = $this->createTestSubOrderList();
        $this->persistAndFlush($entity);

        // 清理缓存确保从数据库重新加载
        self::getEntityManager()->clear();

        $reloaded = $this->getRepository()->find($entity->getId());

        self::assertNotNull($reloaded);
        self::assertInstanceOf(SubOrderList::class, $reloaded);
        self::assertNotNull($reloaded->getCombinedShippingInfo());
        self::assertNotNull($reloaded->getOrderKey());
        self::assertSame($this->testCombinedShippingInfo->getId(), $reloaded->getCombinedShippingInfo()->getId());
    }

    // 测试枚举值的正确处理

    public function testDeliveryModeEnumHandling(): void
    {
        $unifiedEntity = $this->createTestSubOrderList(['deliveryMode' => DeliveryMode::UNIFIED_DELIVERY]);
        $orderKey2 = new OrderKey();
        $orderKey2->setOrderNumberType(OrderNumberType::USE_WECHAT_ORDER);
        $orderKey2->setTransactionId('test_enum_transaction_' . uniqid());
        $orderKey2->setMchId('test_enum_mch_' . uniqid());
        $orderKey2->setOutTradeNo('test_enum_out_trade_' . uniqid());

        $splitEntity = $this->createTestSubOrderList([
            'deliveryMode' => DeliveryMode::SPLIT_DELIVERY,
            'orderKey' => $orderKey2,
        ]);

        $this->persistEntities([$unifiedEntity, $splitEntity]);
        self::getEntityManager()->flush();

        // 清理缓存
        self::getEntityManager()->clear();

        $reloadedUnified = $this->getRepository()->find($unifiedEntity->getId());
        $reloadedSplit = $this->getRepository()->find($splitEntity->getId());

        self::assertNotNull($reloadedUnified);
        self::assertNotNull($reloadedSplit);
        self::assertInstanceOf(SubOrderList::class, $reloadedUnified);
        self::assertInstanceOf(SubOrderList::class, $reloadedSplit);
        self::assertEquals(DeliveryMode::UNIFIED_DELIVERY, $reloadedUnified->getDeliveryMode());
        self::assertEquals(DeliveryMode::SPLIT_DELIVERY, $reloadedSplit->getDeliveryMode());
        self::assertEquals('unified_delivery', $reloadedUnified->getDeliveryMode()->value);
        self::assertEquals('split_delivery', $reloadedSplit->getDeliveryMode()->value);
    }

    // Standard Repository Basic Method Tests (PHPStan Required)

    // FindBy Method Tests

    // FindOneBy Method Tests

    // FindAll Method Tests

    // Robustness Tests (Database Unavailable)

    // IS NULL Query Tests (for nullable fields - Note: combinedShippingInfo is nullable=false)

    public function testFindByCombinedShippingInfoIsNull(): void
    {
        // Note: combinedShippingInfo is nullable=false in entity, so this test will likely return empty results
        $result = $this->getRepository()->findBy(['combinedShippingInfo' => null]);

        self::assertIsArray($result);
        // Should be empty since combinedShippingInfo is required
        self::assertEmpty($result);
    }

    public function testCountCombinedShippingInfoIsNull(): void
    {
        $count = $this->getRepository()->count(['combinedShippingInfo' => null]);

        self::assertIsInt($count);
        // Should be 0 since combinedShippingInfo is required
        self::assertEquals(0, $count);
    }

    public function testFindByOrderKeyIsNull(): void
    {
        // Note: orderKey is nullable=false in entity, so this test will likely return empty results
        $result = $this->getRepository()->findBy(['orderKey' => null]);

        self::assertIsArray($result);
        // Should be empty since orderKey is required
        self::assertEmpty($result);
    }

    public function testCountOrderKeyIsNull(): void
    {
        $count = $this->getRepository()->count(['orderKey' => null]);

        self::assertIsInt($count);
        // Should be 0 since orderKey is required
        self::assertEquals(0, $count);
    }

    // Association Query Tests

    public function testFindByCombinedShippingInfoAssociation(): void
    {
        $entity = $this->createTestSubOrderList();
        $this->persistAndFlush($entity);

        $result = $this->getRepository()->findBy(['combinedShippingInfo' => $this->testCombinedShippingInfo]);

        self::assertIsArray($result);
        self::assertGreaterThanOrEqual(1, count($result));
        foreach ($result as $item) {
            self::assertInstanceOf(SubOrderList::class, $item);
            $combinedShippingInfo = $item->getCombinedShippingInfo();
            self::assertNotNull($combinedShippingInfo);
            self::assertEquals($this->testCombinedShippingInfo->getId(), $combinedShippingInfo->getId());
        }
    }

    public function testCountByCombinedShippingInfoAssociation(): void
    {
        $entity = $this->createTestSubOrderList();
        $this->persistAndFlush($entity);

        $count = $this->getRepository()->count(['combinedShippingInfo' => $this->testCombinedShippingInfo]);

        self::assertIsInt($count);
        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByOrderKeyAssociation(): void
    {
        $entity = $this->createTestSubOrderList();
        $this->persistAndFlush($entity);

        $result = $this->getRepository()->findBy(['orderKey' => $entity->getOrderKey()]);

        self::assertIsArray($result);
        self::assertGreaterThanOrEqual(1, count($result));
        foreach ($result as $item) {
            self::assertInstanceOf(SubOrderList::class, $item);
            $entityOrderKey = $entity->getOrderKey();
            $itemOrderKey = $item->getOrderKey();
            self::assertNotNull($entityOrderKey);
            self::assertNotNull($itemOrderKey);
            self::assertEquals($entityOrderKey->getId(), $itemOrderKey->getId());
        }
    }

    public function testCountByOrderKeyAssociation(): void
    {
        $entity = $this->createTestSubOrderList();
        $this->persistAndFlush($entity);

        $count = $this->getRepository()->count(['orderKey' => $entity->getOrderKey()]);

        self::assertIsInt($count);
        self::assertGreaterThanOrEqual(1, $count);
    }

    // Additional PHPStan Required Tests

    // IS NULL query tests with specific naming pattern (Note: SubOrderList has no nullable fields in the schema shown)

    // Association tests with specific naming pattern

    public function testFindOneByAssociationCombinedShippingInfoShouldReturnMatchingEntity(): void
    {
        $entity = $this->createTestSubOrderList();
        $this->persistAndFlush($entity);

        $found = $this->getRepository()->findOneBy(['combinedShippingInfo' => $this->testCombinedShippingInfo]);

        self::assertInstanceOf(SubOrderList::class, $found);
        $combinedShippingInfo = $found->getCombinedShippingInfo();
        self::assertNotNull($combinedShippingInfo);
        self::assertEquals($this->testCombinedShippingInfo->getId(), $combinedShippingInfo->getId());
    }

    public function testCountByAssociationCombinedShippingInfoShouldReturnCorrectNumber(): void
    {
        $entity = $this->createTestSubOrderList();
        $this->persistAndFlush($entity);

        $count = $this->getRepository()->count(['combinedShippingInfo' => $this->testCombinedShippingInfo]);

        self::assertIsInt($count);
        self::assertGreaterThanOrEqual(1, $count);
    }

    protected function createNewEntity(): object
    {
        // 创建不依赖持久化的独立SubOrderList
        // 注意: AbstractRepositoryTestCase要求createNewEntity不要持久化实体
        $account = new Account();
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');
        $account->setName('Test Account');

        $user = new User();
        $user->setOpenId('test_user_id_' . uniqid());
        $user->setUnionId('test_union_id_' . uniqid());
        $user->setAvatarUrl('https://example.com/avatar.jpg');

        $combinedOrderKey = new OrderKey();
        $combinedOrderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $combinedOrderKey->setOrderId('TEST_COMBINED_ORDER_' . uniqid());
        $combinedOrderKey->setOutOrderId('OUT_TEST_COMBINED_ORDER_' . uniqid());
        $combinedOrderKey->setTransactionId('test_combined_transaction_' . uniqid());
        $combinedOrderKey->setMchId('test_combined_mch_' . uniqid());
        $combinedOrderKey->setOutTradeNo('test_combined_out_trade_' . uniqid());

        $combinedShippingInfo = new CombinedShippingInfo();
        $combinedShippingInfo->setAccount($account);
        $combinedShippingInfo->setOrderKey($combinedOrderKey);
        $combinedShippingInfo->setPayer($user);
        $combinedShippingInfo->setUploadTime(new \DateTimeImmutable());
        $combinedShippingInfo->setValid(true);

        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setOrderId('TEST_ORDER_' . uniqid());
        $orderKey->setOutOrderId('OUT_TEST_ORDER_' . uniqid());
        $orderKey->setTransactionId('test_transaction_' . uniqid());
        $orderKey->setMchId('test_mch_' . uniqid());
        $orderKey->setOutTradeNo('test_out_trade_' . uniqid());

        $entity = new SubOrderList();
        $entity->setCombinedShippingInfo($combinedShippingInfo);
        $entity->setOrderKey($orderKey);
        $entity->setDeliveryMode(DeliveryMode::UNIFIED_DELIVERY);

        return $entity;
    }

    
    protected function getRepository(): SubOrderListRepository
    {
        return self::getService(SubOrderListRepository::class);
    }
}
