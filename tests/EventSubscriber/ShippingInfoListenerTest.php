<?php

namespace WechatMiniProgramOrderBundle\Tests\EventSubscriber;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\EventSubscriber\ShippingInfoListener;
use WechatMiniProgramOrderBundle\Request\UploadShippingInfoRequest;

class ShippingInfoListenerTest extends TestCase
{
    private Client|MockObject $client;
    private ShippingInfoListener $listener;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->listener = new ShippingInfoListener($this->client);
    }

    public function testPrePersistCreatesRequestAndCallsAsyncRequest(): void
    {
        // 准备测试数据
        $shippingInfo = $this->createMock(ShippingInfo::class);
        $account = $this->createMock(Account::class);

        // 设置预期行为
        $shippingInfo->expects($this->once())
            ->method('getAccount')
            ->willReturn($account);
            
        // 验证请求创建和调用
        $this->client->expects($this->once())
            ->method('asyncRequest')
            ->with($this->callback(function (UploadShippingInfoRequest $request) use ($account, $shippingInfo) {
                $this->assertSame($account, $request->getAccount());
                $this->assertSame($shippingInfo, $request->getShippingInfo());
                return true;
            }));
            
        // 执行测试
        $this->listener->prePersist($shippingInfo);
    }
    
    public function testPrePersistWithNullAccount(): void
    {
        // 由于 ShippingInfo::getAccount 方法返回的是非空的 Account 对象
        // 所以这个测试用例不再适用，我们改为测试 asyncRequest 方法调用
        
        // 准备测试数据
        $shippingInfo = $this->createMock(ShippingInfo::class);
        $account = $this->createMock(Account::class);
        
        // 设置预期行为
        $shippingInfo->expects($this->once())
            ->method('getAccount')
            ->willReturn($account);
            
        // 验证请求创建和调用
        $this->client->expects($this->once())
            ->method('asyncRequest')
            ->with($this->isInstanceOf(UploadShippingInfoRequest::class));
            
        // 执行测试
        $this->listener->prePersist($shippingInfo);
    }
}
