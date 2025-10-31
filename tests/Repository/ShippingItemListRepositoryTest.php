<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramAuthBundle\Entity\User;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShippingItemList;
use WechatMiniProgramOrderBundle\Entity\ShippingList;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Repository\ShippingItemListRepository;

/**
 * ShippingItemListRepository 单元测试
 *
 * @internal
 */
#[CoversClass(ShippingItemListRepository::class)]
#[RunTestsInSeparateProcesses]
final class ShippingItemListRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 不需要特别的设置
    }

    /**
     * 测试根据商户商品ID查找物流商品
     */
    public function testFindByMerchantItemIdReturnsCorrectItems(): void
    {
        $this->createTestShippingItemList(merchantItemId: 'ITEM001', shippingListId: 'SHIP001');
        $this->createTestShippingItemList(merchantItemId: 'ITEM001', shippingListId: 'SHIP002');
        $this->createTestShippingItemList(merchantItemId: 'ITEM002', shippingListId: 'SHIP003');

        $results = $this->getRepository()->findByMerchantItemId('ITEM001');

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals('ITEM001', $result->getMerchantItemId());
        }
    }

    /**
     * 创建测试物流商品
     */
    private function createTestShippingItemList(
        string $merchantItemId = 'ITEM123',
        string $shippingListId = 'SHIP123',
    ): ShippingItemList {
        // 创建Account并持久化
        $account = $this->createTestAccount();
        self::getEntityManager()->persist($account);

        // 创建User并持久化
        $user = $this->createTestUser($account);
        self::getEntityManager()->persist($user);

        // 创建OrderKey并持久化
        $orderKey1 = $this->createTestOrderKey();
        $orderKey2 = $this->createTestOrderKey();
        self::getEntityManager()->persist($orderKey1);
        self::getEntityManager()->persist($orderKey2);

        // 创建 CombinedShippingInfo
        $combinedShippingInfo = new CombinedShippingInfo();
        $combinedShippingInfo->setAccount($account);
        $combinedShippingInfo->setPayer($user);
        $combinedShippingInfo->setOrderKey($orderKey1);
        $combinedShippingInfo->setValid(true);

        // 创建 SubOrderList
        $subOrder = new SubOrderList();
        $subOrder->setCombinedShippingInfo($combinedShippingInfo);
        $subOrder->setOrderKey($orderKey2);

        // 创建 ShippingList 实体
        $shippingList = new ShippingList();
        $shippingList->setSubOrder($subOrder);
        $shippingList->setTrackingNo($shippingListId);
        $shippingList->setExpressCompany('TEST_EXPRESS');

        self::getEntityManager()->persist($combinedShippingInfo);
        self::getEntityManager()->persist($subOrder);
        self::getEntityManager()->persist($shippingList);

        $shippingItemList = new ShippingItemList();
        $shippingItemList->setMerchantItemId($merchantItemId);
        $shippingItemList->setShippingList($shippingList);

        self::getEntityManager()->persist($shippingItemList);
        self::getEntityManager()->flush();

        return $shippingItemList;
    }

    /**
     * 测试根据物流单号查找物流商品列表
     */
    public function testFindByShippingListReturnsCorrectItems(): void
    {
        // 创建共享的基础数据
        $account = $this->createTestAccount();
        $user = $this->createTestUser($account);
        $orderKey1 = $this->createTestOrderKey();
        $orderKey2 = $this->createTestOrderKey();
        self::getEntityManager()->persist($account);
        self::getEntityManager()->persist($user);
        self::getEntityManager()->persist($orderKey1);
        self::getEntityManager()->persist($orderKey2);

        // 创建 CombinedShippingInfo 和 SubOrder
        $combinedShippingInfo = new CombinedShippingInfo();
        $combinedShippingInfo->setAccount($account);
        $combinedShippingInfo->setPayer($user);
        $combinedShippingInfo->setOrderKey($orderKey1);
        $combinedShippingInfo->setValid(true);

        $subOrder = new SubOrderList();
        $subOrder->setCombinedShippingInfo($combinedShippingInfo);
        $subOrder->setOrderKey($orderKey2);

        // 创建两个不同的 ShippingList
        $shippingList1 = new ShippingList();
        $shippingList1->setSubOrder($subOrder);
        $shippingList1->setTrackingNo('SHIP001');
        $shippingList1->setExpressCompany('TEST_EXPRESS');

        $shippingList2 = new ShippingList();
        $shippingList2->setSubOrder($subOrder);
        $shippingList2->setTrackingNo('SHIP002');
        $shippingList2->setExpressCompany('TEST_EXPRESS');

        self::getEntityManager()->persist($combinedShippingInfo);
        self::getEntityManager()->persist($subOrder);
        self::getEntityManager()->persist($shippingList1);
        self::getEntityManager()->persist($shippingList2);

        // 创建物流商品项目
        $item1 = new ShippingItemList();
        $item1->setMerchantItemId('ITEM001');
        $item1->setShippingList($shippingList1);

        $item2 = new ShippingItemList();
        $item2->setMerchantItemId('ITEM002');
        $item2->setShippingList($shippingList1);

        $item3 = new ShippingItemList();
        $item3->setMerchantItemId('ITEM003');
        $item3->setShippingList($shippingList2);

        self::getEntityManager()->persist($item1);
        self::getEntityManager()->persist($item2);
        self::getEntityManager()->persist($item3);
        self::getEntityManager()->flush();

        $results = $this->getRepository()->findByShippingList('SHIP001');

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $shippingList = $result->getShippingList();
            $this->assertNotNull($shippingList);
            $this->assertEquals('SHIP001', $shippingList->getTrackingNo());
        }
    }

    /**
     * 测试保存物流商品
     */
    public function testSavePersistsShippingItemListCorrectly(): void
    {
        $shippingItemList = $this->createTestShippingItemList('TEST_SAVE_ITEM', 'TEST_SAVE_SHIP');

        // 验证保存成功
        $savedItems = $this->getRepository()->findByMerchantItemId('TEST_SAVE_ITEM');
        $this->assertCount(1, $savedItems);
        $this->assertEquals('TEST_SAVE_ITEM', $savedItems[0]->getMerchantItemId());
        $shippingList = $savedItems[0]->getShippingList();
        $this->assertNotNull($shippingList);
        $this->assertEquals('TEST_SAVE_SHIP', $shippingList->getTrackingNo());
    }

    /**
     * 测试删除物流商品
     */
    public function testRemoveDeletesShippingItemListCorrectly(): void
    {
        $shippingItemList = $this->createTestShippingItemList(merchantItemId: 'TO_DELETE');

        $this->getRepository()->remove($shippingItemList, flush: true);

        $deletedItems = $this->getRepository()->findByMerchantItemId('TO_DELETE');
        $this->assertEmpty($deletedItems);
    }

    /**
     * 测试边界情况 - 不存在的商户商品ID
     */
    public function testFindByMerchantItemIdWithNonExistentIdReturnsEmptyArray(): void
    {
        $this->createTestShippingItemList(merchantItemId: 'EXISTING_ITEM');

        $results = $this->getRepository()->findByMerchantItemId('NONEXISTENT_ITEM');

        $this->assertEmpty($results);
    }

    /**
     * 测试边界情况 - 不存在的物流单号
     */
    public function testFindByShippingListWithNonExistentIdReturnsEmptyArray(): void
    {
        $this->createTestShippingItemList(shippingListId: 'EXISTING_SHIP');

        $results = $this->getRepository()->findByShippingList('NONEXISTENT_SHIP');

        $this->assertEmpty($results);
    }

    /**
     * 测试保存但不刷新
     */
    public function testSaveWithoutFlushDoesNotPersistImmediately(): void
    {
        // 创建完整的实体层次结构
        $account = $this->createTestAccount();
        $user = $this->createTestUser($account);
        $orderKey1 = $this->createTestOrderKey();
        $orderKey2 = $this->createTestOrderKey();

        self::getEntityManager()->persist($account);
        self::getEntityManager()->persist($user);
        self::getEntityManager()->persist($orderKey1);
        self::getEntityManager()->persist($orderKey2);

        $combinedShippingInfo = new CombinedShippingInfo();
        $combinedShippingInfo->setAccount($account);
        $combinedShippingInfo->setPayer($user);
        $combinedShippingInfo->setOrderKey($orderKey1);
        $combinedShippingInfo->setValid(true);

        $subOrder = new SubOrderList();
        $subOrder->setCombinedShippingInfo($combinedShippingInfo);
        $subOrder->setOrderKey($orderKey2);

        $shippingList = new ShippingList();
        $shippingList->setSubOrder($subOrder);
        $shippingList->setTrackingNo('TEST_NO_FLUSH_SHIP');
        $shippingList->setExpressCompany('TEST_EXPRESS');

        self::getEntityManager()->persist($combinedShippingInfo);
        self::getEntityManager()->persist($subOrder);
        self::getEntityManager()->persist($shippingList);
        self::getEntityManager()->flush(); // 先flush依赖实体

        $shippingItemList = new ShippingItemList();
        $shippingItemList->setMerchantItemId('TEST_NO_FLUSH');
        $shippingItemList->setShippingList($shippingList);

        $this->getRepository()->save($shippingItemList, flush: false);

        // 在flush前应该已经在entity manager中，但还没有到数据库
        $this->assertTrue(self::getEntityManager()->contains($shippingItemList));

        // 手动flush后应该能找到
        self::getEntityManager()->flush();
        $savedItems = $this->getRepository()->findByMerchantItemId('TEST_NO_FLUSH');
        $this->assertCount(1, $savedItems);
    }

    /**
     * 测试特殊字符的商户商品ID
     */
    public function testFindByMerchantItemIdWithSpecialCharactersReturnsCorrectResults(): void
    {
        $specialItemId = 'ITEM-001_TEST@2024';
        $this->createTestShippingItemList(merchantItemId: $specialItemId, shippingListId: 'SHIP001');
        $this->createTestShippingItemList(merchantItemId: $specialItemId, shippingListId: 'SHIP002');

        $results = $this->getRepository()->findByMerchantItemId($specialItemId);

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals($specialItemId, $result->getMerchantItemId());
        }
    }

    /**
     * 测试特殊字符的物流单号
     */
    public function testFindByShippingListWithSpecialCharactersReturnsCorrectResults(): void
    {
        $specialShipId = 'SHIP-001_TEST@2024';
        $this->createTestShippingItemList(merchantItemId: 'ITEM001', shippingListId: $specialShipId);
        $this->createTestShippingItemList(merchantItemId: 'ITEM002', shippingListId: $specialShipId);

        $results = $this->getRepository()->findByShippingList($specialShipId);

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $shippingList = $result->getShippingList();
            $this->assertNotNull($shippingList);
            $this->assertEquals($specialShipId, $shippingList->getTrackingNo());
        }
    }

    /**
     * 测试大量数据查询性能
     */
    public function testFindByMerchantItemIdWithLargeDatasetReturnsCorrectResults(): void
    {
        // 创建多个相同商户商品ID的记录
        for ($i = 1; $i <= 10; ++$i) {
            $this->createTestShippingItemList(
                merchantItemId: 'BULK_ITEM',
                shippingListId: "SHIP{$i}"
            );
        }

        // 创建不同商户商品ID的记录
        for ($i = 1; $i <= 5; ++$i) {
            $this->createTestShippingItemList(
                merchantItemId: "OTHER_ITEM{$i}",
                shippingListId: "SHIP_OTHER{$i}"
            );
        }

        $results = $this->getRepository()->findByMerchantItemId('BULK_ITEM');

        $this->assertCount(10, $results);
        foreach ($results as $result) {
            $this->assertEquals('BULK_ITEM', $result->getMerchantItemId());
        }
    }

    /**
     * 测试大量数据查询物流单号
     */
    public function testFindByShippingListWithLargeDatasetReturnsCorrectResults(): void
    {
        // 创建多个相同物流单号的记录
        for ($i = 1; $i <= 10; ++$i) {
            $this->createTestShippingItemList(
                merchantItemId: "ITEM{$i}",
                shippingListId: 'BULK_SHIP'
            );
        }

        // 创建不同物流单号的记录
        for ($i = 1; $i <= 5; ++$i) {
            $this->createTestShippingItemList(
                merchantItemId: "OTHER_ITEM{$i}",
                shippingListId: "OTHER_SHIP{$i}"
            );
        }

        $results = $this->getRepository()->findByShippingList('BULK_SHIP');

        $this->assertCount(10, $results);
        foreach ($results as $result) {
            $shippingList = $result->getShippingList();
            $this->assertNotNull($shippingList);
            $this->assertEquals('BULK_SHIP', $shippingList->getTrackingNo());
        }
    }

    /**
     * 测试删除操作的完整性
     */
    public function testRemoveWithFlushFalseDoesNotDeleteImmediately(): void
    {
        $shippingItemList = $this->createTestShippingItemList(merchantItemId: 'DELETE_TEST');

        $this->getRepository()->remove($shippingItemList, flush: false);

        // 在flush前，应该还能查到（因为还在EntityManager中）
        $items = $this->getRepository()->findByMerchantItemId('DELETE_TEST');
        $this->assertCount(1, $items);

        // 手动flush后应该删除
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
        $items = $this->getRepository()->findByMerchantItemId('DELETE_TEST');
        $this->assertEmpty($items);
    }

    // Standard Doctrine Repository tests

    // FindOneBy sorting tests
    public function testFindOneByWithOrderBy(): void
    {
        // 创建共享的基础数据
        $account = $this->createTestAccount();
        $user = $this->createTestUser($account);
        $orderKey1 = $this->createTestOrderKey();
        $orderKey2 = $this->createTestOrderKey();
        self::getEntityManager()->persist($account);
        self::getEntityManager()->persist($user);
        self::getEntityManager()->persist($orderKey1);
        self::getEntityManager()->persist($orderKey2);

        $combinedShippingInfo = new CombinedShippingInfo();
        $combinedShippingInfo->setAccount($account);
        $combinedShippingInfo->setPayer($user);
        $combinedShippingInfo->setOrderKey($orderKey1);
        $combinedShippingInfo->setValid(true);

        $subOrder = new SubOrderList();
        $subOrder->setCombinedShippingInfo($combinedShippingInfo);
        $subOrder->setOrderKey($orderKey2);

        $shippingList = new ShippingList();
        $shippingList->setSubOrder($subOrder);
        $shippingList->setTrackingNo('SHIP_ORDER_TEST');
        $shippingList->setExpressCompany('TEST_EXPRESS');

        self::getEntityManager()->persist($combinedShippingInfo);
        self::getEntityManager()->persist($subOrder);
        self::getEntityManager()->persist($shippingList);

        // 创建两个商品项目，共享同一个 ShippingList
        $itemA = new ShippingItemList();
        $itemA->setMerchantItemId('A_ITEM');
        $itemA->setShippingList($shippingList);

        $itemZ = new ShippingItemList();
        $itemZ->setMerchantItemId('Z_ITEM');
        $itemZ->setShippingList($shippingList);

        self::getEntityManager()->persist($itemA);
        self::getEntityManager()->persist($itemZ);
        self::getEntityManager()->flush();

        // 通过shippingList对象查找（按 merchantItemId DESC 排序，应该返回 Z_ITEM）
        $result = $this->getRepository()->findOneBy(['shippingList' => $shippingList], ['merchantItemId' => 'DESC']);

        $this->assertTrue(null === $result || $result instanceof ShippingItemList);
        if (null !== $result) {
            $this->assertEquals('Z_ITEM', $result->getMerchantItemId());
        }
    }

    protected function onTearDown(): void
    {
        // EntityManager is managed by AbstractIntegrationTestCase
    }

    protected function createNewEntity(): object
    {
        // 创建不依赖外部实体的独立ShippingItemList
        // 注意: AbstractRepositoryTestCase要求createNewEntity不要持久化实体
        $account = new Account();
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');
        $account->setName('Test Account');

        $user = new User();
        $user->setOpenId('test_user_id_' . uniqid());
        $user->setAccount($account);

        $orderKey1 = new OrderKey();
        $orderKey1->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey1->setTransactionId('test_transaction_' . uniqid());
        $orderKey1->setMchId('test_mch_' . uniqid());
        $orderKey1->setOutTradeNo('test_out_trade_' . uniqid());

        $orderKey2 = new OrderKey();
        $orderKey2->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey2->setTransactionId('test_transaction_2_' . uniqid());
        $orderKey2->setMchId('test_mch_2_' . uniqid());
        $orderKey2->setOutTradeNo('test_out_trade_2_' . uniqid());

        $combinedShippingInfo = new CombinedShippingInfo();
        $combinedShippingInfo->setAccount($account);
        $combinedShippingInfo->setPayer($user);
        $combinedShippingInfo->setOrderKey($orderKey1);
        $combinedShippingInfo->setValid(true);

        $subOrder = new SubOrderList();
        $subOrder->setCombinedShippingInfo($combinedShippingInfo);
        $subOrder->setOrderKey($orderKey2);

        $shippingList = new ShippingList();
        $shippingList->setSubOrder($subOrder);
        $shippingList->setTrackingNo('test_tracking_' . uniqid());
        $shippingList->setExpressCompany('TEST_EXPRESS');

        $shippingItemList = new ShippingItemList();
        $shippingItemList->setMerchantItemId('TEST_ITEM_' . uniqid());
        $shippingItemList->setShippingList($shippingList);

        return $shippingItemList;
    }

    protected function getRepository(): ShippingItemListRepository
    {
        $repository = self::getEntityManager()->getRepository(ShippingItemList::class);
        $this->assertInstanceOf(ShippingItemListRepository::class, $repository);

        return $repository;
    }

    private function createTestAccount(): Account
    {
        $account = new Account();
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');
        $account->setName('Test Account');

        return $account;
    }

    /**
     * 创建微信小程序用户实体（非系统BizUser）
     * 这个方法专门用于创建WechatMiniProgramAuthBundle\Entity\User实体，
     * 与系统的BizUser不同，需要设置openId、unionId等微信特有属性
     * @phpstan-ignore-next-line PreferInterfaceStubTraitRule.createTestUser
     */
    private function createTestUser(?Account $account = null): UserInterface
    {
        $user = new User();
        $user->setOpenId('test_user_id_' . uniqid());
        $user->setUnionId('test_union_id_' . uniqid());
        $user->setAvatarUrl('https://example.com/avatar.jpg');
        $user->setAccount($account ?? $this->createTestAccount());

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
}
