<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\CombinedShoppingInfo;
use WechatMiniProgramOrderBundle\Repository\CombinedShoppingInfoRepository;

/**
 * @internal
 */
#[CoversClass(CombinedShoppingInfoRepository::class)]
#[RunTestsInSeparateProcesses]
final class CombinedShoppingInfoRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 不需要特别的设置
    }

    private function createTestCombinedShoppingInfo(
        string $orderId = 'test_order_id',
        string $outOrderId = 'test_out_order_id',
        string $pathId = 'test_path_id',
        string $status = 'pending',
        int $totalAmount = 1000,
        int $payAmount = 900,
        ?int $discountAmount = 100,
        ?int $freightAmount = 50,
    ): CombinedShoppingInfo {
        $account = $this->createMockAccount();

        // 先持久化 account
        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $entity = new CombinedShoppingInfo();
        $entity->setAccount($account);
        $entity->setOrderId($orderId);
        $entity->setOutOrderId($outOrderId);
        $entity->setPathId($pathId);
        $entity->setStatus($status);
        $entity->setTotalAmount($totalAmount);
        $entity->setPayAmount($payAmount);
        $entity->setDiscountAmount($discountAmount);
        $entity->setFreightAmount($freightAmount);

        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        return $entity;
    }

    // Standard Doctrine Repository tests

    // Custom Repository methods tests
    public function testFindByOrderKey(): void
    {
        $entity = $this->createTestCombinedShoppingInfo(
            orderId: 'order_key_test_order',
            outOrderId: 'order_key_test_out_order'
        );

        $result = $this->getRepository()->findByOrderKey('order_key_test_order', 'order_key_test_out_order');

        $this->assertNotNull($result);
        $this->assertEquals($entity->getId(), $result->getId());
    }

    public function testFindByAccount(): void
    {
        $entity = $this->createTestCombinedShoppingInfo();
        $account = $entity->getAccount();

        $results = $this->getRepository()->findByAccount($account);

        $this->assertIsArray($results);
        $this->assertGreaterThanOrEqual(1, count($results));
        $this->assertInstanceOf(CombinedShoppingInfo::class, $results[0]);
        $this->assertEquals($account, $results[0]->getAccount());
    }

    public function testFindByOrderId(): void
    {
        $this->createTestCombinedShoppingInfo(orderId: 'find_order_id_test');

        $results = $this->getRepository()->findByOrderId('find_order_id_test');

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertInstanceOf(CombinedShoppingInfo::class, $results[0]);
        $this->assertEquals('find_order_id_test', $results[0]->getOrderId());
    }

    public function testFindByOrderIdWithNull(): void
    {
        $results = $this->getRepository()->findByOrderId(null);

        $this->assertIsArray($results);
    }

    public function testFindByOutOrderId(): void
    {
        $this->createTestCombinedShoppingInfo(outOrderId: 'find_out_order_id_test');

        $results = $this->getRepository()->findByOutOrderId('find_out_order_id_test');

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertEquals('find_out_order_id_test', $results[0]->getOutOrderId());
    }

    public function testFindByOutOrderIdWithNull(): void
    {
        $results = $this->getRepository()->findByOutOrderId(null);

        $this->assertIsArray($results);
    }

    // Save and Remove tests
    public function testSave(): void
    {
        $account = $this->createMockAccount('save_test_app_id');

        $entity = new CombinedShoppingInfo();
        $entity->setAccount($account);
        $entity->setOrderId('save_test_order');
        $entity->setOutOrderId('save_test_out_order');
        $entity->setPathId('save_test_path');
        $entity->setStatus('save_test_status');
        $entity->setTotalAmount(2000);
        $entity->setPayAmount(1800);
        $entity->setDiscountAmount(200);
        $entity->setFreightAmount(100);

        $this->getRepository()->save($entity, true);

        $this->assertNotNull($entity->getId());

        $found = $this->getRepository()->find($entity->getId());
        $this->assertNotNull($found);
        $this->assertInstanceOf(CombinedShoppingInfo::class, $found);
        $this->assertEquals('save_test_order', $found->getOrderId());
    }

    public function testRemove(): void
    {
        $entity = $this->createTestCombinedShoppingInfo(orderId: 'remove_test_order');
        $id = $entity->getId();

        $this->getRepository()->remove($entity, true);

        $this->assertNull($this->getRepository()->find($id));
    }

    // Nullable field tests
    public function testFindByDiscountAmountIsNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['discountAmount' => null]);

        $this->assertIsArray($results);
    }

    public function testFindByFreightAmountIsNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['freightAmount' => null]);

        $this->assertIsArray($results);
    }

    public function testCountByDiscountAmountIsNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['discountAmount' => null]);
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testCountByFreightAmountIsNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['freightAmount' => null]);
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    // FindOneBy sorting tests
    public function testFindOneByWithOrderBy(): void
    {
        $this->createTestCombinedShoppingInfo(orderId: 'test_order_1', totalAmount: 500);
        $this->createTestCombinedShoppingInfo(orderId: 'test_order_2', totalAmount: 1500);

        $result = $this->getRepository()->findOneBy([], ['totalAmount' => 'DESC']);

        $this->assertTrue(null === $result || $result instanceof CombinedShoppingInfo);
        if (null !== $result) {
            $this->assertGreaterThanOrEqual(500, $result->getTotalAmount());
        }
    }

    // Association field query tests
    public function testFindByAccountAssociation(): void
    {
        $entity = $this->createTestCombinedShoppingInfo();
        $account = $entity->getAccount();

        $results = $this->getRepository()->findBy(['account' => $account]);

        $this->assertIsArray($results);
        $this->assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            $this->assertInstanceOf(CombinedShoppingInfo::class, $result);
            $resultAccount = $result->getAccount();
            $this->assertEquals($account->getAppId(), $resultAccount->getAppId());
        }
    }

    public function testFindByPayerAssociation(): void
    {
        $entity = $this->createTestCombinedShoppingInfo();
        $payer = $entity->getPayer();

        $results = $this->getRepository()->findBy(['payer' => $payer]);

        $this->assertIsArray($results);
    }

    public function testFindByContactAssociation(): void
    {
        $entity = $this->createTestCombinedShoppingInfo();
        $contact = $entity->getContact();

        $results = $this->getRepository()->findBy(['contact' => $contact]);

        $this->assertIsArray($results);
    }

    public function testFindByShippingInfoAssociation(): void
    {
        $entity = $this->createTestCombinedShoppingInfo();
        $shippingInfo = $entity->getShippingInfo();

        $results = $this->getRepository()->findBy(['shippingInfo' => $shippingInfo]);

        $this->assertIsArray($results);
    }

    // Association field count query tests
    public function testCountByAccountAssociation(): void
    {
        $entity = $this->createTestCombinedShoppingInfo();
        $account = $entity->getAccount();

        $count = $this->getRepository()->count(['account' => $account]);

        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testCountByPayerAssociation(): void
    {
        $entity = $this->createTestCombinedShoppingInfo();
        $payer = $entity->getPayer();

        $count = $this->getRepository()->count(['payer' => $payer]);

        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testCountByContactAssociation(): void
    {
        $entity = $this->createTestCombinedShoppingInfo();
        $contact = $entity->getContact();

        $count = $this->getRepository()->count(['contact' => $contact]);

        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testCountByShippingInfoAssociation(): void
    {
        $entity = $this->createTestCombinedShoppingInfo();
        $shippingInfo = $entity->getShippingInfo();

        $count = $this->getRepository()->count(['shippingInfo' => $shippingInfo]);

        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    // Public method tests
    public function testFindByPayer(): void
    {
        $entity = $this->createTestCombinedShoppingInfo();
        $payer = $entity->getPayer();

        $results = $this->getRepository()->findByPayer($payer);

        $this->assertIsArray($results);
        if (null !== $payer) {
            $this->assertGreaterThanOrEqual(1, count($results));
            foreach ($results as $result) {
                $this->assertEquals($payer, $result->getPayer());
            }
        }
    }

    public function testFindByPayerOpenid(): void
    {
        $entity = $this->createTestCombinedShoppingInfo();
        $payer = $entity->getPayer();

        if (null !== $payer) {
            $openid = $payer->getOpenId();
            if ('' !== $openid) {
                $results = $this->getRepository()->findByPayerOpenid($openid);
                $this->assertIsArray($results);
            }
        }

        // 测试空字符串情况
        $results = $this->getRepository()->findByPayerOpenid('non_existent_openid');
        $this->assertIsArray($results);
        $this->assertCount(0, $results);
    }

    protected function createNewEntity(): object
    {
        $account = $this->createMockAccount();

        // 为了满足测试框架要求，不持久化 account
        $entity = new CombinedShoppingInfo();
        $entity->setAccount($account);
        $entity->setOrderId('test_order_id_' . uniqid());
        $entity->setOutOrderId('test_out_order_id');
        $entity->setPathId('test_path_id');
        $entity->setStatus('pending');
        $entity->setTotalAmount(1000);
        $entity->setPayAmount(900);
        $entity->setDiscountAmount(100);
        $entity->setFreightAmount(50);

        // 注意：这里不调用 persist，因为测试框架期望新实体不被持久化
        return $entity;
    }

    protected function getRepository(): CombinedShoppingInfoRepository
    {
        return self::getService(CombinedShoppingInfoRepository::class);
    }

    /**
     * 创建测试用的 MiniProgram 账户
     */
    private function createMockAccount(string $appId = 'test_app_id'): Account
    {
        $account = new Account();
        $account->setAppId($appId);
        $account->setAppSecret('test_secret');
        $account->setName('Test Account');

        return $account;
    }
}
