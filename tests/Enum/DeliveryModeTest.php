<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramOrderBundle\Enum\DeliveryMode;

/**
 * @internal
 */
#[CoversClass(DeliveryMode::class)]
final class DeliveryModeTest extends AbstractEnumTestCase
{
    public function testFromValidValues(): void
    {
        $this->assertSame(DeliveryMode::UNIFIED_DELIVERY, DeliveryMode::from('unified_delivery'));
        $this->assertSame(DeliveryMode::SPLIT_DELIVERY, DeliveryMode::from('split_delivery'));
    }

    public function testTryFromValidValues(): void
    {
        $this->assertSame(DeliveryMode::UNIFIED_DELIVERY, DeliveryMode::tryFrom('unified_delivery'));
        $this->assertSame(DeliveryMode::SPLIT_DELIVERY, DeliveryMode::tryFrom('split_delivery'));
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        // 验证特定边界值返回null，补充基类的随机值测试覆盖
        $invalidValues = ['invalid_value', '', 'unknown'];

        foreach ($invalidValues as $invalidValue) {
            $this->assertNull(
                DeliveryMode::tryFrom($invalidValue),
                "Expected null for invalid value: '{$invalidValue}'"
            );
        }
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (DeliveryMode $case) => $case->value, DeliveryMode::cases());
        $this->assertSame($values, array_unique($values), 'Enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (DeliveryMode $case) => $case->getLabel(), DeliveryMode::cases());
        $this->assertSame($labels, array_unique($labels), 'Enum labels must be unique');
    }

    public function testToArray(): void
    {
        $this->assertSame(
            ['value' => 'unified_delivery', 'label' => '统一发货'],
            DeliveryMode::UNIFIED_DELIVERY->toArray()
        );
    }

    public function testGenOptions(): void
    {
        $options = DeliveryMode::genOptions();
        $this->assertCount(2, $options);

        // 验证每个选项都包含必要的键
        foreach ($options as $option) {
            $this->assertArrayHasKey('value', $option);
            $this->assertArrayHasKey('label', $option);
            $this->assertArrayHasKey('text', $option);
            $this->assertArrayHasKey('name', $option);
        }

        // 验证第一个选项的具体内容
        $unifiedOption = array_filter($options, fn($opt) => $opt['value'] === 'unified_delivery')[0];
        $this->assertSame('unified_delivery', $unifiedOption['value']);
        $this->assertSame('统一发货', $unifiedOption['label']);
    }
}
