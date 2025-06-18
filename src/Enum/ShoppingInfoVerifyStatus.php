<?php

namespace WechatMiniProgramOrderBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum ShoppingInfoVerifyStatus: string
 implements Itemable, Labelable, Selectable{
    
    use ItemTrait;
    use SelectTrait;
case PENDING = 'pending';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';
    case FAILED = 'failed';

    public function getLabel(): string
    {
        return match($this) {
            // TODO: 添加具体的标签映射
            default => $this->name,
        };
    }
}
