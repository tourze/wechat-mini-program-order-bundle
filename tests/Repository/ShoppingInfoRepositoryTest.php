<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramAuthBundle\Entity\User;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Repository\ShoppingInfoRepository;

/**
 * ShoppingInfoRepository 单元测试
 *
 * @internal
 */
#[CoversClass(ShoppingInfoRepository::class)]
#[RunTestsInSeparateProcesses]
final class ShoppingInfoRepositoryTest extends AbstractRepositoryTestCase
{
    private ShoppingInfoRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(ShoppingInfoRepository::class);
    }

    protected function onSetUpBeforeClass(): void
    {
        // 不需要特别的设置
    }

    /**
     * 测试根据订单ID查找购物信息
     */
    public function testFindByOrderIdReturnsCorrectShoppingInfo(): void
    {
        $shoppingInfo1 = $this->createTestShoppingInfo(orderId: 'ORDER001', outOrderId: 'OUT001');
        $this->createTestShoppingInfo(orderId: 'ORDER002', outOrderId: 'OUT002');

        $result = $this->repository->findByOrderId('ORDER001');

        $this->assertNotNull($result);
        $this->assertEquals('ORDER001', $result->getOrderKey()->getOrderId());
        $this->assertEquals('OUT001', $result->getOrderKey()->getOutOrderId());
        $this->assertEquals($shoppingInfo1->getId(), $result->getId());
    }

    /**
     * 创建测试购物信息
     */
    private function createTestShoppingInfo(
        string $orderId = 'ORDER123',
        string $outOrderId = 'OUT_ORDER123',
    ): ShoppingInfo {
        // 创建 OrderKey
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_transaction_' . uniqid());
        $orderKey->setMchId('test_mch_' . uniqid());
        $orderKey->setOutTradeNo('test_out_trade_' . uniqid());
        $orderKey->setOrderId($orderId);
        $orderKey->setOutOrderId($outOrderId);

        // 创建必需的 Account
        $account = new Account();
        $account->setAppId('test_app_' . uniqid());
        $account->setAppSecret('test_secret');
        $account->setName('Test Account');

        // 创建必需的 User
        $user = new User();
        $user->setOpenId('test_user_' . uniqid());
        $user->setAccount($account);

        $shoppingInfo = new ShoppingInfo();
        $shoppingInfo->setOrderKey($orderKey);
        $shoppingInfo->setAccount($account);
        $shoppingInfo->setPayer($user);
        $shoppingInfo->setOrderDetailPath('/test/order/detail/path');

        self::getEntityManager()->persist($orderKey);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->persist($user);
        self::getEntityManager()->persist($shoppingInfo);
        self::getEntityManager()->flush();

        return $shoppingInfo;
    }

    /**
     * 测试根据不存在的订单ID查找
     */
    public function testFindByOrderIdWithNonExistentIdReturnsNull(): void
    {
        $this->createTestShoppingInfo(orderId: 'ORDER001');

        $result = $this->repository->findByOrderId('NONEXISTENT_ORDER');

        $this->assertNull($result);
    }

    /**
     * 测试根据商户订单ID查找购物信息
     */
    public function testFindByOutOrderIdReturnsCorrectShoppingInfo(): void
    {
        $shoppingInfo1 = $this->createTestShoppingInfo(orderId: 'ORDER001', outOrderId: 'OUT001');
        $this->createTestShoppingInfo(orderId: 'ORDER002', outOrderId: 'OUT002');

        $result = $this->repository->findByOutOrderId('OUT001');

        $this->assertNotNull($result);
        $this->assertEquals('ORDER001', $result->getOrderKey()->getOrderId());
        $this->assertEquals('OUT001', $result->getOrderKey()->getOutOrderId());
        $this->assertEquals($shoppingInfo1->getId(), $result->getId());
    }

    /**
     * 测试根据不存在的商户订单ID查找
     */
    public function testFindByOutOrderIdWithNonExistentIdReturnsNull(): void
    {
        $this->createTestShoppingInfo(outOrderId: 'OUT001');

        $result = $this->repository->findByOutOrderId('NONEXISTENT_OUT_ORDER');

        $this->assertNull($result);
    }

    /**
     * 测试保存购物信息
     */
    public function testSavePersistsShoppingInfoCorrectly(): void
    {
        $shoppingInfo = $this->createTestShoppingInfo(orderId: 'TEST_SAVE_ORDER', outOrderId: 'TEST_SAVE_OUT_ORDER');

        $savedShoppingInfo = $this->repository->findByOrderId('TEST_SAVE_ORDER');
        $this->assertNotNull($savedShoppingInfo);
        $this->assertEquals('TEST_SAVE_ORDER', $savedShoppingInfo->getOrderKey()->getOrderId());
        $this->assertEquals('TEST_SAVE_OUT_ORDER', $savedShoppingInfo->getOrderKey()->getOutOrderId());
    }

    /**
     * 测试删除购物信息
     */
    public function testRemoveDeletesShoppingInfoCorrectly(): void
    {
        $shoppingInfo = $this->createTestShoppingInfo(orderId: 'ORDER_TO_DELETE');

        $this->repository->remove($shoppingInfo, flush: true);

        $deletedShoppingInfo = $this->repository->findByOrderId('ORDER_TO_DELETE');
        $this->assertNull($deletedShoppingInfo);
    }

    /**
     * 测试保存但不刷新
     */
    public function testSaveWithoutFlushDoesNotPersistImmediately(): void
    {
        // 创建完整的关联结构并先持久化依赖实体
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_transaction_no_flush');
        $orderKey->setMchId('test_mch_no_flush');
        $orderKey->setOutTradeNo('test_out_trade_no_flush');
        $orderKey->setOrderId('TEST_NO_FLUSH');
        $orderKey->setOutOrderId('TEST_NO_FLUSH_OUT');

        // 创建必需的 Account
        $account = new Account();
        $account->setAppId('test_app_no_flush');
        $account->setAppSecret('test_secret');
        $account->setName('Test Account');

        // 创建必需的 User
        $user = new User();
        $user->setOpenId('test_user_no_flush');
        $user->setAccount($account);

        // 先持久化并刷新依赖实体
        self::getEntityManager()->persist($orderKey);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->persist($user);
        self::getEntityManager()->flush();

        $shoppingInfo = new ShoppingInfo();
        $shoppingInfo->setOrderKey($orderKey);
        $shoppingInfo->setAccount($account);
        $shoppingInfo->setPayer($user);
        $shoppingInfo->setOrderDetailPath('/test/order/detail/path');

        $this->repository->save($shoppingInfo, flush: false);

        // 实体应该在实体管理器中被管理
        $this->assertTrue(self::getEntityManager()->contains($shoppingInfo));

        // 在flush前，从数据库查询应该找不到
        self::getEntityManager()->clear();
        $savedShoppingInfo = $this->repository->findByOrderId('TEST_NO_FLUSH');
        $this->assertNull($savedShoppingInfo);

        // clear()后实体变detached，需要重新创建关联实体
        $orderKey2 = new OrderKey();
        $orderKey2->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey2->setTransactionId('test_transaction_no_flush_2');
        $orderKey2->setMchId('test_mch_no_flush_2');
        $orderKey2->setOutTradeNo('test_out_trade_no_flush_2');
        $orderKey2->setOrderId('TEST_NO_FLUSH');
        $orderKey2->setOutOrderId('TEST_NO_FLUSH_OUT_2');

        $account2 = new Account();
        $account2->setAppId('test_app_no_flush_2');
        $account2->setAppSecret('test_secret_2');
        $account2->setName('Test Account 2');

        $user2 = new User();
        $user2->setOpenId('test_user_no_flush_2');
        $user2->setAccount($account2);

        self::getEntityManager()->persist($orderKey2);
        self::getEntityManager()->persist($account2);
        self::getEntityManager()->persist($user2);

        $shoppingInfo2 = new ShoppingInfo();
        $shoppingInfo2->setOrderKey($orderKey2);
        $shoppingInfo2->setAccount($account2);
        $shoppingInfo2->setPayer($user2);
        $shoppingInfo2->setOrderDetailPath('/test/order/detail/path');

        $this->repository->save($shoppingInfo2, flush: true);

        // 现在应该能找到
        $savedShoppingInfo = $this->repository->findByOrderId('TEST_NO_FLUSH');
        $this->assertNotNull($savedShoppingInfo);
    }

    /**
     * 测试特殊字符的订单ID
     */
    public function testFindByOrderIdWithSpecialCharactersReturnsCorrectResult(): void
    {
        $specialOrderId = 'ORDER-001_TEST@2024';
        $shoppingInfo = $this->createTestShoppingInfo(orderId: $specialOrderId, outOrderId: 'OUT001');

        $result = $this->repository->findByOrderId($specialOrderId);

        $this->assertNotNull($result);
        $this->assertEquals($specialOrderId, $result->getOrderKey()->getOrderId());
        $this->assertEquals($shoppingInfo->getId(), $result->getId());
    }

    /**
     * 测试特殊字符的商户订单ID
     */
    public function testFindByOutOrderIdWithSpecialCharactersReturnsCorrectResult(): void
    {
        $specialOutOrderId = 'OUT-001_TEST@2024';
        $shoppingInfo = $this->createTestShoppingInfo(orderId: 'ORDER001', outOrderId: $specialOutOrderId);

        $result = $this->repository->findByOutOrderId($specialOutOrderId);

        $this->assertNotNull($result);
        $this->assertEquals($specialOutOrderId, $result->getOrderKey()->getOutOrderId());
        $this->assertEquals($shoppingInfo->getId(), $result->getId());
    }

    /**
     * 测试订单ID和商户订单ID的唯一性验证
     */
    public function testOrderIdAndOutOrderIdUniquenessHandling(): void
    {
        // 创建第一个购物信息
        $shoppingInfo1 = $this->createTestShoppingInfo(orderId: 'UNIQUE_ORDER', outOrderId: 'UNIQUE_OUT');

        // 验证能正确找到
        $found1 = $this->repository->findByOrderId('UNIQUE_ORDER');
        $this->assertNotNull($found1);
        $this->assertEquals($shoppingInfo1->getId(), $found1->getId());

        $found2 = $this->repository->findByOutOrderId('UNIQUE_OUT');
        $this->assertNotNull($found2);
        $this->assertEquals($shoppingInfo1->getId(), $found2->getId());
    }

    /**
     * 测试空值处理
     */
    public function testFindByOrderIdWithEmptyStringReturnsNull(): void
    {
        $this->createTestShoppingInfo(orderId: 'ORDER001');

        $result = $this->repository->findByOrderId('');

        $this->assertNull($result);
    }

    /**
     * 测试删除操作的完整性
     */
    public function testRemoveWithFlushFalseDoesNotDeleteImmediately(): void
    {
        $shoppingInfo = $this->createTestShoppingInfo(orderId: 'DELETE_TEST');

        $this->repository->remove($shoppingInfo, flush: false);

        // 在flush前，应该还能查到（因为还在EntityManager中）
        $result = $this->repository->findByOrderId('DELETE_TEST');
        $this->assertNotNull($result);

        // 手动flush后应该删除
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
        $result = $this->repository->findByOrderId('DELETE_TEST');
        $this->assertNull($result);
    }

    /**
     * 测试大小写敏感性
     */
    public function testFindByOrderIdWithCaseSensitivityReturnsCorrectResult(): void
    {
        $this->createTestShoppingInfo(orderId: 'OrderTest');

        // 测试完全匹配
        $result1 = $this->repository->findByOrderId('OrderTest');
        $this->assertNotNull($result1);

        // 测试大小写不匹配
        $result2 = $this->repository->findByOrderId('ordertest');
        $this->assertNull($result2);

        $result3 = $this->repository->findByOrderId('ORDERTEST');
        $this->assertNull($result3);
    }

    /**
     * 测试长ID处理
     */
    public function testFindByOrderIdWithLongIdReturnsCorrectResult(): void
    {
        $longOrderId = str_repeat('A', 100) . '_LONG_ORDER_ID';
        $shoppingInfo = $this->createTestShoppingInfo(orderId: $longOrderId);

        $result = $this->repository->findByOrderId($longOrderId);

        $this->assertNotNull($result);
        $this->assertEquals($longOrderId, $result->getOrderKey()->getOrderId());
        $this->assertEquals($shoppingInfo->getId(), $result->getId());
    }

    /**
     * 测试Unicode字符处理
     */
    public function testFindByOrderIdWithUnicodeCharactersReturnsCorrectResult(): void
    {
        $unicodeOrderId = 'ORDER_测试_🛒_001';
        $shoppingInfo = $this->createTestShoppingInfo(orderId: $unicodeOrderId);

        $result = $this->repository->findByOrderId($unicodeOrderId);

        $this->assertNotNull($result);
        $this->assertEquals($unicodeOrderId, $result->getOrderKey()->getOrderId());
        $this->assertEquals($shoppingInfo->getId(), $result->getId());
    }

    /**
     * 测试批量数据查询
     */
    public function testFindMethodsWithMultipleRecordsReturnCorrectResults(): void
    {
        // 创建多个购物信息记录
        for ($i = 1; $i <= 5; ++$i) {
            $this->createTestShoppingInfo(
                orderId: "BATCH_ORDER_{$i}",
                outOrderId: "BATCH_OUT_{$i}"
            );
        }

        // 测试能正确找到每一个
        for ($i = 1; $i <= 5; ++$i) {
            $resultByOrder = $this->repository->findByOrderId("BATCH_ORDER_{$i}");
            $this->assertNotNull($resultByOrder);
            $this->assertEquals("BATCH_ORDER_{$i}", $resultByOrder->getOrderKey()->getOrderId());

            $resultByOutOrder = $this->repository->findByOutOrderId("BATCH_OUT_{$i}");
            $this->assertNotNull($resultByOutOrder);
            $this->assertEquals("BATCH_OUT_{$i}", $resultByOutOrder->getOrderKey()->getOutOrderId());

            // 验证是同一个实体
            $this->assertEquals($resultByOrder->getId(), $resultByOutOrder->getId());
        }
    }

    /**
     * 测试带关联数据的订单ID查询（防止N+1问题）
     */
    public function testFindByOrderIdWithRelationsPreloadsAssociations(): void
    {
        $shoppingInfo = $this->createTestShoppingInfo(orderId: 'ORDER_WITH_RELATIONS', outOrderId: 'OUT_WITH_RELATIONS');

        // 清空实体管理器缓存，确保从数据库重新加载
        self::getEntityManager()->clear();

        $result = $this->repository->findByOrderIdWithRelations('ORDER_WITH_RELATIONS');

        $this->assertNotNull($result);
        $this->assertEquals('ORDER_WITH_RELATIONS', $result->getOrderKey()->getOrderId());

        // 验证关联数据已被预加载（实体管理器已清空，但可以访问关联数据）
        $this->assertNotNull($result->getOrderKey());
        $this->assertNotNull($result->getAccount());
        $this->assertNotNull($result->getPayer());

        // 验证itemList集合已被初始化
        $this->assertNotNull($result->getItemList());
    }

    /**
     * 测试带关联数据的商户订单ID查询（防止N+1问题）
     */
    public function testFindByOutOrderIdWithRelationsPreloadsAssociations(): void
    {
        $shoppingInfo = $this->createTestShoppingInfo(orderId: 'ORDER_OUT_WITH_RELATIONS', outOrderId: 'OUT_ORDER_WITH_RELATIONS');

        // 清空实体管理器缓存
        self::getEntityManager()->clear();

        $result = $this->repository->findByOutOrderIdWithRelations('OUT_ORDER_WITH_RELATIONS');

        $this->assertNotNull($result);
        $this->assertEquals('OUT_ORDER_WITH_RELATIONS', $result->getOrderKey()->getOutOrderId());

        // 验证关联数据已被预加载
        $this->assertNotNull($result->getOrderKey());
        $this->assertNotNull($result->getAccount());
        $this->assertNotNull($result->getPayer());
        $this->assertNotNull($result->getItemList());
    }

    /**
     * 测试批量查询订单ID（防止N+1问题）
     */
    public function testFindByOrderIdsWithRelationsReturnsBatchResults(): void
    {
        // 创建多个订单
        $orderIds = [];
        for ($i = 1; $i <= 3; ++$i) {
            $orderId = "BATCH_ORDER_{$i}";
            $orderIds[] = $orderId;
            $this->createTestShoppingInfo(orderId: $orderId, outOrderId: "BATCH_OUT_{$i}");
        }

        // 清空实体管理器缓存
        self::getEntityManager()->clear();

        $results = $this->repository->findByOrderIdsWithRelations($orderIds);

        $this->assertCount(3, $results);

        // 验证每个结果都有预加载的关联数据
        foreach ($results as $result) {
            $this->assertNotNull($result->getOrderKey());
            $this->assertNotNull($result->getAccount());
            $this->assertNotNull($result->getPayer());
            $this->assertNotNull($result->getItemList());
            $this->assertContains($result->getOrderKey()->getOrderId(), $orderIds);
        }
    }

    /**
     * 测试空数组批量查询
     */
    public function testFindByOrderIdsWithRelationsWithEmptyArrayReturnsEmptyArray(): void
    {
        $results = $this->repository->findByOrderIdsWithRelations([]);
        $this->assertEmpty($results);
    }

    /**
     * 测试按支付者查询（分页）
     */
    public function testFindByPayerWithRelationsReturnsPaginatedResults(): void
    {
        $payerOpenId = 'test_payer_pagination';

        // 创建用户和账户
        $account = new Account();
        $account->setAppId('test_app_pager');
        $account->setAppSecret('test_secret');
        $account->setName('Test Account');

        $user = new User();
        $user->setOpenId($payerOpenId);
        $user->setAccount($account);

        self::getEntityManager()->persist($account);
        self::getEntityManager()->persist($user);
        self::getEntityManager()->flush();

        // 为同一用户创建多个订单
        for ($i = 1; $i <= 5; ++$i) {
            $orderKey = new OrderKey();
            $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
            $orderKey->setTransactionId("test_transaction_pager_{$i}");
            $orderKey->setMchId("test_mch_pager_{$i}");
            $orderKey->setOutTradeNo("test_out_trade_pager_{$i}");
            $orderKey->setOrderId("PAGER_ORDER_{$i}");
            $orderKey->setOutOrderId("PAGER_OUT_{$i}");

            $shoppingInfo = new ShoppingInfo();
            $shoppingInfo->setOrderKey($orderKey);
            $shoppingInfo->setAccount($account);
            $shoppingInfo->setPayer($user);
            $shoppingInfo->setOrderDetailPath('/test/order/detail/path');

            self::getEntityManager()->persist($orderKey);
            self::getEntityManager()->persist($shoppingInfo);
        }
        self::getEntityManager()->flush();

        // 清空实体管理器缓存
        self::getEntityManager()->clear();

        // 测试分页查询
        $page1 = $this->repository->findByPayerWithRelations($payerOpenId, 3, 0);
        $page2 = $this->repository->findByPayerWithRelations($payerOpenId, 3, 3);

        $this->assertCount(3, $page1);
        $this->assertCount(2, $page2); // 剩余的2个

        // 验证关联数据已预加载
        foreach (array_merge($page1, $page2) as $result) {
            $this->assertNotNull($result->getOrderKey());
            $this->assertNotNull($result->getAccount());
            $this->assertNotNull($result->getPayer());
            $this->assertEquals($payerOpenId, $result->getPayer()->getOpenId());
        }
    }

    /**
     * 测试按账户查询（分页）
     */
    public function testFindByAccountWithRelationsReturnsPaginatedResults(): void
    {
        $appId = 'test_app_account_pagination';

        // 创建账户
        $account = new Account();
        $account->setAppId($appId);
        $account->setAppSecret('test_secret');
        $account->setName('Test Account');

        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        // 为同一账户创建多个订单
        for ($i = 1; $i <= 4; ++$i) {
            $user = new User();
            $user->setOpenId("test_user_account_{$i}");
            $user->setAccount($account);

            $orderKey = new OrderKey();
            $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
            $orderKey->setTransactionId("test_transaction_account_{$i}");
            $orderKey->setMchId("test_mch_account_{$i}");
            $orderKey->setOutTradeNo("test_out_trade_account_{$i}");
            $orderKey->setOrderId("ACCOUNT_ORDER_{$i}");
            $orderKey->setOutOrderId("ACCOUNT_OUT_{$i}");

            $shoppingInfo = new ShoppingInfo();
            $shoppingInfo->setOrderKey($orderKey);
            $shoppingInfo->setAccount($account);
            $shoppingInfo->setPayer($user);
            $shoppingInfo->setOrderDetailPath('/test/order/detail/path');

            self::getEntityManager()->persist($user);
            self::getEntityManager()->persist($orderKey);
            self::getEntityManager()->persist($shoppingInfo);
        }
        self::getEntityManager()->flush();

        // 清空实体管理器缓存
        self::getEntityManager()->clear();

        // 测试分页查询
        $page1 = $this->repository->findByAccountWithRelations($appId, 2, 0);
        $page2 = $this->repository->findByAccountWithRelations($appId, 2, 2);

        $this->assertCount(2, $page1);
        $this->assertCount(2, $page2);

        // 验证关联数据已预加载
        foreach (array_merge($page1, $page2) as $result) {
            $this->assertNotNull($result->getOrderKey());
            $this->assertNotNull($result->getAccount());
            $this->assertNotNull($result->getPayer());
            $this->assertEquals($appId, $result->getAccount()->getAppId());
        }
    }

    /**
     * 测试批量保存功能
     */
    public function testSaveBatchPersistsMultipleEntities(): void
    {
        $entities = [];

        // 创建多个实体
        for ($i = 1; $i <= 3; ++$i) {
            // 创建依赖实体
            $orderKey = new OrderKey();
            $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
            $orderKey->setTransactionId("test_transaction_batch_{$i}");
            $orderKey->setMchId("test_mch_batch_{$i}");
            $orderKey->setOutTradeNo("test_out_trade_batch_{$i}");
            $orderKey->setOrderId("BATCH_SAVE_ORDER_{$i}");
            $orderKey->setOutOrderId("BATCH_SAVE_OUT_{$i}");

            $account = new Account();
            $account->setAppId("test_app_batch_{$i}");
            $account->setAppSecret('test_secret');
            $account->setName('Test Account');

            $user = new User();
            $user->setOpenId("test_user_batch_{$i}");
            $user->setAccount($account);

            $shoppingInfo = new ShoppingInfo();
            $shoppingInfo->setOrderKey($orderKey);
            $shoppingInfo->setAccount($account);
            $shoppingInfo->setPayer($user);
            $shoppingInfo->setOrderDetailPath('/test/order/detail/path');

            // 先持久化依赖实体
            self::getEntityManager()->persist($orderKey);
            self::getEntityManager()->persist($account);
            self::getEntityManager()->persist($user);

            $entities[] = $shoppingInfo;
        }

        // 批量保存
        $this->repository->saveBatch($entities, flush: true);

        // 验证保存成功
        for ($i = 1; $i <= 3; ++$i) {
            $result = $this->repository->findByOrderId("BATCH_SAVE_ORDER_{$i}");
            $this->assertNotNull($result);
            $this->assertEquals("BATCH_SAVE_ORDER_{$i}", $result->getOrderKey()->getOrderId());
        }
    }

    /**
     * 测试不同查询方法的性能对比（确保预加载版本没有损害基本功能）
     */
    public function testQueryMethodsPerformanceComparison(): void
    {
        $shoppingInfo = $this->createTestShoppingInfo(orderId: 'PERFORMANCE_TEST', outOrderId: 'PERFORMANCE_OUT_TEST');

        // 清空实体管理器缓存
        self::getEntityManager()->clear();

        // 测试基本查询方法
        $basicResult = $this->repository->findByOrderId('PERFORMANCE_TEST');
        $this->assertNotNull($basicResult);

        // 清空实体管理器缓存
        self::getEntityManager()->clear();

        // 测试预加载查询方法
        $preloadedResult = $this->repository->findByOrderIdWithRelations('PERFORMANCE_TEST');
        $this->assertNotNull($preloadedResult);

        // 验证两个结果的一致性
        $this->assertEquals($basicResult->getId(), $preloadedResult->getId());
        $this->assertEquals($basicResult->getOrderKey()->getOrderId(), $preloadedResult->getOrderKey()->getOrderId());

        // 验证预加载版本包含所有必要的关联数据
        $this->assertNotNull($preloadedResult->getAccount());
        $this->assertNotNull($preloadedResult->getPayer());
        $this->assertNotNull($preloadedResult->getItemList());
    }

    // Standard Doctrine Repository tests

    protected function onTearDown(): void
    {
        // EntityManager is managed by AbstractRepositoryTestCase
    }

    protected function createNewEntity(): object
    {
        // 创建不依赖持久化的独立ShoppingInfo
        // 注意: AbstractRepositoryTestCase要求createNewEntity不要持久化实体
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_transaction_' . uniqid());
        $orderKey->setMchId('test_mch_' . uniqid());
        $orderKey->setOutTradeNo('test_out_trade_' . uniqid());
        $orderKey->setOrderId('test_order_' . uniqid());
        $orderKey->setOutOrderId('test_out_order_' . uniqid());

        // 创建必需的 Account (使用 WechatMiniProgramBundle\Entity\Account)
        $account = new Account();
        $account->setAppId('test_app_' . uniqid());
        $account->setAppSecret('test_secret');
        $account->setName('Test Account');

        // 创建必需的 User (使用 WechatMiniProgramAuthBundle\Entity\User)
        $user = new User();
        $user->setOpenId('test_user_' . uniqid());
        $user->setAccount($account);

        // 创建 ShoppingInfo 实体并设置所有必填字段
        $entity = new ShoppingInfo();
        $entity->setOrderKey($orderKey);
        $entity->setAccount($account);
        $entity->setPayer($user);
        $entity->setOrderDetailPath('/test/order/detail/path');

        return $entity;
    }

    protected function getRepository(): ShoppingInfoRepository
    {
        return $this->repository;
    }
}
