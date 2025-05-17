<?php

namespace WechatMiniProgramOrderBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;

class LogisticsTypeTest extends TestCase
{
    public function testLogisticsTypeEnumValues(): void
    {
        $this->assertSame(1, LogisticsType::PHYSICAL_LOGISTICS->value);
        $this->assertSame(2, LogisticsType::LOCAL_DELIVERY->value);
        $this->assertSame(3, LogisticsType::VIRTUAL_GOODS->value);
        $this->assertSame(4, LogisticsType::SELF_PICKUP->value);
    }

    public function testLogisticsTypeEnumCount(): void
    {
        $cases = LogisticsType::cases();
        $this->assertCount(4, $cases);
        $this->assertContains(LogisticsType::PHYSICAL_LOGISTICS, $cases);
        $this->assertContains(LogisticsType::LOCAL_DELIVERY, $cases);
        $this->assertContains(LogisticsType::VIRTUAL_GOODS, $cases);
        $this->assertContains(LogisticsType::SELF_PICKUP, $cases);
    }

    public function testLogisticsTypeFromInt(): void
    {
        $this->assertSame(LogisticsType::PHYSICAL_LOGISTICS, LogisticsType::from(1));
        $this->assertSame(LogisticsType::LOCAL_DELIVERY, LogisticsType::from(2));
        $this->assertSame(LogisticsType::VIRTUAL_GOODS, LogisticsType::from(3));
        $this->assertSame(LogisticsType::SELF_PICKUP, LogisticsType::from(4));
    }

    public function testLogisticsTypeFromInvalidInt(): void
    {
        $this->expectException(\ValueError::class);
        LogisticsType::from(999);
    }

    public function testLogisticsTypeTryFrom(): void
    {
        $this->assertSame(LogisticsType::PHYSICAL_LOGISTICS, LogisticsType::tryFrom(1));
        $this->assertSame(LogisticsType::LOCAL_DELIVERY, LogisticsType::tryFrom(2));
        $this->assertSame(LogisticsType::VIRTUAL_GOODS, LogisticsType::tryFrom(3));
        $this->assertSame(LogisticsType::SELF_PICKUP, LogisticsType::tryFrom(4));
        $this->assertNull(LogisticsType::tryFrom(999));
    }
}
