<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 订单单号类型枚举
 * 1. USE_MCH_ORDER - 使用下单商户号和商户侧单号
 * 2. USE_WECHAT_ORDER - 使用微信支付单号
 */
enum OrderNumberType: int implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    /**
     * 使用下单商户号和商户侧单号
     */
    case USE_MCH_ORDER = 1;

    /**
     * 使用微信支付单号
     */
    case USE_WECHAT_ORDER = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::USE_MCH_ORDER => '使用商户单号',
            self::USE_WECHAT_ORDER => '使用微信支付单号',
        };
    }
}
