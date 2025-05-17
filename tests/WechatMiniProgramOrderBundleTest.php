<?php

namespace WechatMiniProgramOrderBundle\Tests;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\WechatMiniProgramOrderBundle;

class WechatMiniProgramOrderBundleTest extends TestCase
{
    public function testBundleConstruction(): void
    {
        $bundle = new WechatMiniProgramOrderBundle();
        $this->assertInstanceOf(WechatMiniProgramOrderBundle::class, $bundle);
    }
    
    public function testBundleExtendsSfBundle(): void
    {
        $bundle = new WechatMiniProgramOrderBundle();
        $this->assertInstanceOf(\Symfony\Component\HttpKernel\Bundle\Bundle::class, $bundle);
    }
}
