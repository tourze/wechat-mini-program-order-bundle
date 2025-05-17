<?php

namespace WechatMiniProgramOrderBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Request\UploadShippingInfoRequest;

class UploadShippingInfoRequestTest extends TestCase
{
    private UploadShippingInfoRequest $request;
    private ShippingInfo $shippingInfo;
    
    protected function setUp(): void
    {
        $this->request = new UploadShippingInfoRequest();
        
        // 创建模拟的 OrderKey
        $orderKey = $this->createMock(OrderKey::class);
        $orderKey->method('getOrderNumberType')->willReturn(OrderNumberType::USE_MCH_ORDER);
        $orderKey->method('getTransactionId')->willReturn('tx-123456');
        $orderKey->method('getMchId')->willReturn('mch-123456');
        $orderKey->method('getOutTradeNo')->willReturn('out-trade-123456');
        
        // 创建模拟的 UserInterface (Payer)
        $payer = $this->createMock(UserInterface::class);
        $payer->method('getOpenId')->willReturn('openid-123456');
        
        // 创建模拟的 ShippingInfo
        $this->shippingInfo = $this->createMock(ShippingInfo::class);
        $this->shippingInfo->method('getOrderKey')->willReturn($orderKey);
        $this->shippingInfo->method('getPayer')->willReturn($payer);
        $this->shippingInfo->method('getLogisticsType')->willReturn(LogisticsType::PHYSICAL_LOGISTICS);
        $this->shippingInfo->method('getTrackingNo')->willReturn('SF1234567890');
        $this->shippingInfo->method('getDeliveryCompany')->willReturn('SF Express');
        $this->shippingInfo->method('getDeliveryMobile')->willReturn('13800138000');
        
        // 设置 request 的 ShippingInfo
        $this->request->setShippingInfo($this->shippingInfo);
    }
    
    public function testGetRequestPath(): void
    {
        $this->assertSame('https://api.weixin.qq.com/user-order/orders/shippings', $this->request->getRequestPath());
    }
    
    public function testGetterAndSetterForShippingInfo(): void
    {
        $this->assertSame($this->shippingInfo, $this->request->getShippingInfo());
        
        $newShippingInfo = $this->createMock(ShippingInfo::class);
        $returnedRequest = $this->request->setShippingInfo($newShippingInfo);
        
        $this->assertSame($newShippingInfo, $this->request->getShippingInfo());
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
        
        // 验证物流类型
        $this->assertArrayHasKey('logistics_type', $json);
        $this->assertSame(LogisticsType::PHYSICAL_LOGISTICS->value, $json['logistics_type']);
        
        // 验证物流清单
        $this->assertArrayHasKey('shipping_list', $json);
        $this->assertIsArray($json['shipping_list']);
        $this->assertCount(1, $json['shipping_list']);
        
        $shipping = $json['shipping_list'][0];
        $this->assertSame('SF1234567890', $shipping['tracking_no']);
        $this->assertSame('SF Express', $shipping['express_company']);
        $this->assertSame(1, $shipping['delivery_mode']);
        
        // 验证支付者信息
        $this->assertArrayHasKey('payer', $json);
        $this->assertSame('openid-123456', $json['payer']['openid']);
        
        // 验证收件人信息
        $this->assertArrayHasKey('receiver_info', $json);
        $this->assertSame('13800138000', $json['receiver_info']['receiver_contact']);
        
        // 验证上传时间
        $this->assertArrayHasKey('upload_time', $json);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[\+\-]\d{2}:\d{2}$/', $json['upload_time']);
    }
    
    public function testGetterAndSetterForAccount(): void
    {
        $account = $this->createMock(Account::class);
        $this->request->setAccount($account);
        
        $this->assertSame($account, $this->request->getAccount());
    }
}
