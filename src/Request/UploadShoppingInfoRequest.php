<?php

namespace WechatMiniProgramOrderBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;

/**
 * 上传购物信息
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/shopping-order/normal-shopping-detail/uploadShoppingInfo.html
 */
class UploadShoppingInfoRequest extends WithAccountRequest
{
    private ShoppingInfo $shoppingInfo;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/user-order/orders';
    }

    public function getRequestOptions(): ?array
    {
        $json = [
            'order_key' => [
                'order_number_type' => $this->shoppingInfo->getOrderKey()->getOrderNumberType()->value,
                'transaction_id' => $this->shoppingInfo->getOrderKey()->getTransactionId(),
                'mch_id' => $this->shoppingInfo->getOrderKey()->getMchId(),
                'out_trade_no' => $this->shoppingInfo->getOrderKey()->getOutTradeNo(),
            ],
            'payer' => [
                'openid' => $this->shoppingInfo->getPayer()->getOpenId(),
            ],
            'order_detail' => [
                'order_detail_type' => $this->shoppingInfo->getOrderDetailType()->value,
                'order_detail_path' => $this->shoppingInfo->getOrderDetailPath(),
            ],
            'logistics_type' => $this->shoppingInfo->getLogisticsType()->value,
            'order_list' => array_map(function (ShoppingItemList $item) {
                return [
                    'item_name' => $item->getItemName(),
                    'item_count' => $item->getItemCount(),
                    'item_price' => $item->getItemPrice(),
                    'item_amount' => $item->getItemAmount(),
                    'merchant_item_id' => $item->getMerchantItemId(),
                ];
            }, $this->shoppingInfo->getItemList()->toArray()),
        ];

        return [
            'json' => $json,
        ];
    }

    public function getShoppingInfo(): ShoppingInfo
    {
        return $this->shoppingInfo;
    }

    public function setShoppingInfo(ShoppingInfo $shoppingInfo): self
    {
        $this->shoppingInfo = $shoppingInfo;

        return $this;
    }
}
