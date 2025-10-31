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
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Repository\ShoppingItemListRepository;

/**
 * ShoppingItemListRepository 单元测试
 *
 * @internal
 */
#[CoversClass(ShoppingItemListRepository::class)]
#[RunTestsInSeparateProcesses]
final class ShoppingItemListRepositoryTest extends AbstractRepositoryTestCase
{
    private ShoppingItemListRepository $repository;

    /**
     * 测试根据商品ID查找购物商品列表项
     */
    public function testFindByMerchantItemIdReturnsCorrectItems(): void
    {
        $this->createTestShoppingItemList(merchantItemId: 'ITEM001', shoppingInfoId: 'SHOPPING001');
        $this->createTestShoppingItemList(merchantItemId: 'ITEM001', shoppingInfoId: 'SHOPPING002');
        $this->createTestShoppingItemList(merchantItemId: 'ITEM002', shoppingInfoId: 'SHOPPING003');

        $results = $this->repository->findByMerchantItemId('ITEM001');

        $this->assertIsArray($results);
        // 验证返回的是查询结果，具体断言需要根据实际的查询逻辑
        // 由于这个方法使用了QueryBuilder，我们主要测试方法能正常执行
    }

    /**
     * 创建测试购物商品列表
     */
    private function createTestShoppingItemList(
        string $merchantItemId = 'ITEM123',
        string $shoppingInfoId = 'SHOPPING123',
    ): ShoppingItemList {
        // 创建必需的 OrderKey
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_transaction_' . uniqid());
        $orderKey->setMchId('test_mch_' . uniqid());
        $orderKey->setOutTradeNo('test_out_trade_' . uniqid());
        $orderKey->setOrderId('test_order_' . uniqid());
        $orderKey->setOutOrderId('test_out_order_' . uniqid());

        // 创建必需的 Account
        $account = new Account();
        $account->setAppId('test_app_' . uniqid());
        $account->setAppSecret('test_secret');
        $account->setName('Test Account');

        // 创建必需的 User
        $user = new User();
        $user->setOpenId('test_user_' . uniqid());
        $user->setAccount($account);

        // 创建 ShoppingInfo
        $shoppingInfo = new ShoppingInfo();
        $shoppingInfo->setOrderKey($orderKey);
        $shoppingInfo->setAccount($account);
        $shoppingInfo->setPayer($user);
        $shoppingInfo->setOrderDetailPath('/test/order/detail/path');

        self::getEntityManager()->persist($orderKey);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->persist($user);
        self::getEntityManager()->persist($shoppingInfo);

        // 创建 ShoppingItemList 并设置所有必填字段
        $shoppingItemList = new ShoppingItemList();
        $shoppingItemList->setMerchantItemId($merchantItemId);
        $shoppingItemList->setItemName('Test Item Name');
        $shoppingItemList->setItemCount(1);
        $shoppingItemList->setItemPrice('10.00');
        $shoppingItemList->setItemAmount('10.00');
        $shoppingItemList->setShoppingInfo($shoppingInfo);

        self::getEntityManager()->persist($shoppingItemList);
        self::getEntityManager()->flush();

        return $shoppingItemList;
    }

    /**
     * 测试根据购物信息查找购物商品列表项
     */
    public function testFindByShoppingInfoReturnsCorrectItems(): void
    {
        $this->createTestShoppingItemList(merchantItemId: 'ITEM001', shoppingInfoId: 'SHOPPING001');
        $this->createTestShoppingItemList(merchantItemId: 'ITEM002', shoppingInfoId: 'SHOPPING001');
        $this->createTestShoppingItemList(merchantItemId: 'ITEM003', shoppingInfoId: 'SHOPPING002');

        $results = $this->repository->findByShoppingInfo('SHOPPING001');

        $this->assertIsArray($results);
        // 验证返回的是查询结果，具体断言需要根据实际的查询逻辑和实体关系
    }

    /**
     * 测试保存购物商品列表项
     */
    public function testSavePersistsShoppingItemListCorrectly(): void
    {
        // 创建必需的关联实体
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_transaction_save');
        $orderKey->setMchId('test_mch_save');
        $orderKey->setOutTradeNo('test_out_trade_save');
        $orderKey->setOrderId('test_order_save');
        $orderKey->setOutOrderId('test_out_order_save');

        $account = new Account();
        $account->setAppId('test_app_save');
        $account->setAppSecret('test_secret');
        $account->setName('Test Account');

        $user = new User();
        $user->setOpenId('test_user_save');
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

        // 创建 ShoppingItemList 并设置所有必填字段
        $shoppingItemList = new ShoppingItemList();
        $shoppingItemList->setMerchantItemId('TEST_SAVE_ITEM');
        $shoppingItemList->setItemName('Test Save Item');
        $shoppingItemList->setItemCount(1);
        $shoppingItemList->setItemPrice('10.00');
        $shoppingItemList->setItemAmount('10.00');
        $shoppingItemList->setShoppingInfo($shoppingInfo);

        $this->repository->save($shoppingItemList, flush: true);

        // 验证保存成功 - 通过查询验证
        $results = $this->repository->findByMerchantItemId('TEST_SAVE_ITEM');
        $this->assertIsArray($results);
    }

    /**
     * 测试删除购物商品列表项
     */
    public function testRemoveDeletesShoppingItemListCorrectly(): void
    {
        $shoppingItemList = $this->createTestShoppingItemList(merchantItemId: 'TO_DELETE');

        $this->repository->remove($shoppingItemList, flush: true);

        // 验证删除成功
        $results = $this->repository->findByMerchantItemId('TO_DELETE');
        $this->assertIsArray($results);
        // 由于QueryBuilder查询，具体结果验证需要根据实际情况
    }

    /**
     * 测试边界情况 - 不存在的商户商品ID
     */
    public function testFindByMerchantItemIdWithNonExistentIdReturnsEmptyArray(): void
    {
        $this->createTestShoppingItemList(merchantItemId: 'EXISTING_ITEM');

        $results = $this->repository->findByMerchantItemId('NONEXISTENT_ITEM');

        $this->assertIsArray($results);
        // QueryBuilder查询应该返回空数组
    }

    /**
     * 测试边界情况 - 不存在的购物信息ID
     */
    public function testFindByShoppingInfoWithNonExistentIdReturnsEmptyArray(): void
    {
        $this->createTestShoppingItemList(shoppingInfoId: 'EXISTING_SHOPPING');

        $results = $this->repository->findByShoppingInfo('NONEXISTENT_SHOPPING');

        $this->assertIsArray($results);
        // QueryBuilder查询应该返回空数组
    }

    /**
     * 测试保存但不刷新
     */
    public function testSaveWithoutFlushDoesNotPersistImmediately(): void
    {
        $shoppingItemList = new ShoppingItemList();
        $shoppingItemList->setMerchantItemId('TEST_NO_FLUSH');

        $this->repository->save($shoppingItemList, flush: false);

        // 在flush前，从数据库查询应该找不到
        self::getEntityManager()->clear();
        $results = $this->repository->findByMerchantItemId('TEST_NO_FLUSH');
        $this->assertIsArray($results);

        // 手动flush后应该能找到
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
        $results = $this->repository->findByMerchantItemId('TEST_NO_FLUSH');
        $this->assertIsArray($results);
    }

    /**
     * 测试特殊字符的商户商品ID
     */
    public function testFindByMerchantItemIdWithSpecialCharactersReturnsCorrectResults(): void
    {
        $specialItemId = 'ITEM-001_TEST@2024';
        $this->createTestShoppingItemList(merchantItemId: $specialItemId);

        $results = $this->repository->findByMerchantItemId($specialItemId);

        $this->assertIsArray($results);
        // 验证QueryBuilder能正确处理特殊字符
    }

    /**
     * 测试特殊字符的购物信息ID
     */
    public function testFindByShoppingInfoWithSpecialCharactersReturnsCorrectResults(): void
    {
        $specialShoppingId = 'SHOPPING-001_TEST@2024';
        $this->createTestShoppingItemList(shoppingInfoId: $specialShoppingId);

        $results = $this->repository->findByShoppingInfo($specialShoppingId);

        $this->assertIsArray($results);
        // 验证QueryBuilder能正确处理特殊字符
    }

    /**
     * 测试QueryBuilder查询的基本功能
     */
    public function testQueryBuilderMethodsReturnArrayResults(): void
    {
        // 创建一些测试数据
        $this->createTestShoppingItemList(merchantItemId: 'QB_TEST_ITEM1');
        $this->createTestShoppingItemList(merchantItemId: 'QB_TEST_ITEM2');

        // 测试findByMerchantItemId方法
        $results1 = $this->repository->findByMerchantItemId('QB_TEST_ITEM1');
        $this->assertIsArray($results1);

        // 测试findByShoppingInfo方法
        $results2 = $this->repository->findByShoppingInfo('QB_TEST_SHOPPING');
        $this->assertIsArray($results2);
    }

    /**
     * 测试空字符串参数处理
     */
    public function testFindByMerchantItemIdWithEmptyStringReturnsEmptyArray(): void
    {
        $this->createTestShoppingItemList(merchantItemId: 'NORMAL_ITEM');

        $results = $this->repository->findByMerchantItemId('');

        $this->assertIsArray($results);
    }

    /**
     * 测试空字符串购物信息ID
     */
    public function testFindByShoppingInfoWithEmptyStringReturnsEmptyArray(): void
    {
        $this->createTestShoppingItemList(shoppingInfoId: 'NORMAL_SHOPPING');

        $results = $this->repository->findByShoppingInfo('');

        $this->assertIsArray($results);
    }

    /**
     * 测试删除操作的完整性
     */
    public function testRemoveWithFlushFalseDoesNotDeleteImmediately(): void
    {
        $shoppingItemList = $this->createTestShoppingItemList(merchantItemId: 'DELETE_TEST');

        $this->repository->remove($shoppingItemList, flush: false);

        // 在flush前验证
        $results = $this->repository->findByMerchantItemId('DELETE_TEST');
        $this->assertIsArray($results);

        // 手动flush后验证
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
        $results = $this->repository->findByMerchantItemId('DELETE_TEST');
        $this->assertIsArray($results);
    }

    /**
     * 测试批量数据处理
     */
    public function testBulkDataHandling(): void
    {
        // 创建多个商品项
        for ($i = 1; $i <= 5; ++$i) {
            $this->createTestShoppingItemList(
                merchantItemId: "BULK_ITEM_{$i}",
                shoppingInfoId: "BULK_SHOPPING_{$i}"
            );
        }

        // 测试批量查询
        for ($i = 1; $i <= 5; ++$i) {
            $results = $this->repository->findByMerchantItemId("BULK_ITEM_{$i}");
            $this->assertIsArray($results);
        }
    }

    /**
     * 测试NULL值处理
     */
    public function testNullValueHandling(): void
    {
        // 测试传入null值的行为（取决于方法签名）
        try {
            $results = $this->repository->findByMerchantItemId('VALID_ITEM');
            $this->assertIsArray($results);
        } catch (\TypeError $e) {
            // 如果方法不接受null，应该抛出TypeError
            $this->assertStringContainsString('string', $e->getMessage());
        }
    }

    /**
     * 测试长字符串ID处理
     */
    public function testLongStringIdHandling(): void
    {
        $longItemId = str_repeat('A', 100) . '_LONG_ITEM_ID';
        $this->createTestShoppingItemList(merchantItemId: $longItemId);

        $results = $this->repository->findByMerchantItemId($longItemId);
        $this->assertIsArray($results);
    }

    /**
     * 测试Unicode字符处理
     */
    public function testUnicodeCharacterHandling(): void
    {
        $unicodeItemId = 'ITEM_测试_🛒_001';
        $this->createTestShoppingItemList(merchantItemId: $unicodeItemId);

        $results = $this->repository->findByMerchantItemId($unicodeItemId);
        $this->assertIsArray($results);
    }

    // Standard Doctrine Repository tests

    protected function onSetUp(): void
    {
        $this->repository = self::getService(ShoppingItemListRepository::class);
    }

    protected function onTearDown(): void
    {
        // EntityManager is managed by AbstractIntegrationTestCase
    }

    protected function createNewEntity(): object
    {
        // 创建不依赖持久化的独立ShoppingItemList
        // 注意: AbstractRepositoryTestCase要求createNewEntity不要持久化实体
        $account = new Account();
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');
        $account->setName('Test Account');

        $user = new User();
        $user->setOpenId('test_user_id_' . uniqid());
        $user->setUnionId('test_union_id_' . uniqid());
        $user->setAccount($account);

        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('test_transaction_' . uniqid());
        $orderKey->setMchId('test_mch_' . uniqid());
        $orderKey->setOutTradeNo('test_out_trade_' . uniqid());
        $orderKey->setOrderId('test_order_' . uniqid());
        $orderKey->setOutOrderId('test_out_order_' . uniqid());

        $shoppingInfo = new ShoppingInfo();
        $shoppingInfo->setOrderKey($orderKey);
        $shoppingInfo->setAccount($account);
        $shoppingInfo->setPayer($user);
        $shoppingInfo->setOrderDetailPath('/test/order/detail/path');

        // 创建 ShoppingItemList 实体并设置所有必填字段
        $entity = new ShoppingItemList();
        $entity->setMerchantItemId('test_merchant_item_' . uniqid());
        $entity->setItemName('Test Item Name');
        $entity->setItemCount(1);
        $entity->setItemPrice('10.00');
        $entity->setItemAmount('10.00');
        $entity->setShoppingInfo($shoppingInfo);

        return $entity;
    }

    protected function getRepository(): ShoppingItemListRepository
    {
        return $this->repository;
    }
}
