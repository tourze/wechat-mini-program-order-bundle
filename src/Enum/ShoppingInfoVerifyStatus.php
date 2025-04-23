<?php

namespace WechatMiniProgramOrderBundle\Enum;

enum ShoppingInfoVerifyStatus: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';
    case FAILED = 'failed';
}
