<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;

/**
 * @internal
 */
#[CoversClass(OrderDetailType::class)]
final class OrderDetailTypeTest extends AbstractEnumTestCase
{
    #[TestWith([OrderDetailType::URL, 1, 'H5链接'])]
    #[TestWith([OrderDetailType::MINI_PROGRAM, 2, '小程序链接'])]
    public function testValueAndLabel(OrderDetailType $enum, int $expectedValue, string $expectedLabel): void
    {
        $this->assertSame($expectedValue, $enum->value);
        $this->assertSame($expectedLabel, $enum->getLabel());
    }

    public function testFromValidValues(): void
    {
        $this->assertSame(OrderDetailType::URL, OrderDetailType::from(1));
        $this->assertSame(OrderDetailType::MINI_PROGRAM, OrderDetailType::from(2));
    }

    public function testFromThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(\ValueError::class);
        OrderDetailType::from(999);
    }

    public function testTryFromValidValues(): void
    {
        $this->assertSame(OrderDetailType::URL, OrderDetailType::tryFrom(1));
        $this->assertSame(OrderDetailType::MINI_PROGRAM, OrderDetailType::tryFrom(2));
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        // 验证特定边界值返回null，补充基类的随机值测试覆盖
        $invalidValues = [999, 0, -1];

        foreach ($invalidValues as $invalidValue) {
            $this->assertNull(
                OrderDetailType::tryFrom($invalidValue),
                "Expected null for invalid value: {$invalidValue}"
            );
        }
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (OrderDetailType $case) => $case->value, OrderDetailType::cases());
        $this->assertSame($values, array_unique($values), 'Enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (OrderDetailType $case) => $case->getLabel(), OrderDetailType::cases());
        $this->assertSame($labels, array_unique($labels), 'Enum labels must be unique');
    }

    public function testToArray(): void
    {
        $this->assertSame(
            ['value' => 1, 'label' => 'H5链接'],
            OrderDetailType::URL->toArray()
        );
    }

    public function testGenOptions(): void
    {
        $options = OrderDetailType::genOptions();
        $this->assertCount(2, $options);

        // 验证第一个选项的结构和内容
        $firstOption = $options[0];
        $this->assertSame(1, $firstOption['value']);
        $this->assertSame('H5链接', $firstOption['label']);
        $this->assertArrayHasKey('text', $firstOption);
        $this->assertArrayHasKey('name', $firstOption);

        // 验证第二个选项的结构和内容
        $secondOption = $options[1];
        $this->assertSame(2, $secondOption['value']);
        $this->assertSame('小程序链接', $secondOption['label']);
        $this->assertArrayHasKey('text', $secondOption);
        $this->assertArrayHasKey('name', $secondOption);
    }
}
