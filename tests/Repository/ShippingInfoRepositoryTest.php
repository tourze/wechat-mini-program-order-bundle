<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramAuthBundle\Entity\User;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\Repository\ShippingInfoRepository;

/**
 * ShippingInfoRepository 单元测试
 *
 * @internal
 */
#[CoversClass(ShippingInfoRepository::class)]
#[RunTestsInSeparateProcesses]
final class ShippingInfoRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 不需要特别的设置
    }

    /**
     * 测试根据物流单号查找物流信息
     */
    public function testFindByTrackingNoReturnsCorrectShippingInfo(): void
    {
        $shippingInfo1 = $this->createTestShippingInfo(trackingNo: 'TN001');
        $this->createTestShippingInfo(trackingNo: 'TN002');

        $result = $this->getRepository()->findByTrackingNo('TN001');

        $this->assertNotNull($result);
        $this->assertEquals('TN001', $result->getTrackingNo());
        $this->assertEquals($shippingInfo1->getId(), $result->getId());
    }

    /**
     * 创建测试物流信息
     */
    private function createTestShippingInfo(
        string $trackingNo = 'TN123456789',
        string $deliveryCompany = '顺丰速运',
        ?string $deliveryMobile = null,
        string $deliveryName = '测试用户',
        string|int $account = 'test_account',
        ?string $expressCompany = null,
    ): ShippingInfo {
        $accountEntity = new Account();
        $accountEntity->setAppId('test_app_id');
        $accountEntity->setName(is_string($account) ? $account : 'Test Account ' . $trackingNo);
        $accountEntity->setAppSecret('test_app_secret');
        self::getEntityManager()->persist($accountEntity);

        $orderKey = new OrderKey();
        $orderKey->setOrderId('test_order_' . $trackingNo);
        $orderKey->setOutOrderId('test_out_order_' . $trackingNo);
        $orderKey->setOpenid('test_openid_' . $trackingNo);
        $orderKey->setPathId('test_path');
        self::getEntityManager()->persist($orderKey);

        $payer = new User();
        $payer->setOpenId('test_openid_' . $trackingNo . '_' . uniqid());
        self::getEntityManager()->persist($payer);

        $shippingInfo = new ShippingInfo();
        $shippingInfo->setTrackingNo($trackingNo);
        $shippingInfo->setDeliveryCompany($deliveryCompany);
        $shippingInfo->setDeliveryMobile($deliveryMobile ?? '13800138' . substr(md5($trackingNo), 0, 3));
        $shippingInfo->setDeliveryName($deliveryName);
        $shippingInfo->setAccount($accountEntity);
        $shippingInfo->setOrderKey($orderKey);
        $shippingInfo->setPayer($payer);

        // 设置 expressCompany 属性
        if (null !== $expressCompany) {
            $shippingInfo->setExpressCompany($expressCompany);
        } else {
            $shippingInfo->setExpressCompany($deliveryCompany);
        }

        self::getEntityManager()->persist($shippingInfo);
        self::getEntityManager()->flush();

        return $shippingInfo;
    }

    private function createTestShippingInfoWithAccount(string $trackingNo, Account $account): ShippingInfo
    {
        $orderKey = new OrderKey();
        $orderKey->setOrderId('test_order_' . $trackingNo);
        $orderKey->setOutOrderId('test_out_order_' . $trackingNo);
        $orderKey->setOpenid('test_openid_' . $trackingNo);
        $orderKey->setPathId('test_path');
        self::getEntityManager()->persist($orderKey);

        $payer = new User();
        $payer->setOpenId('test_openid_' . $trackingNo . '_' . uniqid());
        self::getEntityManager()->persist($payer);

        $shippingInfo = new ShippingInfo();
        $shippingInfo->setTrackingNo($trackingNo);
        $shippingInfo->setDeliveryCompany('顺丰速运');
        $shippingInfo->setDeliveryMobile('13800138' . substr(md5($trackingNo), 0, 3));
        $shippingInfo->setDeliveryName('测试用户');
        $shippingInfo->setAccount($account);
        $shippingInfo->setOrderKey($orderKey);
        $shippingInfo->setPayer($payer);
        $shippingInfo->setExpressCompany('顺丰速运');

        self::getEntityManager()->persist($shippingInfo);
        self::getEntityManager()->flush();

        return $shippingInfo;
    }

    /**
     * 创建测试物流信息但不持久化到数据库
     */
    private function createTestShippingInfoWithoutPersist(
        string $trackingNo = 'TN123456789',
        string $deliveryCompany = '顺丰速运',
        ?string $deliveryMobile = null,
        string $deliveryName = '测试用户',
    ): ShippingInfo {
        $accountEntity = new Account();
        $accountEntity->setAppId('test_app_id_' . $trackingNo);
        $accountEntity->setName('Test Account ' . $trackingNo);
        $accountEntity->setAppSecret('test_app_secret');
        self::getEntityManager()->persist($accountEntity);

        $orderKey = new OrderKey();
        $orderKey->setOrderId('test_order_' . $trackingNo);
        $orderKey->setOutOrderId('test_out_order_' . $trackingNo);
        $orderKey->setOpenid('test_openid_' . $trackingNo);
        $orderKey->setPathId('test_path');
        self::getEntityManager()->persist($orderKey);

        $payer = new User();
        $payer->setOpenId('test_openid_' . $trackingNo . '_' . uniqid());
        self::getEntityManager()->persist($payer);

        // 先flush依赖实体
        self::getEntityManager()->flush();

        $shippingInfo = new ShippingInfo();
        $shippingInfo->setTrackingNo($trackingNo);
        $shippingInfo->setDeliveryCompany($deliveryCompany);
        $shippingInfo->setDeliveryMobile($deliveryMobile ?? '13800138' . substr(md5($trackingNo), 0, 3));
        $shippingInfo->setDeliveryName($deliveryName);
        $shippingInfo->setAccount($accountEntity);
        $shippingInfo->setOrderKey($orderKey);
        $shippingInfo->setPayer($payer);
        $shippingInfo->setExpressCompany($deliveryCompany);

        // 不persist不flush ShippingInfo，让测试方法控制何时持久化
        return $shippingInfo;
    }

    /**
     * 测试根据不存在的物流单号查找
     */
    public function testFindByTrackingNoWithNonExistentNoReturnsNull(): void
    {
        $this->createTestShippingInfo(trackingNo: 'TN001');

        $result = $this->getRepository()->findByTrackingNo('NONEXISTENT');

        $this->assertNull($result);
    }

    /**
     * 测试根据快递公司查找物流信息列表
     */
    public function testFindByExpressCompanyReturnsCorrectShippingInfos(): void
    {
        $this->createTestShippingInfo(trackingNo: 'TN001', expressCompany: '顺丰速运');
        $this->createTestShippingInfo(trackingNo: 'TN002', expressCompany: '顺丰速运');
        $this->createTestShippingInfo(trackingNo: 'TN003', expressCompany: '申通快递');

        $results = $this->getRepository()->findByExpressCompany('顺丰速运');

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals('顺丰速运', $result->getExpressCompany());
        }
    }

    /**
     * 测试根据收货人手机号查找物流信息
     */
    public function testFindByDeliveryMobileReturnsCorrectShippingInfos(): void
    {
        $testMobile = '13800000' . substr(uniqid(), -3);
        $this->createTestShippingInfo(trackingNo: 'TN001', deliveryMobile: $testMobile);
        $this->createTestShippingInfo(trackingNo: 'TN002', deliveryMobile: $testMobile);
        $this->createTestShippingInfo(trackingNo: 'TN003', deliveryMobile: '13900139000');

        $results = $this->getRepository()->findByDeliveryMobile($testMobile);

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals($testMobile, $result->getDeliveryMobile());
        }
    }

    /**
     * 测试根据收货人姓名查找物流信息
     */
    public function testFindByDeliveryNameReturnsCorrectShippingInfos(): void
    {
        $this->createTestShippingInfo(trackingNo: 'TN001', deliveryName: '张三');
        $this->createTestShippingInfo(trackingNo: 'TN002', deliveryName: '张三');
        $this->createTestShippingInfo(trackingNo: 'TN003', deliveryName: '李四');

        $results = $this->getRepository()->findByDeliveryName('张三');

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals('张三', $result->getDeliveryName());
        }
    }

    /**
     * 测试根据账号查找物流信息
     */
    public function testFindByAccountReturnsCorrectShippingInfos(): void
    {
        // Create shared account
        $sharedAccount = new Account();
        $sharedAccount->setAppId('test_app_id_shared');
        $sharedAccount->setName('account_001');
        $sharedAccount->setAppSecret('test_app_secret');
        self::getEntityManager()->persist($sharedAccount);
        self::getEntityManager()->flush();

        $shipping1 = $this->createTestShippingInfoWithAccount('TN001', $sharedAccount);
        $shipping2 = $this->createTestShippingInfoWithAccount('TN002', $sharedAccount);
        $this->createTestShippingInfo(trackingNo: 'TN003', account: 'account_002');

        $results = $this->getRepository()->findByAccount($sharedAccount);

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals($sharedAccount->getAppId(), $result->getAccount()->getAppId());
        }
    }

    /**
     * 测试根据账号查找物流信息 - 不同类型的账号
     */
    public function testFindByAccountWithDifferentTypesReturnsCorrectShippingInfos(): void
    {
        // Create shared account
        $numericAccount = new Account();
        $numericAccount->setAppId('test_app_id_123');
        $numericAccount->setName('123');
        $numericAccount->setAppSecret('test_app_secret');
        self::getEntityManager()->persist($numericAccount);
        self::getEntityManager()->flush();

        $shipping1 = $this->createTestShippingInfoWithAccount('TN001', $numericAccount);
        $shipping2 = $this->createTestShippingInfoWithAccount('TN002', $numericAccount);
        $this->createTestShippingInfo(trackingNo: 'TN003', account: 'string_account');

        $results = $this->getRepository()->findByAccount($numericAccount);

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals($numericAccount->getAppId(), $result->getAccount()->getAppId());
        }
    }

    /**
     * 测试保存物流信息
     */
    public function testSavePersistsShippingInfoCorrectly(): void
    {
        $shippingInfo = $this->createTestShippingInfo(
            trackingNo: 'TEST_SAVE',
            deliveryCompany: '测试快递',
            deliveryMobile: '13800138001',
            deliveryName: '测试用户',
            expressCompany: '测试快递'
        );

        $this->getRepository()->save($shippingInfo, flush: true);

        $savedShippingInfo = $this->getRepository()->findByTrackingNo('TEST_SAVE');
        $this->assertNotNull($savedShippingInfo);
        $this->assertEquals('TEST_SAVE', $savedShippingInfo->getTrackingNo());
        $this->assertEquals('测试快递', $savedShippingInfo->getExpressCompany());
        $this->assertEquals('13800138001', $savedShippingInfo->getDeliveryMobile());
        $this->assertEquals('测试用户', $savedShippingInfo->getDeliveryName());
        $this->assertEquals('test_app_id', $savedShippingInfo->getAccount()->getAppId());
    }

    /**
     * 测试删除物流信息
     */
    public function testRemoveDeletesShippingInfoCorrectly(): void
    {
        $shippingInfo = $this->createTestShippingInfo(trackingNo: 'TO_DELETE');

        $this->getRepository()->remove($shippingInfo, flush: true);

        $deletedShippingInfo = $this->getRepository()->findByTrackingNo('TO_DELETE');
        $this->assertNull($deletedShippingInfo);
    }

    /**
     * 测试边界情况 - 空快递公司列表
     */
    public function testFindByExpressCompanyWithNonExistentCompanyReturnsEmptyArray(): void
    {
        $this->createTestShippingInfo(expressCompany: '顺丰速运');

        $results = $this->getRepository()->findByExpressCompany('不存在的快递公司');

        $this->assertEmpty($results);
    }

    /**
     * 测试边界情况 - 空手机号列表
     */
    public function testFindByDeliveryMobileWithNonExistentMobileReturnsEmptyArray(): void
    {
        $this->createTestShippingInfo(deliveryMobile: '13800138000');

        $results = $this->getRepository()->findByDeliveryMobile('99999999999');

        $this->assertEmpty($results);
    }

    /**
     * 测试边界情况 - 空姓名列表
     */
    public function testFindByDeliveryNameWithNonExistentNameReturnsEmptyArray(): void
    {
        $this->createTestShippingInfo(deliveryName: '张三');

        $results = $this->getRepository()->findByDeliveryName('不存在的姓名');

        $this->assertEmpty($results);
    }

    /**
     * 测试边界情况 - 空账号列表
     */
    public function testFindByAccountWithNonExistentAccountReturnsEmptyArray(): void
    {
        $this->createTestShippingInfo(account: 'existing_account');

        $results = $this->getRepository()->findByAccount('nonexistent_account');

        $this->assertEmpty($results);
    }

    /**
     * 测试保存但不刷新
     */
    public function testSaveWithoutFlushDoesNotPersistImmediately(): void
    {
        $shippingInfo = $this->createTestShippingInfo(trackingNo: 'TEST_NO_FLUSH');

        // 先删除已经被persist的实体，避免干扰测试
        self::getEntityManager()->remove($shippingInfo);
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();

        // 重新创建实体但不flush
        $newShippingInfo = $this->createTestShippingInfoWithoutPersist(trackingNo: 'TEST_NO_FLUSH_2');
        $this->getRepository()->save($newShippingInfo, flush: false);

        // 实体应该在EntityManager中被管理
        $this->assertTrue(self::getEntityManager()->contains($newShippingInfo));

        // 清除EntityManager缓存后从数据库查询应该找不到
        self::getEntityManager()->clear();
        $savedShippingInfo = $this->getRepository()->findByTrackingNo('TEST_NO_FLUSH_2');
        $this->assertNull($savedShippingInfo);

        // 重新创建实体并手动flush，然后应该能找到
        $finalShippingInfo = $this->createTestShippingInfoWithoutPersist(trackingNo: 'TEST_NO_FLUSH_3');
        $this->getRepository()->save($finalShippingInfo, flush: true);

        $finalSavedShippingInfo = $this->getRepository()->findByTrackingNo('TEST_NO_FLUSH_3');
        $this->assertNotNull($finalSavedShippingInfo);
    }

    /**
     * 测试查找特殊字符的物流单号
     */
    public function testFindByTrackingNoWithSpecialCharactersReturnsCorrectResult(): void
    {
        $specialTrackingNo = 'TN-001_TEST@2024';
        $shippingInfo = $this->createTestShippingInfo(trackingNo: $specialTrackingNo);

        $result = $this->getRepository()->findByTrackingNo($specialTrackingNo);

        $this->assertNotNull($result);
        $this->assertEquals($specialTrackingNo, $result->getTrackingNo());
        $this->assertEquals($shippingInfo->getId(), $result->getId());
    }

    /**
     * 测试查找特殊字符的快递公司
     */
    public function testFindByExpressCompanyWithSpecialCharactersReturnsCorrectResults(): void
    {
        $specialCompany = 'SF Express (中国)';
        $this->createTestShippingInfo(trackingNo: 'TN001', expressCompany: $specialCompany);
        $this->createTestShippingInfo(trackingNo: 'TN002', expressCompany: $specialCompany);

        $results = $this->getRepository()->findByExpressCompany($specialCompany);

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals($specialCompany, $result->getExpressCompany());
        }
    }

    // Standard Doctrine Repository tests

    protected function onTearDown(): void
    {
        // EntityManager is managed by AbstractIntegrationTestCase
    }

    protected function createNewEntity(): object
    {
        $account = new Account();
        $account->setAppId('test_app_id_new');
        $account->setName('Test Account New');
        $account->setAppSecret('test_app_secret_new');

        $orderKey = new OrderKey();
        $orderKey->setOrderId('test_order_create_new');
        $orderKey->setOutOrderId('test_out_order_create_new');
        $orderKey->setOpenid('test_openid_new');
        $orderKey->setPathId('test_path');

        $payer = new User();
        $payer->setOpenId('test_openid_new_' . uniqid());

        $shippingInfo = new ShippingInfo();
        $shippingInfo->setTrackingNo('TN_CREATE_NEW_123');
        $shippingInfo->setDeliveryCompany('测试物流公司');
        $shippingInfo->setDeliveryMobile('13800138000');
        $shippingInfo->setDeliveryName('测试收件人');
        $shippingInfo->setAccount($account);
        $shippingInfo->setOrderKey($orderKey);
        $shippingInfo->setPayer($payer);
        $shippingInfo->setExpressCompany('测试物流公司');

        return $shippingInfo;
    }

    protected function getRepository(): ShippingInfoRepository
    {
        $repository = self::getEntityManager()->getRepository(ShippingInfo::class);
        $this->assertInstanceOf(ShippingInfoRepository::class, $repository);

        return $repository;
    }
}
