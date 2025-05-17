<?php

namespace WechatMiniProgramOrderBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Enum\DeliveryMode;

class DeliveryModeTest extends TestCase
{
    public function testDeliveryModeEnumValues(): void
    {
        $this->assertSame('unified_delivery', DeliveryMode::UNIFIED_DELIVERY->value);
        $this->assertSame('split_delivery', DeliveryMode::SPLIT_DELIVERY->value);
    }

    public function testDeliveryModeEnumCount(): void
    {
        $cases = DeliveryMode::cases();
        $this->assertCount(2, $cases);
        $this->assertContains(DeliveryMode::UNIFIED_DELIVERY, $cases);
        $this->assertContains(DeliveryMode::SPLIT_DELIVERY, $cases);
    }

    public function testDeliveryModeFromString(): void
    {
        $this->assertSame(DeliveryMode::UNIFIED_DELIVERY, DeliveryMode::from('unified_delivery'));
        $this->assertSame(DeliveryMode::SPLIT_DELIVERY, DeliveryMode::from('split_delivery'));
    }

    public function testDeliveryModeFromInvalidString(): void
    {
        $this->expectException(\ValueError::class);
        DeliveryMode::from('invalid_delivery_mode');
    }

    public function testDeliveryModeTryFrom(): void
    {
        $this->assertSame(DeliveryMode::UNIFIED_DELIVERY, DeliveryMode::tryFrom('unified_delivery'));
        $this->assertSame(DeliveryMode::SPLIT_DELIVERY, DeliveryMode::tryFrom('split_delivery'));
        $this->assertNull(DeliveryMode::tryFrom('invalid_delivery_mode'));
    }
}
