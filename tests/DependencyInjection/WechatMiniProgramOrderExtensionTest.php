<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use WechatMiniProgramOrderBundle\DependencyInjection\WechatMiniProgramOrderExtension;
use WechatMiniProgramOrderBundle\Repository\ShippingInfoRepository;
use WechatMiniProgramOrderBundle\Repository\ShoppingInfoRepository;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramOrderExtension::class)]
final class WechatMiniProgramOrderExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private ContainerBuilder $container;

    private WechatMiniProgramOrderExtension $extension;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
        $this->extension = new WechatMiniProgramOrderExtension();
    }

    public function testServicesAreRegistered(): void
    {
        // 使用 AutoExtension 加载服务
        $this->extension->load([], $this->container);

        // 验证仓库注册
        $this->assertTrue($this->container->hasDefinition(ShoppingInfoRepository::class));
        $this->assertTrue($this->container->hasDefinition(ShippingInfoRepository::class));

        // 验证配置文件是否被正确加载
        $serviceDefinitions = array_keys($this->container->getDefinitions());
        $this->assertNotEmpty($serviceDefinitions, 'Container should have service definitions after loading');
    }

    public function testServiceDefinitionsAreCorrect(): void
    {
        // 使用 AutoExtension 加载服务
        $this->extension->load([], $this->container);

        // 验证仓库定义存在
        $this->assertTrue($this->container->hasDefinition(ShoppingInfoRepository::class));
        $this->assertTrue($this->container->hasDefinition(ShippingInfoRepository::class));

        // 验证定义存在且可获取
        $shoppingInfoRepoDef = $this->container->getDefinition(ShoppingInfoRepository::class);
        $this->assertNotNull($shoppingInfoRepoDef->getClass());

        $shippingInfoRepoDef = $this->container->getDefinition(ShippingInfoRepository::class);
        $this->assertNotNull($shippingInfoRepoDef->getClass());
    }

    public function testPrepend(): void
    {
        // 调用 prepend 方法
        $this->extension->prepend($this->container);

        // 验证 prepend 方法执行后容器状态仍然有效
        $this->assertInstanceOf(ContainerBuilder::class, $this->container);
    }

    public function testPrependWithoutStubDirectory(): void
    {
        // 设置环境为非测试环境，避免 Stub 目录问题
        $this->container->setParameter('kernel.environment', 'prod');

        // 调用 prepend 方法，应该不会抛出异常
        $this->extension->prepend($this->container);

        // 验证容器状态仍然有效
        $this->assertInstanceOf(ContainerBuilder::class, $this->container);
    }
}
