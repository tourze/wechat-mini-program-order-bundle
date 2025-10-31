<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 订单详情链接类型枚举
 * 1. URL - H5链接
 * 2. MINI_PROGRAM - 小程序链接
 */
enum OrderDetailType: int implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    /**
     * H5链接
     */
    case URL = 1;

    /**
     * 小程序链接
     */
    case MINI_PROGRAM = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::URL => 'H5链接',
            self::MINI_PROGRAM => '小程序链接',
        };
    }
}
