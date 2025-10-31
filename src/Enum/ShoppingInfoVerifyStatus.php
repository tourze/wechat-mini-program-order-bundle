<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum ShoppingInfoVerifyStatus: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case FAILED = 'failed';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => '待审核',
            self::VERIFIED => '已验证',
            self::APPROVED => '已批准',
            self::REJECTED => '已拒绝',
            self::FAILED => '验证失败',
        };
    }
}
