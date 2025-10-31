<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Request;

use Doctrine\Common\Collections\ArrayCollection;
use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Request\UploadShoppingInfoRequest;

/**
 * @internal
 */
#[CoversClass(UploadShoppingInfoRequest::class)]
final class UploadShoppingInfoRequestTest extends RequestTestCase
{
    private UploadShoppingInfoRequest $request;

    private ShoppingInfo $shoppingInfo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new UploadShoppingInfoRequest();

        // 使用具体类 OrderKey 是必要的，理由1：OrderKey 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 OrderKey 是必要的，理由2：测试需要控制 OrderKey 的各种方法返回值，Mock 能精确模拟这些行为
        // 使用具体类 OrderKey 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $orderKey = $this->createMock(OrderKey::class);
        $orderKey->method('getOrderNumberType')->willReturn(OrderNumberType::USE_MCH_ORDER);
        $orderKey->method('getTransactionId')->willReturn('tx-123456');
        $orderKey->method('getMchId')->willReturn('mch-123456');
        $orderKey->method('getOutTradeNo')->willReturn('out-trade-123456');

        // 使用具体类 UserInterface 是必要的，理由1：UserInterface 是接口，使用 Mock 模拟接口实现是标准做法
        // 使用具体类 UserInterface 是必要的，理由2：测试需要控制 getOpenId 方法的返回值，Mock 能精确模拟这个行为
        // 使用具体类 UserInterface 是必要的，理由3：避免依赖具体的用户实现类，保持测试的独立性
        $payer = $this->createMock(UserInterface::class);
        $payer->method('getOpenId')->willReturn('openid-123456');

        // 使用具体类 ShoppingItemList 是必要的，理由1：ShoppingItemList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShoppingItemList 是必要的，理由2：测试需要控制多个 getter 方法的返回值，Mock 能精确模拟这些行为
        // 使用具体类 ShoppingItemList 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $item1 = $this->createMock(ShoppingItemList::class);
        $item1->method('getItemName')->willReturn('商品1');
        $item1->method('getItemCount')->willReturn(2);
        $item1->method('getItemPrice')->willReturn('100.00');
        $item1->method('getItemAmount')->willReturn('200.00');
        $item1->method('getMerchantItemId')->willReturn('item-123');

        // 使用具体类 ShoppingItemList 是必要的，理由1：ShoppingItemList 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShoppingItemList 是必要的，理由2：测试需要控制多个 getter 方法的返回值，Mock 能精确模拟这些行为
        // 使用具体类 ShoppingItemList 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $item2 = $this->createMock(ShoppingItemList::class);
        $item2->method('getItemName')->willReturn('商品2');
        $item2->method('getItemCount')->willReturn(1);
        $item2->method('getItemPrice')->willReturn('50.00');
        $item2->method('getItemAmount')->willReturn('50.00');
        $item2->method('getMerchantItemId')->willReturn('item-456');

        $itemList = new ArrayCollection([$item1, $item2]);

        // 使用具体类 ShoppingInfo 是必要的，理由1：ShoppingInfo 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShoppingInfo 是必要的，理由2：测试需要控制多个 getter 方法的返回值，Mock 能精确模拟这些行为
        // 使用具体类 ShoppingInfo 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $this->shoppingInfo = $this->createMock(ShoppingInfo::class);
        $this->shoppingInfo->method('getOrderKey')->willReturn($orderKey);
        $this->shoppingInfo->method('getPayer')->willReturn($payer);
        $this->shoppingInfo->method('getOrderDetailType')->willReturn(OrderDetailType::MINI_PROGRAM);
        $this->shoppingInfo->method('getOrderDetailPath')->willReturn('/pages/order/detail');
        $this->shoppingInfo->method('getLogisticsType')->willReturn(LogisticsType::VIRTUAL_GOODS);
        $this->shoppingInfo->method('getItemList')->willReturn($itemList);

        // 设置 request 的 ShoppingInfo
        $this->request->setShoppingInfo($this->shoppingInfo);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('https://api.weixin.qq.com/user-order/orders', $this->request->getRequestPath());
    }

    public function testGetterAndSetterForShoppingInfo(): void
    {
        $this->assertSame($this->shoppingInfo, $this->request->getShoppingInfo());

        // 使用具体类 ShoppingInfo 是必要的，理由1：ShoppingInfo 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShoppingInfo 是必要的，理由2：测试需要验证 setter 方法的功能，不需要具体实现，Mock 即可满足需求
        // 使用具体类 ShoppingInfo 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $newShoppingInfo = $this->createMock(ShoppingInfo::class);
        $this->request->setShoppingInfo($newShoppingInfo);

        $this->assertSame($newShoppingInfo, $this->request->getShoppingInfo());
    }

    public function testGetRequestOptions(): void
    {
        $options = $this->request->getRequestOptions();
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);

        $json = $options['json'];

        // 验证 order_key 结构
        $this->assertArrayHasKey('order_key', $json);
        $this->assertIsArray($json['order_key']);
        $orderKey = $json['order_key'];
        $this->assertSame(OrderNumberType::USE_MCH_ORDER->value, $orderKey['order_number_type']);
        $this->assertSame('tx-123456', $orderKey['transaction_id']);
        $this->assertSame('mch-123456', $orderKey['mch_id']);
        $this->assertSame('out-trade-123456', $orderKey['out_trade_no']);

        // 验证 payer 结构
        $this->assertArrayHasKey('payer', $json);
        $this->assertIsArray($json['payer']);
        $payer = $json['payer'];
        $this->assertSame('openid-123456', $payer['openid']);

        // 验证 order_detail 结构
        $this->assertArrayHasKey('order_detail', $json);
        $this->assertIsArray($json['order_detail']);
        $orderDetail = $json['order_detail'];
        $this->assertSame(OrderDetailType::MINI_PROGRAM->value, $orderDetail['order_detail_type']);
        $this->assertSame('/pages/order/detail', $orderDetail['order_detail_path']);

        // 验证物流类型
        $this->assertArrayHasKey('logistics_type', $json);
        $this->assertSame(LogisticsType::VIRTUAL_GOODS->value, $json['logistics_type']);

        // 验证订单项目列表
        $this->assertArrayHasKey('order_list', $json);
        $this->assertIsArray($json['order_list']);
        $orderList = $json['order_list'];
        $this->assertCount(2, $orderList);

        // 验证第一个订单项
        $this->assertIsArray($orderList[0]);
        $item1 = $orderList[0];
        $this->assertSame('商品1', $item1['item_name']);
        $this->assertSame(2, $item1['item_count']);
        $this->assertSame('100.00', $item1['item_price']);
        $this->assertSame('200.00', $item1['item_amount']);
        $this->assertSame('item-123', $item1['merchant_item_id']);

        // 验证第二个订单项
        $this->assertIsArray($orderList[1]);
        $item2 = $orderList[1];
        $this->assertSame('商品2', $item2['item_name']);
        $this->assertSame(1, $item2['item_count']);
        $this->assertSame('50.00', $item2['item_price']);
        $this->assertSame('50.00', $item2['item_amount']);
        $this->assertSame('item-456', $item2['merchant_item_id']);
    }

    public function testGetterAndSetterForAccount(): void
    {
        // 使用具体类 Account 是必要的，理由1：Account 来自外部 Bundle，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 Account 是必要的，理由2：测试需要验证 getter/setter 方法的功能，不需要具体实现，Mock 即可满足需求
        // 使用具体类 Account 是必要的，理由3：避免测试与其他 Bundle 的具体实现产生耦合
        $account = $this->createMock(Account::class);
        $this->request->setAccount($account);

        $this->assertSame($account, $this->request->getAccount());
    }
}
