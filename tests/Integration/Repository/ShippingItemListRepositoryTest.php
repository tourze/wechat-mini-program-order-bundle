<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Integration\Repository;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Repository\ShippingItemListRepository;

class ShippingItemListRepositoryTest extends TestCase
{
    public function testRepositoryExists(): void
    {
        $this->assertTrue(class_exists(ShippingItemListRepository::class));
    }
}
