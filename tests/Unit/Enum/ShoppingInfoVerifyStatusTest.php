<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Enum\ShoppingInfoVerifyStatus;

class ShoppingInfoVerifyStatusTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertSame('pending', ShoppingInfoVerifyStatus::PENDING->value);
        $this->assertSame('verified', ShoppingInfoVerifyStatus::VERIFIED->value);
        $this->assertSame('rejected', ShoppingInfoVerifyStatus::REJECTED->value);
        $this->assertSame('failed', ShoppingInfoVerifyStatus::FAILED->value);
    }

    public function testEnumNames(): void
    {
        $this->assertSame('PENDING', ShoppingInfoVerifyStatus::PENDING->name);
        $this->assertSame('VERIFIED', ShoppingInfoVerifyStatus::VERIFIED->name);
        $this->assertSame('REJECTED', ShoppingInfoVerifyStatus::REJECTED->name);
        $this->assertSame('FAILED', ShoppingInfoVerifyStatus::FAILED->name);
    }

    public function testGetLabel(): void
    {
        $this->assertSame('PENDING', ShoppingInfoVerifyStatus::PENDING->getLabel());
        $this->assertSame('VERIFIED', ShoppingInfoVerifyStatus::VERIFIED->getLabel());
        $this->assertSame('REJECTED', ShoppingInfoVerifyStatus::REJECTED->getLabel());
        $this->assertSame('FAILED', ShoppingInfoVerifyStatus::FAILED->getLabel());
    }

    public function testFromValue(): void
    {
        $this->assertSame(ShoppingInfoVerifyStatus::PENDING, ShoppingInfoVerifyStatus::from('pending'));
        $this->assertSame(ShoppingInfoVerifyStatus::VERIFIED, ShoppingInfoVerifyStatus::from('verified'));
        $this->assertSame(ShoppingInfoVerifyStatus::REJECTED, ShoppingInfoVerifyStatus::from('rejected'));
        $this->assertSame(ShoppingInfoVerifyStatus::FAILED, ShoppingInfoVerifyStatus::from('failed'));
    }

    public function testTryFromValue(): void
    {
        $this->assertSame(ShoppingInfoVerifyStatus::PENDING, ShoppingInfoVerifyStatus::tryFrom('pending'));
        $this->assertSame(ShoppingInfoVerifyStatus::VERIFIED, ShoppingInfoVerifyStatus::tryFrom('verified'));
        $this->assertSame(ShoppingInfoVerifyStatus::REJECTED, ShoppingInfoVerifyStatus::tryFrom('rejected'));
        $this->assertSame(ShoppingInfoVerifyStatus::FAILED, ShoppingInfoVerifyStatus::tryFrom('failed'));
        $this->assertNull(ShoppingInfoVerifyStatus::tryFrom('invalid'));
    }

    public function testCases(): void
    {
        $cases = ShoppingInfoVerifyStatus::cases();
        $this->assertCount(4, $cases);
        $this->assertContains(ShoppingInfoVerifyStatus::PENDING, $cases);
        $this->assertContains(ShoppingInfoVerifyStatus::VERIFIED, $cases);
        $this->assertContains(ShoppingInfoVerifyStatus::REJECTED, $cases);
        $this->assertContains(ShoppingInfoVerifyStatus::FAILED, $cases);
    }
}