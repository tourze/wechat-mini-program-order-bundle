<?php

namespace WechatMiniProgramOrderBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum OrderStatus: string
 implements Itemable, Labelable, Selectable{
    
    use ItemTrait;
    use SelectTrait;
case CREATED = 'created';
    case PAID = 'paid';
    case DELIVERING = 'delivering';
    case DELIVERED = 'delivered';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    public function getLabel(): string
    {
        return match($this) {
            // TODO: 添加具体的标签映射
            default => $this->name,
        };
    }
}
