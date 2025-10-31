<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use WechatMiniProgramAuthBundle\WechatMiniProgramAuthBundle;
use WechatMiniProgramBundle\WechatMiniProgramBundle;

class WechatMiniProgramOrderBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
            WechatMiniProgramBundle::class => ['all' => true],
            WechatMiniProgramAuthBundle::class => ['all' => true],
        ];
    }
}
