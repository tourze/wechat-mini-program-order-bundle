<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfoVerifyUpload;
use WechatMiniProgramOrderBundle\Enum\ShoppingInfoVerifyStatus;
use WechatMiniProgramOrderBundle\Repository\ShoppingInfoVerifyUploadRepository;

/**
 * ShoppingInfoVerifyUploadRepository 单元测试
 *
 * @internal
 */
#[CoversClass(ShoppingInfoVerifyUploadRepository::class)]
#[RunTestsInSeparateProcesses]
final class ShoppingInfoVerifyUploadRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 不需要特别的设置
    }

    /**
     * 测试根据订单ID查找最新的验证记录
     */
    public function testFindLatestByOrderIdReturnsLatestRecord(): void
    {
        $olderTime = new \DateTimeImmutable('2024-01-01 10:00:00');
        $newerTime = new \DateTimeImmutable('2024-01-01 12:00:00');

        // 创建多个验证记录，验证返回最新的
        $this->createTestShoppingInfoVerifyUpload(
            orderId: 'ORDER001',
            status: ShoppingInfoVerifyStatus::PENDING,
            createTime: $olderTime
        );
        $newerRecord = $this->createTestShoppingInfoVerifyUpload(
            orderId: 'ORDER001',
            status: ShoppingInfoVerifyStatus::APPROVED,
            createTime: $newerTime
        );

        // 创建其他订单的记录
        $this->createTestShoppingInfoVerifyUpload(orderId: 'ORDER002');

        $result = $this->getRepository()->findLatestByOrderId('ORDER001');

        $this->assertNotNull($result);
        $this->assertEquals('ORDER001', $result->getOrderId());
        $this->assertEquals(ShoppingInfoVerifyStatus::APPROVED, $result->getStatus());
        $this->assertEquals($newerRecord->getId(), $result->getId());
    }

    /**
     * 创建测试验证上传记录
     */
    private function createTestShoppingInfoVerifyUpload(
        string $orderId = 'ORDER123',
        ShoppingInfoVerifyStatus $status = ShoppingInfoVerifyStatus::PENDING,
        ?\DateTimeImmutable $createTime = null,
    ): ShoppingInfoVerifyUpload {
        $verifyUpload = new ShoppingInfoVerifyUpload();
        $verifyUpload->setOrderId($orderId);
        $verifyUpload->setOutOrderId('OUT_' . $orderId); // 设置必需的 outOrderId
        $verifyUpload->setPathId('PATH_' . uniqid()); // 设置必需的 pathId
        $verifyUpload->setStatus($status);

        // 设置创建时间（如果需要特定时间）
        if (null !== $createTime) {
            $verifyUpload->setCreateTime($createTime);
        }

        self::getEntityManager()->persist($verifyUpload);
        self::getEntityManager()->flush();

        return $verifyUpload;
    }

    /**
     * 测试根据不存在的订单ID查找最新验证记录
     */
    public function testFindLatestByOrderIdWithNonExistentIdReturnsNull(): void
    {
        $this->createTestShoppingInfoVerifyUpload(orderId: 'ORDER001');

        $result = $this->getRepository()->findLatestByOrderId('NONEXISTENT_ORDER');

        $this->assertNull($result);
    }

    /**
     * 测试查找所有待验证的记录
     */
    public function testFindAllPendingReturnsOnlyPendingRecords(): void
    {
        $this->createTestShoppingInfoVerifyUpload(orderId: 'ORDER001', status: ShoppingInfoVerifyStatus::PENDING);
        $this->createTestShoppingInfoVerifyUpload(orderId: 'ORDER002', status: ShoppingInfoVerifyStatus::PENDING);
        $this->createTestShoppingInfoVerifyUpload(orderId: 'ORDER003', status: ShoppingInfoVerifyStatus::APPROVED);
        $this->createTestShoppingInfoVerifyUpload(orderId: 'ORDER004', status: ShoppingInfoVerifyStatus::REJECTED);

        $results = $this->getRepository()->findAllPending();

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals(ShoppingInfoVerifyStatus::PENDING, $result->getStatus());
        }

        $orderIds = array_map(fn ($r) => $r->getOrderId(), $results);
        $this->assertContains('ORDER001', $orderIds);
        $this->assertContains('ORDER002', $orderIds);
        $this->assertNotContains('ORDER003', $orderIds);
        $this->assertNotContains('ORDER004', $orderIds);
    }

    /**
     * 测试查找所有待验证的记录 - 无待验证记录
     */
    public function testFindAllPendingWithNoPendingRecordsReturnsEmptyArray(): void
    {
        $this->createTestShoppingInfoVerifyUpload(orderId: 'ORDER001', status: ShoppingInfoVerifyStatus::APPROVED);
        $this->createTestShoppingInfoVerifyUpload(orderId: 'ORDER002', status: ShoppingInfoVerifyStatus::REJECTED);

        $results = $this->getRepository()->findAllPending();

        $this->assertEmpty($results);
    }

    /**
     * 测试保存验证上传记录
     */
    public function testSavePersistsVerifyUploadCorrectly(): void
    {
        $verifyUpload = new ShoppingInfoVerifyUpload();
        $verifyUpload->setOrderId('TEST_SAVE_ORDER');
        $verifyUpload->setOutOrderId('OUT_TEST_SAVE_ORDER');
        $verifyUpload->setPathId('PATH_' . uniqid());
        $verifyUpload->setStatus(ShoppingInfoVerifyStatus::PENDING);

        $this->getRepository()->save($verifyUpload, flush: true);

        $savedRecord = $this->getRepository()->findLatestByOrderId('TEST_SAVE_ORDER');
        $this->assertNotNull($savedRecord);
        $this->assertEquals('TEST_SAVE_ORDER', $savedRecord->getOrderId());
        $this->assertEquals(ShoppingInfoVerifyStatus::PENDING, $savedRecord->getStatus());
    }

    /**
     * 测试删除验证上传记录
     */
    public function testRemoveDeletesVerifyUploadCorrectly(): void
    {
        $verifyUpload = $this->createTestShoppingInfoVerifyUpload(orderId: 'ORDER_TO_DELETE');

        $this->getRepository()->remove($verifyUpload, flush: true);

        $deletedRecord = $this->getRepository()->findLatestByOrderId('ORDER_TO_DELETE');
        $this->assertNull($deletedRecord);
    }

    /**
     * 测试保存但不刷新
     */
    public function testSaveWithoutFlushDoesNotPersistImmediately(): void
    {
        $verifyUpload = new ShoppingInfoVerifyUpload();
        $verifyUpload->setOrderId('TEST_NO_FLUSH');
        $verifyUpload->setOutOrderId('OUT_TEST_NO_FLUSH');
        $verifyUpload->setPathId('PATH_' . uniqid());
        $verifyUpload->setStatus(ShoppingInfoVerifyStatus::PENDING);

        $this->getRepository()->save($verifyUpload, flush: false);

        // 实体应该在实体管理器中被管理
        $this->assertTrue(self::getEntityManager()->contains($verifyUpload));

        // 在flush前，从数据库查询应该找不到
        self::getEntityManager()->clear();
        $savedRecord = $this->getRepository()->findLatestByOrderId('TEST_NO_FLUSH');
        $this->assertNull($savedRecord);

        // 重新创建并保存实体，然后flush
        $verifyUpload2 = new ShoppingInfoVerifyUpload();
        $verifyUpload2->setOrderId('TEST_NO_FLUSH');
        $verifyUpload2->setOutOrderId('OUT_TEST_NO_FLUSH');
        $verifyUpload2->setPathId('PATH_' . uniqid());
        $verifyUpload2->setStatus(ShoppingInfoVerifyStatus::PENDING);

        $this->getRepository()->save($verifyUpload2, flush: true);

        // 现在应该能找到
        $savedRecord = $this->getRepository()->findLatestByOrderId('TEST_NO_FLUSH');
        $this->assertNotNull($savedRecord);
    }

    /**
     * 测试不同状态的验证记录查找
     */
    public function testFindAllPendingWithMixedStatusesReturnsCorrectResults(): void
    {
        // 创建各种状态的记录
        $statuses = [
            ShoppingInfoVerifyStatus::PENDING,
            ShoppingInfoVerifyStatus::APPROVED,
            ShoppingInfoVerifyStatus::REJECTED,
            ShoppingInfoVerifyStatus::PENDING, // 再来一个待验证的
        ];

        foreach ($statuses as $index => $status) {
            $this->createTestShoppingInfoVerifyUpload(
                orderId: "ORDER_STATUS_{$index}",
                status: $status
            );
        }

        $pendingResults = $this->getRepository()->findAllPending();

        $this->assertCount(2, $pendingResults);
        foreach ($pendingResults as $result) {
            $this->assertEquals(ShoppingInfoVerifyStatus::PENDING, $result->getStatus());
        }
    }

    /**
     * 测试特殊字符的订单ID
     */
    public function testFindLatestByOrderIdWithSpecialCharactersReturnsCorrectResult(): void
    {
        $specialOrderId = 'ORDER-001_TEST@2024';
        $verifyUpload = $this->createTestShoppingInfoVerifyUpload(orderId: $specialOrderId);

        $result = $this->getRepository()->findLatestByOrderId($specialOrderId);

        $this->assertNotNull($result);
        $this->assertEquals($specialOrderId, $result->getOrderId());
        $this->assertEquals($verifyUpload->getId(), $result->getId());
    }

    /**
     * 测试大量数据查询性能
     */
    public function testFindLatestByOrderIdWithMultipleRecordsReturnsLatestOnly(): void
    {
        // 为同一个订单创建多个验证记录
        $baseTime = new \DateTimeImmutable('2024-01-01 10:00:00');
        for ($i = 1; $i <= 10; ++$i) {
            $this->createTestShoppingInfoVerifyUpload(
                orderId: 'BULK_ORDER',
                status: 0 === $i % 2 ? ShoppingInfoVerifyStatus::PENDING : ShoppingInfoVerifyStatus::APPROVED,
                createTime: $baseTime->modify("+{$i} minutes")
            );
        }

        $result = $this->getRepository()->findLatestByOrderId('BULK_ORDER');

        $this->assertNotNull($result);
        $this->assertEquals('BULK_ORDER', $result->getOrderId());
        // 最后一个记录应该是APPROVED状态（因为10%2=0，所以是PENDING，但9%2=1是APPROVED，实际最后创建的是10，应该是PENDING）
        $this->assertEquals(ShoppingInfoVerifyStatus::PENDING, $result->getStatus());
    }

    /**
     * 测试大量待验证记录查询
     */
    public function testFindAllPendingWithLargeDatasetReturnsCorrectResults(): void
    {
        // 创建大量不同状态的记录
        $pendingCount = 0;
        $totalCount = 30;

        for ($i = 1; $i <= $totalCount; ++$i) {
            $status = match ($i % 3) {
                0 => ShoppingInfoVerifyStatus::PENDING,
                1 => ShoppingInfoVerifyStatus::APPROVED,
                2 => ShoppingInfoVerifyStatus::REJECTED,
            };

            if (ShoppingInfoVerifyStatus::PENDING === $status) {
                ++$pendingCount;
            }

            $this->createTestShoppingInfoVerifyUpload(
                orderId: "BULK_ORDER_{$i}",
                status: $status
            );
        }

        $results = $this->getRepository()->findAllPending();

        $this->assertCount($pendingCount, $results);
        foreach ($results as $result) {
            $this->assertEquals(ShoppingInfoVerifyStatus::PENDING, $result->getStatus());
        }
    }

    /**
     * 测试删除操作的完整性
     */
    public function testRemoveWithFlushFalseDoesNotDeleteImmediately(): void
    {
        $verifyUpload = $this->createTestShoppingInfoVerifyUpload(orderId: 'DELETE_TEST');

        $this->getRepository()->remove($verifyUpload, flush: false);

        // 在flush前，应该还能查到（因为还在EntityManager中）
        $result = $this->getRepository()->findLatestByOrderId('DELETE_TEST');
        $this->assertNotNull($result);

        // 手动flush后应该删除
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
        $result = $this->getRepository()->findLatestByOrderId('DELETE_TEST');
        $this->assertNull($result);
    }

    /**
     * 测试时间排序的正确性
     */
    public function testFindLatestByOrderIdReturnsRecordWithLatestCreatedAt(): void
    {
        $times = [
            new \DateTimeImmutable('2024-01-01 10:00:00'),
            new \DateTimeImmutable('2024-01-01 12:00:00'),
            new \DateTimeImmutable('2024-01-01 08:00:00'), // 最早的
            new \DateTimeImmutable('2024-01-01 15:00:00'), // 最晚的，应该被返回
            new \DateTimeImmutable('2024-01-01 11:00:00'),
        ];

        $expectedLatestRecord = null;
        foreach ($times as $time) {
            $record = $this->createTestShoppingInfoVerifyUpload(
                orderId: 'TIME_TEST_ORDER',
                status: ShoppingInfoVerifyStatus::PENDING,
                createTime: $time
            );

            // 记录最晚时间的记录
            if ('15:00:00' === $time->format('H:i:s')) {
                $expectedLatestRecord = $record;
            }
        }

        $result = $this->getRepository()->findLatestByOrderId('TIME_TEST_ORDER');

        $this->assertNotNull($result);
        $this->assertNotNull($expectedLatestRecord);
        $this->assertEquals($expectedLatestRecord->getId(), $result->getId());
        $createTime = $result->getCreateTime();
        $this->assertNotNull($createTime);
        $this->assertEquals('15:00:00', $createTime->format('H:i:s'));
    }

    // Standard Doctrine Repository tests

    protected function onTearDown(): void
    {
        // EntityManager is managed by AbstractIntegrationTestCase
    }

    protected function createNewEntity(): object
    {
        $verifyUpload = new ShoppingInfoVerifyUpload();
        $verifyUpload->setOrderId('TEST_ENTITY_' . uniqid());
        $verifyUpload->setOutOrderId('OUT_TEST_ENTITY_' . uniqid());
        $verifyUpload->setPathId('PATH_' . uniqid());
        $verifyUpload->setStatus(ShoppingInfoVerifyStatus::APPROVED); // 使用非PENDING状态避免干扰测试

        return $verifyUpload;
    }

    protected function getRepository(): ShoppingInfoVerifyUploadRepository
    {
        $repository = self::getEntityManager()->getRepository(ShoppingInfoVerifyUpload::class);
        $this->assertInstanceOf(ShoppingInfoVerifyUploadRepository::class, $repository);

        return $repository;
    }
}
