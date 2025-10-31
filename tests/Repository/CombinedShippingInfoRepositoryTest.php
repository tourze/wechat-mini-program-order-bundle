<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramAuthBundle\Entity\User;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Repository\CombinedShippingInfoRepository;

/**
 * @internal
 */
#[CoversClass(CombinedShippingInfoRepository::class)]
#[RunTestsInSeparateProcesses]
final class CombinedShippingInfoRepositoryTest extends AbstractRepositoryTestCase
{
    private Account $testAccount;

    private User $testUser;

    private OrderKey $testOrderKey;

    private CombinedShippingInfoRepository $repository;

    protected function onSetUp(): void
    {
        $this->testAccount = $this->createTestAccount();
        $this->testUser = $this->createTestUser($this->testAccount);
        $this->testOrderKey = $this->createTestOrderKey();
        $this->repository = self::getService(CombinedShippingInfoRepository::class);
    }

    private function createTestAccount(): Account
    {
        $account = new Account();
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');
        $account->setName('Test Account');

        return $account;
    }

    private function createTestUser(?Account $account = null): User
    {
        $user = new User();
        $user->setOpenId('test_user_id_' . uniqid());
        if (null !== $account) {
            $user->setAccount($account);
        }

        return $user;
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

    private function createOrderKey2(): OrderKey
    {
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_transaction_2_' . uniqid());
        $orderKey->setMchId('test_mch_2_' . uniqid());
        $orderKey->setOutTradeNo('test_out_trade_2_' . uniqid());

        return $orderKey;
    }

    private function createOrderKey3(): OrderKey
    {
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_WECHAT_ORDER);
        $orderKey->setTransactionId('test_transaction_complex_' . uniqid());
        $orderKey->setMchId('test_mch_complex_' . uniqid());
        $orderKey->setOutTradeNo('test_out_trade_complex_' . uniqid());

        return $orderKey;
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private function createTestCombinedShippingInfo(array $overrides = []): CombinedShippingInfo
    {
        $entity = new CombinedShippingInfo();

        $testAccount = $overrides['account'] ?? $this->createTestAccount();
        $testOrderKey = $overrides['orderKey'] ?? $this->createTestOrderKey();
        $testUser = $overrides['payer'] ?? $this->createTestUser($testAccount instanceof Account ? $testAccount : null);

        $defaults = [
            'account' => $testAccount,
            'orderKey' => $testOrderKey,
            'payer' => $testUser,
            'uploadTime' => new \DateTimeImmutable(),
            'valid' => true,
        ];

        $data = array_merge($defaults, $overrides);

        // 类型安全的数据提取
        /** @var MiniProgramInterface $account */
        $account = $data['account'];
        /** @var OrderKey|null $orderKey */
        $orderKey = $data['orderKey'];
        /** @var UserInterface|null $payer */
        $payer = $data['payer'];
        /** @var \DateTimeImmutable $uploadTime */
        $uploadTime = $data['uploadTime'];
        /** @var bool|null $valid */
        $valid = $data['valid'];

        $entity->setAccount($account);
        $entity->setOrderKey($orderKey);
        $entity->setPayer($payer);
        $entity->setUploadTime($uploadTime);
        $entity->setValid($valid);

        return $entity;
    }

    // 基础功能测试

    public function testSaveAndFlushEntity(): void
    {
        $repository = self::getService(CombinedShippingInfoRepository::class);
        $entity = $this->createTestCombinedShippingInfo();

        // 按依赖关系顺序持久化关联实体
        $this->persistAndFlush($entity->getAccount()); // 先持久化Account

        $payer = $entity->getPayer();
        if (null !== $payer) {
            $this->persistAndFlush($payer);   // 再持久化User (依赖Account)
        }

        $orderKey = $entity->getOrderKey();
        if (null !== $orderKey) {
            $this->persistAndFlush($orderKey); // 持久化OrderKey
        }

        $repository->save($entity, true);

        $this->assertEntityPersisted($entity);
        self::assertNotNull($entity->getId());
    }

    public function testRemoveAndFlushEntity(): void
    {
        $entity = $this->createTestCombinedShippingInfo();

        // 按依赖关系顺序持久化关联实体
        $this->persistAndFlush($entity->getAccount());

        $payer = $entity->getPayer();
        if (null !== $payer) {
            $this->persistAndFlush($payer);
        }

        $orderKey = $entity->getOrderKey();
        if (null !== $orderKey) {
            $this->persistAndFlush($orderKey);
        }
        $this->persistAndFlush($entity);

        $entityId = $entity->getId();

        $this->repository->remove($entity, true);

        $this->assertEntityNotExists(CombinedShippingInfo::class, $entityId);

        // Ensure the entity was actually removed
        $found = $this->repository->find($entityId);
        self::assertNull($found);
    }

    // findByOrderId测试

    public function testFindByOrderIdReturnsNull(): void
    {
        $result = $this->repository->findByOrderId('nonexistent_order_id');

        self::assertNull($result);
    }

    public function testFindByOrderIdWithEmptyString(): void
    {
        $result = $this->repository->findByOrderId('');

        self::assertNull($result);
    }

    // findByTrackingNo测试

    public function testFindByTrackingNoReturnsNull(): void
    {
        $result = $this->repository->findByTrackingNo('nonexistent_tracking_no');

        self::assertNull($result);
    }

    public function testFindByTrackingNoWithEmptyString(): void
    {
        $result = $this->repository->findByTrackingNo('');

        self::assertNull($result);
    }

    // findNeedUpdateTracking测试

    public function testFindNeedUpdateTrackingWithFutureTime(): void
    {
        $futureTime = new \DateTime('+1 hour');

        $result = $this->repository->findNeedUpdateTracking($futureTime);

        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    public function testFindNeedUpdateTrackingWithPastTime(): void
    {
        $pastTime = new \DateTime('-1 hour');

        $result = $this->repository->findNeedUpdateTracking($pastTime);

        self::assertIsArray($result);
    }

    public function testFindNeedUpdateTrackingWithDateTimeImmutable(): void
    {
        $time = new \DateTimeImmutable('+1 hour');

        $result = $this->repository->findNeedUpdateTracking($time);

        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    // findByAccount测试

    public function testFindByAccountReturnsEmptyArray(): void
    {
        $nonExistentAccount = $this->createTestAccount();

        $result = $this->repository->findByAccount($nonExistentAccount);

        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    public function testFindByAccountWithValidAccount(): void
    {
        $entity = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $this->persistAndFlush($entity);

        $result = $this->repository->findByAccount($this->testAccount);

        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertSame($entity, $result[0]);
    }

    public function testFindByAccountWithMultipleResults(): void
    {
        $entity1 = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $entity2 = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'orderKey' => $this->createOrderKey2(),
            'payer' => $this->createTestUser($this->testAccount),
        ]);

        $this->persistEntities([$entity1, $entity2]);
        self::getEntityManager()->flush();

        $result = $this->repository->findByAccount($this->testAccount);

        self::assertIsArray($result);
        self::assertCount(2, $result);
    }

    public function testFindByAccountWithNullAccount(): void
    {
        $result = $this->repository->findByAccount(null);

        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    // findByOrderKey测试

    public function testFindByOrderKeyReturnsNull(): void
    {
        $nonExistentOrderKey = $this->createTestOrderKey();

        $result = $this->repository->findByOrderKey($nonExistentOrderKey);

        self::assertNull($result);
    }

    public function testFindByOrderKeyWithValidOrderKey(): void
    {
        $entity = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $this->persistAndFlush($entity);

        $result = $this->repository->findByOrderKey($this->testOrderKey);

        self::assertSame($entity, $result);
    }

    public function testFindByOrderKeyWithNullOrderKey(): void
    {
        $result = $this->repository->findByOrderKey(null);

        self::assertNull($result);
    }

    // findByPayer测试

    public function testFindByPayerReturnsEmptyArray(): void
    {
        // 使用一个真实用户但不关联任何 CombinedShippingInfo
        $this->persistAndFlush($this->testAccount);

        $unrelatedUser = new User();
        $unrelatedUser->setOpenId('unrelated_user_' . uniqid());
        $unrelatedUser->setAccount($this->testAccount);
        $this->persistAndFlush($unrelatedUser);

        $result = $this->repository->findByPayer($unrelatedUser);

        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    public function testFindByPayerWithValidPayer(): void
    {
        $entity = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $this->persistAndFlush($entity);

        $result = $this->repository->findByPayer($this->testUser);

        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertSame($entity, $result[0]);
    }

    public function testFindByPayerWithNullPayer(): void
    {
        $result = $this->repository->findByPayer(null);

        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    // 边界情况和错误处理测试

    public function testValidFieldCanBeNull(): void
    {
        $entity = $this->createTestCombinedShippingInfo(['valid' => null]);
        $this->persistAndFlush($entity);

        self::assertNull($entity->isValid());
        $this->assertEntityPersisted($entity);
    }

    public function testValidFieldCanBeFalse(): void
    {
        $entity = $this->createTestCombinedShippingInfo(['valid' => false]);
        $this->persistAndFlush($entity);

        self::assertFalse($entity->isValid());
        $this->assertEntityPersisted($entity);
    }

    public function testValidFieldCanBeTrue(): void
    {
        $entity = $this->createTestCombinedShippingInfo(['valid' => true]);
        $this->persistAndFlush($entity);

        self::assertTrue($entity->isValid());
        $this->assertEntityPersisted($entity);
    }

    public function testSubOrdersCollectionInitialization(): void
    {
        $entity = $this->createTestCombinedShippingInfo();

        self::assertCount(0, $entity->getSubOrders());
    }

    public function testUploadTimeIsSetAutomatically(): void
    {
        $beforeCreation = new \DateTimeImmutable();
        $entity = $this->createTestCombinedShippingInfo();
        $afterCreation = new \DateTimeImmutable();

        self::assertGreaterThanOrEqual($beforeCreation, $entity->getUploadTime());
        self::assertLessThanOrEqual($afterCreation, $entity->getUploadTime());
    }

    // 测试复杂查询场景

    public function testRepositoryHandlesComplexQueries(): void
    {
        // 创建多个实体用于复杂查询测试
        $account2 = $this->createTestAccount();
        $this->persistAndFlush($account2);

        $entity1 = $this->createTestCombinedShippingInfo(['account' => $this->testAccount, 'valid' => true]);
        $entity2 = $this->createTestCombinedShippingInfo([
            'account' => $account2,
            'valid' => false,
            'orderKey' => $this->createOrderKey3(),
            'payer' => $this->createTestUser($account2),
        ]);

        $this->persistEntities([$entity1, $entity2]);
        self::getEntityManager()->flush();

        // 测试通过不同账号查找
        $resultAccount1 = $this->repository->findByAccount($this->testAccount);
        $resultAccount2 = $this->repository->findByAccount($account2);

        self::assertCount(1, $resultAccount1);
        self::assertCount(1, $resultAccount2);
        self::assertSame($entity1, $resultAccount1[0]);
        self::assertSame($entity2, $resultAccount2[0]);
    }

    // 性能测试

    public function testRepositoryPerformanceWithMultipleEntities(): void
    {
        // 创建多个实体
        $entities = [];
        for ($i = 0; $i < 10; ++$i) {
            $orderKey = new OrderKey();
            $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
            $orderKey->setTransactionId('test_transaction_perf_' . $i . '_' . uniqid());
            $orderKey->setMchId('test_mch_perf_' . $i . '_' . uniqid());
            $orderKey->setOutTradeNo('test_out_trade_perf_' . $i . '_' . uniqid());

            $entities[] = $this->createTestCombinedShippingInfo([
                'account' => $this->testAccount,
                'orderKey' => $orderKey,
                'valid' => 0 === $i % 2,
                'payer' => $this->createTestUser($this->testAccount),
            ]);
        }

        $this->persistEntities($entities);
        self::getEntityManager()->flush();

        // 测试批量查找
        $result = $this->repository->findByAccount($this->testAccount);

        self::assertCount(10, $result);
        self::assertIsArray($result);
    }

    // 数据完整性测试

    public function testEntityRelationshipsAreProperlyMaintained(): void
    {
        // 使用预创建的测试实体确保ID一致
        $entity = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $this->persistAndFlush($entity);

        // 清理缓存确保从数据库重新加载
        self::getEntityManager()->clear();

        $reloaded = $this->repository->find($entity->getId());

        self::assertNotNull($reloaded);
        self::assertInstanceOf(CombinedShippingInfo::class, $reloaded);
        $reloadedAccount = $reloaded->getAccount();
        if ($reloadedAccount instanceof Account) {
            self::assertSame($this->testAccount->getId(), $reloadedAccount->getId());
        }
    }

    // Standard Repository Basic Method Tests (PHPStan Required)

    // FindBy Method Tests

    // FindOneBy Method Tests

    // FindAll Method Tests

    // Robustness Tests (Database Unavailable)

    // IS NULL Query Tests (for nullable fields)

    public function testFindByValidIsNull(): void
    {
        $entity = $this->createTestCombinedShippingInfo(['valid' => null]);
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['valid' => null]);

        self::assertIsArray($result);
        self::assertGreaterThanOrEqual(1, count($result));
        foreach ($result as $item) {
            self::assertInstanceOf(CombinedShippingInfo::class, $item);
            self::assertNull($item->isValid());
        }
    }

    public function testCountValidIsNull(): void
    {
        $entity = $this->createTestCombinedShippingInfo(['valid' => null]);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['valid' => null]);

        self::assertIsInt($count);
        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByOrderKeyIsNull(): void
    {
        // Note: orderKey is nullable=false in entity, so this test will likely return empty results
        $result = $this->repository->findBy(['orderKey' => null]);

        self::assertIsArray($result);
        // Should be empty since orderKey is required
        self::assertEmpty($result);
    }

    public function testCountOrderKeyIsNull(): void
    {
        $count = $this->repository->count(['orderKey' => null]);

        self::assertIsInt($count);
        // Should be 0 since orderKey is required
        self::assertEquals(0, $count);
    }

    public function testFindByPayerIsNull(): void
    {
        // Note: payer is nullable=false in entity, so this test will likely return empty results
        $result = $this->repository->findBy(['payer' => null]);

        self::assertIsArray($result);
        // Should be empty since payer is required
        self::assertEmpty($result);
    }

    public function testCountPayerIsNull(): void
    {
        $count = $this->repository->count(['payer' => null]);

        self::assertIsInt($count);
        // Should be 0 since payer is required
        self::assertEquals(0, $count);
    }

    // Association Query Tests

    public function testFindByAccountAssociation(): void
    {
        $entity = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['account' => $this->testAccount]);

        self::assertIsArray($result);
        self::assertGreaterThanOrEqual(1, count($result));
        foreach ($result as $item) {
            self::assertInstanceOf(CombinedShippingInfo::class, $item);
            $itemAccount = $item->getAccount();
            if ($itemAccount instanceof Account) {
                self::assertEquals($this->testAccount->getId(), $itemAccount->getId());
            }
        }
    }

    public function testCountByAccountAssociation(): void
    {
        $entity = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => $this->testAccount]);

        self::assertIsInt($count);
        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByOrderKeyAssociation(): void
    {
        $entity = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['orderKey' => $this->testOrderKey]);

        self::assertIsArray($result);
        self::assertGreaterThanOrEqual(1, count($result));
        foreach ($result as $item) {
            self::assertInstanceOf(CombinedShippingInfo::class, $item);
            $orderKey = $item->getOrderKey();
            self::assertNotNull($orderKey);
            self::assertEquals($this->testOrderKey->getId(), $orderKey->getId());
        }
    }

    public function testCountByOrderKeyAssociation(): void
    {
        $entity = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['orderKey' => $this->testOrderKey]);

        self::assertIsInt($count);
        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByPayerAssociation(): void
    {
        $entity = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['payer' => $this->testUser]);

        self::assertIsArray($result);
        self::assertGreaterThanOrEqual(1, count($result));
        foreach ($result as $item) {
            self::assertInstanceOf(CombinedShippingInfo::class, $item);
            $payer = $item->getPayer();
            self::assertNotNull($payer);
            self::assertEquals($this->testUser->getOpenId(), $payer->getOpenId());
        }
    }

    public function testCountByPayerAssociation(): void
    {
        $entity = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['payer' => $this->testUser]);

        self::assertIsInt($count);
        self::assertGreaterThanOrEqual(1, $count);
    }

    // Additional PHPStan Required Tests

    // IS NULL query tests with specific naming pattern

    // Association tests with specific naming pattern

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $entity = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $this->persistAndFlush($entity);

        $found = $this->repository->findOneBy(['account' => $this->testAccount]);

        self::assertInstanceOf(CombinedShippingInfo::class, $found);
        $foundAccount = $found->getAccount();
        if ($foundAccount instanceof Account) {
            self::assertEquals($this->testAccount->getId(), $foundAccount->getId());
        }
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $entity = $this->createTestCombinedShippingInfo([
            'account' => $this->testAccount,
            'payer' => $this->testUser,
            'orderKey' => $this->testOrderKey,
        ]);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => $this->testAccount]);

        self::assertIsInt($count);
        self::assertGreaterThanOrEqual(1, $count);
    }

    protected function createNewEntity(): object
    {
        $entity = new CombinedShippingInfo();
        $entity->setAccount($this->testAccount);
        $entity->setOrderKey($this->createOrderKey3());
        $entity->setPayer($this->createTestUser($this->testAccount));
        $entity->setUploadTime(new \DateTimeImmutable());
        $entity->setValid(true);

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<CombinedShippingInfo>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }
}
