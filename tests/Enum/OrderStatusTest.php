<?php

namespace WechatMiniProgramOrderBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Enum\OrderStatus;

class OrderStatusTest extends TestCase
{
    public function testOrderStatusEnumValues(): void
    {
        $this->assertSame('created', OrderStatus::CREATED->value);
        $this->assertSame('paid', OrderStatus::PAID->value);
        $this->assertSame('delivering', OrderStatus::DELIVERING->value);
        $this->assertSame('delivered', OrderStatus::DELIVERED->value);
        $this->assertSame('completed', OrderStatus::COMPLETED->value);
        $this->assertSame('cancelled', OrderStatus::CANCELLED->value);
        $this->assertSame('refunded', OrderStatus::REFUNDED->value);
    }

    public function testOrderStatusEnumCount(): void
    {
        $cases = OrderStatus::cases();
        $this->assertCount(7, $cases);
        $this->assertContains(OrderStatus::CREATED, $cases);
        $this->assertContains(OrderStatus::PAID, $cases);
        $this->assertContains(OrderStatus::DELIVERING, $cases);
        $this->assertContains(OrderStatus::DELIVERED, $cases);
        $this->assertContains(OrderStatus::COMPLETED, $cases);
        $this->assertContains(OrderStatus::CANCELLED, $cases);
        $this->assertContains(OrderStatus::REFUNDED, $cases);
    }

    public function testOrderStatusFromString(): void
    {
        $this->assertSame(OrderStatus::CREATED, OrderStatus::from('created'));
        $this->assertSame(OrderStatus::PAID, OrderStatus::from('paid'));
        $this->assertSame(OrderStatus::DELIVERING, OrderStatus::from('delivering'));
        $this->assertSame(OrderStatus::DELIVERED, OrderStatus::from('delivered'));
        $this->assertSame(OrderStatus::COMPLETED, OrderStatus::from('completed'));
        $this->assertSame(OrderStatus::CANCELLED, OrderStatus::from('cancelled'));
        $this->assertSame(OrderStatus::REFUNDED, OrderStatus::from('refunded'));
    }

    public function testOrderStatusFromInvalidString(): void
    {
        $this->expectException(\ValueError::class);
        OrderStatus::from('invalid_status');
    }

    public function testOrderStatusTryFrom(): void
    {
        $this->assertSame(OrderStatus::CREATED, OrderStatus::tryFrom('created'));
        $this->assertSame(OrderStatus::PAID, OrderStatus::tryFrom('paid'));
        $this->assertNull(OrderStatus::tryFrom('invalid_status'));
    }
} 