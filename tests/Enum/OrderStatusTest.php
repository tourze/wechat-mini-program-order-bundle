<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramOrderBundle\Enum\OrderStatus;

/**
 * @internal
 */
#[CoversClass(OrderStatus::class)]
final class OrderStatusTest extends AbstractEnumTestCase
{
    #[TestWith([OrderStatus::CREATED, 'created', '已创建'])]
    #[TestWith([OrderStatus::PAID, 'paid', '已支付'])]
    #[TestWith([OrderStatus::DELIVERING, 'delivering', '配送中'])]
    #[TestWith([OrderStatus::DELIVERED, 'delivered', '已送达'])]
    #[TestWith([OrderStatus::COMPLETED, 'completed', '已完成'])]
    #[TestWith([OrderStatus::CANCELLED, 'cancelled', '已取消'])]
    #[TestWith([OrderStatus::REFUNDED, 'refunded', '已退款'])]
    public function testValueAndLabel(OrderStatus $enum, string $expectedValue, string $expectedLabel): void
    {
        $this->assertSame($expectedValue, $enum->value);
        $this->assertSame($expectedLabel, $enum->getLabel());
    }

    public function testFromValidValues(): void
    {
        $this->assertSame(OrderStatus::CREATED, OrderStatus::from('created'));
        $this->assertSame(OrderStatus::PAID, OrderStatus::from('paid'));
        $this->assertSame(OrderStatus::DELIVERING, OrderStatus::from('delivering'));
        $this->assertSame(OrderStatus::DELIVERED, OrderStatus::from('delivered'));
        $this->assertSame(OrderStatus::COMPLETED, OrderStatus::from('completed'));
        $this->assertSame(OrderStatus::CANCELLED, OrderStatus::from('cancelled'));
        $this->assertSame(OrderStatus::REFUNDED, OrderStatus::from('refunded'));
    }

    public function testFromThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(\ValueError::class);
        OrderStatus::from('invalid_status');
    }

    public function testTryFromValidValues(): void
    {
        $this->assertSame(OrderStatus::CREATED, OrderStatus::tryFrom('created'));
        $this->assertSame(OrderStatus::PAID, OrderStatus::tryFrom('paid'));
        $this->assertSame(OrderStatus::DELIVERING, OrderStatus::tryFrom('delivering'));
        $this->assertSame(OrderStatus::DELIVERED, OrderStatus::tryFrom('delivered'));
        $this->assertSame(OrderStatus::COMPLETED, OrderStatus::tryFrom('completed'));
        $this->assertSame(OrderStatus::CANCELLED, OrderStatus::tryFrom('cancelled'));
        $this->assertSame(OrderStatus::REFUNDED, OrderStatus::tryFrom('refunded'));
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        // 验证特定边界值返回null，补充基类的随机值测试覆盖
        $invalidValues = ['invalid_status', '', 'unknown'];

        foreach ($invalidValues as $invalidValue) {
            $this->assertNull(
                OrderStatus::tryFrom($invalidValue),
                "Expected null for invalid value: '{$invalidValue}'"
            );
        }
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (OrderStatus $case) => $case->value, OrderStatus::cases());
        $this->assertSame($values, array_unique($values), 'Enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (OrderStatus $case) => $case->getLabel(), OrderStatus::cases());
        $this->assertSame($labels, array_unique($labels), 'Enum labels must be unique');
    }

    public function testToArray(): void
    {
        $this->assertSame(
            ['value' => 'created', 'label' => '已创建'],
            OrderStatus::CREATED->toArray()
        );
    }

    public function testGenOptions(): void
    {
        $options = OrderStatus::genOptions();
        $this->assertCount(7, $options);

        // 验证每个选项都包含必要的键
        foreach ($options as $option) {
            $this->assertArrayHasKey('value', $option);
            $this->assertArrayHasKey('label', $option);
            $this->assertArrayHasKey('text', $option);
            $this->assertArrayHasKey('name', $option);
        }

        // 验证第一个选项的具体内容
        $createdOption = array_filter($options, fn($opt) => $opt['value'] === 'created')[0];
        $this->assertSame('created', $createdOption['value']);
        $this->assertSame('已创建', $createdOption['label']);
    }
}
