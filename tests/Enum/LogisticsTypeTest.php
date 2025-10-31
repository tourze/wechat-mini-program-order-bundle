<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;

/**
 * @internal
 */
#[CoversClass(LogisticsType::class)]
final class LogisticsTypeTest extends AbstractEnumTestCase
{
    #[TestWith([LogisticsType::PHYSICAL_LOGISTICS, 1, '实体物流配送'])]
    #[TestWith([LogisticsType::LOCAL_DELIVERY, 2, '同城配送'])]
    #[TestWith([LogisticsType::VIRTUAL_GOODS, 3, '虚拟商品'])]
    #[TestWith([LogisticsType::SELF_PICKUP, 4, '用户自提'])]
    public function testValueAndLabel(LogisticsType $enum, int $expectedValue, string $expectedLabel): void
    {
        $this->assertSame($expectedValue, $enum->value);
        $this->assertSame($expectedLabel, $enum->getLabel());
    }

    public function testFromValidValues(): void
    {
        $this->assertSame(LogisticsType::PHYSICAL_LOGISTICS, LogisticsType::from(1));
        $this->assertSame(LogisticsType::LOCAL_DELIVERY, LogisticsType::from(2));
        $this->assertSame(LogisticsType::VIRTUAL_GOODS, LogisticsType::from(3));
        $this->assertSame(LogisticsType::SELF_PICKUP, LogisticsType::from(4));
    }

    public function testFromThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(\ValueError::class);
        LogisticsType::from(999);
    }

    public function testTryFromValidValues(): void
    {
        $this->assertSame(LogisticsType::PHYSICAL_LOGISTICS, LogisticsType::tryFrom(1));
        $this->assertSame(LogisticsType::LOCAL_DELIVERY, LogisticsType::tryFrom(2));
        $this->assertSame(LogisticsType::VIRTUAL_GOODS, LogisticsType::tryFrom(3));
        $this->assertSame(LogisticsType::SELF_PICKUP, LogisticsType::tryFrom(4));
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        // 验证特定边界值返回null，补充基类的随机值测试覆盖
        $invalidValues = [999, 0, -1];

        foreach ($invalidValues as $invalidValue) {
            $this->assertNull(
                LogisticsType::tryFrom($invalidValue),
                "Expected null for invalid value: {$invalidValue}"
            );
        }
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (LogisticsType $case) => $case->value, LogisticsType::cases());
        $this->assertSame($values, array_unique($values), 'Enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (LogisticsType $case) => $case->getLabel(), LogisticsType::cases());
        $this->assertSame($labels, array_unique($labels), 'Enum labels must be unique');
    }

    public function testToArray(): void
    {
        $this->assertSame(
            ['value' => 1, 'label' => '实体物流配送'],
            LogisticsType::PHYSICAL_LOGISTICS->toArray()
        );
    }

    public function testGenOptions(): void
    {
        $options = LogisticsType::genOptions();
        $this->assertCount(4, $options);

        // 验证每个选项都包含必要的键
        foreach ($options as $option) {
            $this->assertArrayHasKey('value', $option);
            $this->assertArrayHasKey('label', $option);
            $this->assertArrayHasKey('text', $option);
            $this->assertArrayHasKey('name', $option);
        }

        // 验证第一个选项的具体内容
        $physicalOption = array_filter($options, fn($opt) => $opt['value'] === 1)[0];
        $this->assertSame(1, $physicalOption['value']);
        $this->assertSame('实体物流配送', $physicalOption['label']);
    }
}
