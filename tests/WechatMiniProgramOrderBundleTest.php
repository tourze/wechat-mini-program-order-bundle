<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatMiniProgramOrderBundle\WechatMiniProgramOrderBundle;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramOrderBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatMiniProgramOrderBundleTest extends AbstractBundleTestCase
{
}
