<?php

namespace WechatMiniProgramOrderBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 物流形式枚举
 * 1. PHYSICAL_LOGISTICS - 实体物流配送采用快递公司进行实体物流配送形式
 * 2. LOCAL_DELIVERY - 同城配送
 * 3. VIRTUAL_GOODS - 虚拟商品，例如话费充值，点卡等，无实体配送形式
 * 4. SELF_PICKUP - 用户自提
 */
enum LogisticsType: int
 implements Itemable, Labelable, Selectable{
    
    use ItemTrait;
    use SelectTrait;
/**
     * 实体物流配送
     */
    case PHYSICAL_LOGISTICS = 1;

    /**
     * 同城配送
     */
    case LOCAL_DELIVERY = 2;

    /**
     * 虚拟商品
     */
    case VIRTUAL_GOODS = 3;

    /**
     * 用户自提
     */
    case SELF_PICKUP = 4;

    public function getLabel(): string
    {
        return match($this) {
            // TODO: 添加具体的标签映射
            default => $this->name,
        };
    }
}
