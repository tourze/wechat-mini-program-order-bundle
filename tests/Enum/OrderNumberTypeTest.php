<?php

namespace WechatMiniProgramOrderBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;

class OrderNumberTypeTest extends TestCase
{
    public function testOrderNumberTypeEnumValues(): void
    {
        $this->assertSame(1, OrderNumberType::USE_MCH_ORDER->value);
        $this->assertSame(2, OrderNumberType::USE_WECHAT_ORDER->value);
    }

    public function testOrderNumberTypeEnumCount(): void
    {
        $cases = OrderNumberType::cases();
        $this->assertCount(2, $cases);
        $this->assertContains(OrderNumberType::USE_MCH_ORDER, $cases);
        $this->assertContains(OrderNumberType::USE_WECHAT_ORDER, $cases);
    }

    public function testOrderNumberTypeFromInt(): void
    {
        $this->assertSame(OrderNumberType::USE_MCH_ORDER, OrderNumberType::from(1));
        $this->assertSame(OrderNumberType::USE_WECHAT_ORDER, OrderNumberType::from(2));
    }

    public function testOrderNumberTypeFromInvalidInt(): void
    {
        $this->expectException(\ValueError::class);
        OrderNumberType::from(999);
    }

    public function testOrderNumberTypeTryFrom(): void
    {
        $this->assertSame(OrderNumberType::USE_MCH_ORDER, OrderNumberType::tryFrom(1));
        $this->assertSame(OrderNumberType::USE_WECHAT_ORDER, OrderNumberType::tryFrom(2));
        $this->assertNull(OrderNumberType::tryFrom(999));
    }
}
