<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;

/**
 * @internal
 */
#[CoversClass(OrderNumberType::class)]
final class OrderNumberTypeTest extends AbstractEnumTestCase
{
    #[TestWith([OrderNumberType::USE_MCH_ORDER, 1, '使用商户单号'])]
    #[TestWith([OrderNumberType::USE_WECHAT_ORDER, 2, '使用微信支付单号'])]
    public function testValueAndLabel(OrderNumberType $enum, int $expectedValue, string $expectedLabel): void
    {
        $this->assertSame($expectedValue, $enum->value);
        $this->assertSame($expectedLabel, $enum->getLabel());
    }

    public function testFromValidValues(): void
    {
        $this->assertSame(OrderNumberType::USE_MCH_ORDER, OrderNumberType::from(1));
        $this->assertSame(OrderNumberType::USE_WECHAT_ORDER, OrderNumberType::from(2));
    }

    public function testFromThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(\ValueError::class);
        OrderNumberType::from(999);
    }

    public function testTryFromValidValues(): void
    {
        $this->assertSame(OrderNumberType::USE_MCH_ORDER, OrderNumberType::tryFrom(1));
        $this->assertSame(OrderNumberType::USE_WECHAT_ORDER, OrderNumberType::tryFrom(2));
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        // 验证特定边界值返回null，补充基类的随机值测试覆盖
        $invalidValues = [999, 0, -1];

        foreach ($invalidValues as $invalidValue) {
            $this->assertNull(
                OrderNumberType::tryFrom($invalidValue),
                "Expected null for invalid value: {$invalidValue}"
            );
        }
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (OrderNumberType $case) => $case->value, OrderNumberType::cases());
        $this->assertSame($values, array_unique($values), 'Enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (OrderNumberType $case) => $case->getLabel(), OrderNumberType::cases());
        $this->assertSame($labels, array_unique($labels), 'Enum labels must be unique');
    }

    public function testToArray(): void
    {
        $this->assertSame(
            ['value' => 1, 'label' => '使用商户单号'],
            OrderNumberType::USE_MCH_ORDER->toArray()
        );
    }

    public function testGenOptions(): void
    {
        $options = OrderNumberType::genOptions();
        $this->assertCount(2, $options);

        // 验证第一个选项的结构和内容
        $firstOption = $options[0];
        $this->assertSame(1, $firstOption['value']);
        $this->assertSame('使用商户单号', $firstOption['label']);
        $this->assertArrayHasKey('text', $firstOption);
        $this->assertArrayHasKey('name', $firstOption);

        // 验证第二个选项的结构和内容
        $secondOption = $options[1];
        $this->assertSame(2, $secondOption['value']);
        $this->assertSame('使用微信支付单号', $secondOption['label']);
        $this->assertArrayHasKey('text', $secondOption);
        $this->assertArrayHasKey('name', $secondOption);
    }
}
