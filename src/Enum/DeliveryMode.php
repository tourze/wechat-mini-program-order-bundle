<?php

namespace WechatMiniProgramOrderBundle\Enum;

/**
 * 发货模式枚举
 * 1. UNIFIED_DELIVERY（统一发货）
 * 2. SPLIT_DELIVERY（分拆发货）
 */
enum DeliveryMode: string
{
    /**
     * 统一发货
     */
    case UNIFIED_DELIVERY = 'unified_delivery';

    /**
     * 分拆发货
     */
    case SPLIT_DELIVERY = 'split_delivery';
}
