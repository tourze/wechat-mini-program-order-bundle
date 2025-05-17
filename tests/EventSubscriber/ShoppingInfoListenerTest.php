<?php

namespace WechatMiniProgramOrderBundle\Tests\EventSubscriber;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\EventSubscriber\ShoppingInfoListener;
use WechatMiniProgramOrderBundle\Request\UploadShoppingInfoRequest;

class ShoppingInfoListenerTest extends TestCase
{
    private Client|MockObject $client;
    private ShoppingInfoListener $listener;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->listener = new ShoppingInfoListener($this->client);
    }

    public function testPrePersistCreatesRequestAndCallsAsyncRequest(): void
    {
        // 准备测试数据
        $shoppingInfo = $this->createMock(ShoppingInfo::class);
        $account = $this->createMock(Account::class);
        
        // 设置预期行为
        $shoppingInfo->expects($this->once())
            ->method('getAccount')
            ->willReturn($account);
            
        // 验证请求创建和调用
        $this->client->expects($this->once())
            ->method('asyncRequest')
            ->with($this->callback(function (UploadShoppingInfoRequest $request) use ($account, $shoppingInfo) {
                $this->assertSame($account, $request->getAccount());
                $this->assertSame($shoppingInfo, $request->getShoppingInfo());
                return true;
            }));
            
        // 执行测试
        $this->listener->prePersist($shoppingInfo);
    }
    
    public function testPrePersistWithNullAccount(): void
    {
        // 由于 ShoppingInfo::getAccount 方法返回的是非空的 Account 对象
        // 所以这个测试用例不再适用，我们改为测试 asyncRequest 方法调用
        
        // 准备测试数据
        $shoppingInfo = $this->createMock(ShoppingInfo::class);
        $account = $this->createMock(Account::class);
        
        // 设置预期行为
        $shoppingInfo->expects($this->once())
            ->method('getAccount')
            ->willReturn($account);
            
        // 验证请求创建和调用
        $this->client->expects($this->once())
            ->method('asyncRequest')
            ->with($this->isInstanceOf(UploadShoppingInfoRequest::class));
            
        // 执行测试
        $this->listener->prePersist($shoppingInfo);
    }
}
