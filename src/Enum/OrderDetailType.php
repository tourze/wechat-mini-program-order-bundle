<?php

namespace WechatMiniProgramOrderBundle\Enum;

/**
 * 订单详情链接类型枚举
 * 1. URL - H5链接
 * 2. MINI_PROGRAM - 小程序链接
 */
enum OrderDetailType: int
{
    /**
     * H5链接
     */
    case URL = 1;

    /**
     * 小程序链接
     */
    case MINI_PROGRAM = 2;
}
