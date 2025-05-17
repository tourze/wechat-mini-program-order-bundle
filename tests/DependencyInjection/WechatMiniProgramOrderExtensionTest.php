<?php

namespace WechatMiniProgramOrderBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WechatMiniProgramOrderBundle\DependencyInjection\WechatMiniProgramOrderExtension;
use WechatMiniProgramOrderBundle\EventSubscriber\ShippingInfoListener;
use WechatMiniProgramOrderBundle\EventSubscriber\ShoppingInfoListener;
use WechatMiniProgramOrderBundle\Repository\ShippingInfoRepository;
use WechatMiniProgramOrderBundle\Repository\ShoppingInfoRepository;

class WechatMiniProgramOrderExtensionTest extends TestCase
{
    private ContainerBuilder $container;
    private WechatMiniProgramOrderExtension $extension;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->extension = new WechatMiniProgramOrderExtension();
    }

    public function testServicesAreRegistered(): void
    {
        // 加载扩展
        $this->extension->load([], $this->container);
        
        // 验证事件监听器注册 - 使用 hasDefinition 检查是否有服务定义
        $this->assertTrue($this->container->hasDefinition(ShoppingInfoListener::class));
        $this->assertTrue($this->container->hasDefinition(ShippingInfoListener::class));
        
        // 验证仓库注册
        $this->assertTrue($this->container->hasDefinition(ShoppingInfoRepository::class));
        $this->assertTrue($this->container->hasDefinition(ShippingInfoRepository::class));
    }
    
    public function testServiceDefinitionsAreCorrect(): void
    {
        // 加载扩展
        $this->extension->load([], $this->container);
        
        // 验证事件监听器定义
        $shoppingInfoListenerDef = $this->container->getDefinition(ShoppingInfoListener::class);
        $this->assertTrue($shoppingInfoListenerDef->isAutowired());
        $this->assertTrue($shoppingInfoListenerDef->isAutoconfigured());
        
        $shippingInfoListenerDef = $this->container->getDefinition(ShippingInfoListener::class);
        $this->assertTrue($shippingInfoListenerDef->isAutowired());
        $this->assertTrue($shippingInfoListenerDef->isAutoconfigured());
    }
}
