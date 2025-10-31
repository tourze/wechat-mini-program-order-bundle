<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramOrderBundle\Enum\ShoppingInfoVerifyStatus;

/**
 * @internal
 */
#[CoversClass(ShoppingInfoVerifyStatus::class)]
final class ShoppingInfoVerifyStatusTest extends AbstractEnumTestCase
{
    #[TestWith([ShoppingInfoVerifyStatus::PENDING, 'pending', '待审核'])]
    #[TestWith([ShoppingInfoVerifyStatus::VERIFIED, 'verified', '已验证'])]
    #[TestWith([ShoppingInfoVerifyStatus::APPROVED, 'approved', '已批准'])]
    #[TestWith([ShoppingInfoVerifyStatus::REJECTED, 'rejected', '已拒绝'])]
    #[TestWith([ShoppingInfoVerifyStatus::FAILED, 'failed', '验证失败'])]
    public function testValueAndLabel(ShoppingInfoVerifyStatus $enum, string $expectedValue, string $expectedLabel): void
    {
        $this->assertSame($expectedValue, $enum->value);
        $this->assertSame($expectedLabel, $enum->getLabel());
    }

    public function testFromValidValues(): void
    {
        $this->assertSame(ShoppingInfoVerifyStatus::PENDING, ShoppingInfoVerifyStatus::from('pending'));
        $this->assertSame(ShoppingInfoVerifyStatus::VERIFIED, ShoppingInfoVerifyStatus::from('verified'));
        $this->assertSame(ShoppingInfoVerifyStatus::REJECTED, ShoppingInfoVerifyStatus::from('rejected'));
        $this->assertSame(ShoppingInfoVerifyStatus::FAILED, ShoppingInfoVerifyStatus::from('failed'));
    }

    public function testFromThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(\ValueError::class);
        ShoppingInfoVerifyStatus::from('invalid_status');
    }

    public function testTryFromValidValues(): void
    {
        $this->assertSame(ShoppingInfoVerifyStatus::PENDING, ShoppingInfoVerifyStatus::tryFrom('pending'));
        $this->assertSame(ShoppingInfoVerifyStatus::VERIFIED, ShoppingInfoVerifyStatus::tryFrom('verified'));
        $this->assertSame(ShoppingInfoVerifyStatus::REJECTED, ShoppingInfoVerifyStatus::tryFrom('rejected'));
        $this->assertSame(ShoppingInfoVerifyStatus::FAILED, ShoppingInfoVerifyStatus::tryFrom('failed'));
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        // 验证特定边界值返回null，补充基类的随机值测试覆盖
        $invalidValues = ['invalid', '', 'unknown'];

        foreach ($invalidValues as $invalidValue) {
            $this->assertNull(
                ShoppingInfoVerifyStatus::tryFrom($invalidValue),
                "Expected null for invalid value: '{$invalidValue}'"
            );
        }
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (ShoppingInfoVerifyStatus $case) => $case->value, ShoppingInfoVerifyStatus::cases());
        $this->assertSame($values, array_unique($values), 'Enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (ShoppingInfoVerifyStatus $case) => $case->getLabel(), ShoppingInfoVerifyStatus::cases());
        $this->assertSame($labels, array_unique($labels), 'Enum labels must be unique');
    }

    public function testToArray(): void
    {
        $this->assertSame(
            ['value' => 'pending', 'label' => '待审核'],
            ShoppingInfoVerifyStatus::PENDING->toArray()
        );
    }

    public function testGenOptions(): void
    {
        $options = ShoppingInfoVerifyStatus::genOptions();
        $this->assertCount(5, $options);

        // 验证每个选项都包含必要的键
        foreach ($options as $option) {
            $this->assertArrayHasKey('value', $option);
            $this->assertArrayHasKey('label', $option);
            $this->assertArrayHasKey('text', $option);
            $this->assertArrayHasKey('name', $option);
        }

        // 验证第一个选项的具体内容
        $pendingOption = array_filter($options, fn($opt) => $opt['value'] === 'pending')[0];
        $this->assertSame('pending', $pendingOption['value']);
        $this->assertSame('待审核', $pendingOption['label']);
    }
}
