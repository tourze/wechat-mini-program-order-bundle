<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Request\UploadShippingInfoRequest;

/**
 * @internal
 */
#[CoversClass(UploadShippingInfoRequest::class)]
final class UploadShippingInfoRequestTest extends RequestTestCase
{
    private UploadShippingInfoRequest $request;

    private ShippingInfo $shippingInfo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new UploadShippingInfoRequest();

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

        // 使用具体类 ShippingInfo 是必要的，理由1：ShippingInfo 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShippingInfo 是必要的，理由2：测试需要控制多个 getter 方法的返回值，Mock 能精确模拟这些行为
        // 使用具体类 ShippingInfo 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
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

        // 使用具体类 ShippingInfo 是必要的，理由1：ShippingInfo 是 Doctrine 实体，没有对应的接口，创建接口会增加不必要的复杂性
        // 使用具体类 ShippingInfo 是必要的，理由2：测试需要验证 setter 方法的功能，不需要具体实现，Mock 即可满足需求
        // 使用具体类 ShippingInfo 是必要的，理由3：单元测试需要隔离外部依赖，使用 Mock 是标准做法
        $newShippingInfo = $this->createMock(ShippingInfo::class);
        $this->request->setShippingInfo($newShippingInfo);

        $this->assertSame($newShippingInfo, $this->request->getShippingInfo());
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

        // 验证物流类型
        $this->assertArrayHasKey('logistics_type', $json);
        $this->assertSame(LogisticsType::PHYSICAL_LOGISTICS->value, $json['logistics_type']);

        // 验证物流清单
        $this->assertArrayHasKey('shipping_list', $json);
        $this->assertIsArray($json['shipping_list']);
        $this->assertCount(1, $json['shipping_list']);

        $shippingList = $json['shipping_list'];
        $this->assertIsArray($shippingList[0]);
        $shipping = $shippingList[0];
        $this->assertSame('SF1234567890', $shipping['tracking_no']);
        $this->assertSame('SF Express', $shipping['express_company']);
        $this->assertSame(1, $shipping['delivery_mode']);

        // 验证支付者信息
        $this->assertArrayHasKey('payer', $json);
        $this->assertIsArray($json['payer']);
        $payer = $json['payer'];
        $this->assertSame('openid-123456', $payer['openid']);

        // 验证收件人信息
        $this->assertArrayHasKey('receiver_info', $json);
        $this->assertIsArray($json['receiver_info']);
        $receiverInfo = $json['receiver_info'];
        $this->assertSame('13800138000', $receiverInfo['receiver_contact']);

        // 验证上传时间
        $this->assertArrayHasKey('upload_time', $json);
        $this->assertIsString($json['upload_time']);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[\+\-]\d{2}:\d{2}$/', $json['upload_time']);
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
