<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Tourze\SymfonyDependencyServiceLoader\AutoExtension;

class WechatMiniProgramOrderExtension extends AutoExtension implements PrependExtensionInterface
{
    protected function getConfigDir(): string
    {
        return __DIR__ . '/../Resources/config';
    }

    public function prepend(ContainerBuilder $container): void
    {
        // 检查是否在测试环境中
        if ($container->hasParameter('kernel.environment')) {
            $environment = $container->getParameter('kernel.environment');

            if ('test' === $environment) {
                // 在测试环境中注册 Stub 实体映射
                $stubDir = __DIR__ . '/../../tests/Stub';
                // 只有在 Stub 目录存在时才配置
                if (is_dir($stubDir)) {
                    $container->prependExtensionConfig('doctrine', [
                        'orm' => [
                            'mappings' => [
                                'WechatMiniProgramOrderBundleTests' => [
                                    'type' => 'attribute',
                                    'is_bundle' => false,
                                    'dir' => $stubDir,
                                    'prefix' => 'WechatMiniProgramOrderBundle\Tests\Stub',
                                    'alias' => 'WechatMiniProgramOrderBundleTests',
                                ],
                            ],
                        ],
                    ]);
                }
            }
        }
    }
}
