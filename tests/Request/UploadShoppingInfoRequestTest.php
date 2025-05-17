<?php

namespace WechatMiniProgramOrderBundle\Tests\Request;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Request\UploadShoppingInfoRequest;

class UploadShoppingInfoRequestTest extends TestCase
{
    private UploadShoppingInfoRequest $request;
    private ShoppingInfo $shoppingInfo;
    
    protected function setUp(): void
    {
        $this->request = new UploadShoppingInfoRequest();
        
        // 创建模拟的 OrderKey
        $orderKey = $this->createMock(OrderKey::class);
        $orderKey->method('getOrderNumberType')->willReturn(OrderNumberType::USE_MCH_ORDER);
        $orderKey->method('getTransactionId')->willReturn('tx-123456');
        $orderKey->method('getMchId')->willReturn('mch-123456');
        $orderKey->method('getOutTradeNo')->willReturn('out-trade-123456');
        
        // 创建模拟的 UserInterface (Payer)
        $payer = $this->createMock(UserInterface::class);
        $payer->method('getOpenId')->willReturn('openid-123456');
        
        // 创建模拟的 ShoppingItemList
        $item1 = $this->createMock(ShoppingItemList::class);
        $item1->method('getItemName')->willReturn('商品1');
        $item1->method('getItemCount')->willReturn(2);
        $item1->method('getItemPrice')->willReturn('100.00');
        $item1->method('getItemAmount')->willReturn('200.00');
        $item1->method('getMerchantItemId')->willReturn('item-123');
        
        $item2 = $this->createMock(ShoppingItemList::class);
        $item2->method('getItemName')->willReturn('商品2');
        $item2->method('getItemCount')->willReturn(1);
        $item2->method('getItemPrice')->willReturn('50.00');
        $item2->method('getItemAmount')->willReturn('50.00');
        $item2->method('getMerchantItemId')->willReturn('item-456');
        
        $itemList = new ArrayCollection([$item1, $item2]);
        
        // 创建模拟的 ShoppingInfo
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
        
        $newShoppingInfo = $this->createMock(ShoppingInfo::class);
        $returnedRequest = $this->request->setShoppingInfo($newShoppingInfo);
        
        $this->assertSame($newShoppingInfo, $this->request->getShoppingInfo());
        $this->assertSame($this->request, $returnedRequest, '设置方法应返回自身以支持链式调用');
    }
    
    public function testGetRequestOptions(): void
    {
        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        
        $json = $options['json'];
        
        // 验证 order_key 结构
        $this->assertArrayHasKey('order_key', $json);
        $this->assertSame(OrderNumberType::USE_MCH_ORDER->value, $json['order_key']['order_number_type']);
        $this->assertSame('tx-123456', $json['order_key']['transaction_id']);
        $this->assertSame('mch-123456', $json['order_key']['mch_id']);
        $this->assertSame('out-trade-123456', $json['order_key']['out_trade_no']);
        
        // 验证 payer 结构
        $this->assertArrayHasKey('payer', $json);
        $this->assertSame('openid-123456', $json['payer']['openid']);
        
        // 验证 order_detail 结构
        $this->assertArrayHasKey('order_detail', $json);
        $this->assertSame(OrderDetailType::MINI_PROGRAM->value, $json['order_detail']['order_detail_type']);
        $this->assertSame('/pages/order/detail', $json['order_detail']['order_detail_path']);
        
        // 验证物流类型
        $this->assertArrayHasKey('logistics_type', $json);
        $this->assertSame(LogisticsType::VIRTUAL_GOODS->value, $json['logistics_type']);
        
        // 验证订单项目列表
        $this->assertArrayHasKey('order_list', $json);
        $this->assertCount(2, $json['order_list']);
        
        // 验证第一个订单项
        $item1 = $json['order_list'][0];
        $this->assertSame('商品1', $item1['item_name']);
        $this->assertSame(2, $item1['item_count']);
        $this->assertSame('100.00', $item1['item_price']);
        $this->assertSame('200.00', $item1['item_amount']);
        $this->assertSame('item-123', $item1['merchant_item_id']);
        
        // 验证第二个订单项
        $item2 = $json['order_list'][1];
        $this->assertSame('商品2', $item2['item_name']);
        $this->assertSame(1, $item2['item_count']);
        $this->assertSame('50.00', $item2['item_price']);
        $this->assertSame('50.00', $item2['item_amount']);
        $this->assertSame('item-456', $item2['merchant_item_id']);
    }
    
    public function testGetterAndSetterForAccount(): void
    {
        $account = $this->createMock(Account::class);
        $this->request->setAccount($account);
        
        $this->assertSame($account, $this->request->getAccount());
    }
}
