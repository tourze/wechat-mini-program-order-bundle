<?php

namespace WechatMiniProgramOrderBundle\Enum;

/**
 * 订单单号类型枚举
 * 1. USE_MCH_ORDER - 使用下单商户号和商户侧单号
 * 2. USE_WECHAT_ORDER - 使用微信支付单号
 */
enum OrderNumberType: int
{
    /**
     * 使用下单商户号和商户侧单号
     */
    case USE_MCH_ORDER = 1;

    /**
     * 使用微信支付单号
     */
    case USE_WECHAT_ORDER = 2;
}
