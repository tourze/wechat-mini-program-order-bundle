<?php

namespace WechatMiniProgramOrderBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;

class OrderDetailTypeTest extends TestCase
{
    public function testOrderDetailTypeEnumValues(): void
    {
        $this->assertSame(1, OrderDetailType::URL->value);
        $this->assertSame(2, OrderDetailType::MINI_PROGRAM->value);
    }

    public function testOrderDetailTypeEnumCount(): void
    {
        $cases = OrderDetailType::cases();
        $this->assertCount(2, $cases);
        $this->assertContains(OrderDetailType::URL, $cases);
        $this->assertContains(OrderDetailType::MINI_PROGRAM, $cases);
    }

    public function testOrderDetailTypeFromInt(): void
    {
        $this->assertSame(OrderDetailType::URL, OrderDetailType::from(1));
        $this->assertSame(OrderDetailType::MINI_PROGRAM, OrderDetailType::from(2));
    }

    public function testOrderDetailTypeFromInvalidInt(): void
    {
        $this->expectException(\ValueError::class);
        OrderDetailType::from(999);
    }

    public function testOrderDetailTypeTryFrom(): void
    {
        $this->assertSame(OrderDetailType::URL, OrderDetailType::tryFrom(1));
        $this->assertSame(OrderDetailType::MINI_PROGRAM, OrderDetailType::tryFrom(2));
        $this->assertNull(OrderDetailType::tryFrom(999));
    }
} 