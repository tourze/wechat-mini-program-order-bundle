<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 发货模式枚举
 * 1. UNIFIED_DELIVERY（统一发货）
 * 2. SPLIT_DELIVERY（分拆发货）
 */
enum DeliveryMode: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    /**
     * 统一发货
     */
    case UNIFIED_DELIVERY = 'unified_delivery';

    /**
     * 分拆发货
     */
    case SPLIT_DELIVERY = 'split_delivery';

    public function getLabel(): string
    {
        return match ($this) {
            self::UNIFIED_DELIVERY => '统一发货',
            self::SPLIT_DELIVERY => '分拆发货',
        };
    }
}
