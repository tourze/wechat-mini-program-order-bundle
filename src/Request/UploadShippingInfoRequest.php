<?php

namespace WechatMiniProgramOrderBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;

/**
 * 上传物流信息
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/shopping-order/normal-shopping-detail/uploadShippingInfo.html
 */
class UploadShippingInfoRequest extends WithAccountRequest
{
    private ShippingInfo $shippingInfo;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/user-order/orders/shippings';
    }

    public function getRequestOptions(): ?array
    {
        $json = [
            'order_key' => [
                'order_number_type' => $this->shippingInfo->getOrderKey()->getOrderNumberType()->value,
                'transaction_id' => $this->shippingInfo->getOrderKey()->getTransactionId(),
                'mch_id' => $this->shippingInfo->getOrderKey()->getMchId(),
                'out_trade_no' => $this->shippingInfo->getOrderKey()->getOutTradeNo(),
            ],
            'logistics_type' => $this->shippingInfo->getLogisticsType()->value,
            'shipping_list' => [
                [
                    'tracking_no' => $this->shippingInfo->getTrackingNo(),
                    'express_company' => $this->shippingInfo->getDeliveryCompany(),
                    'delivery_mode' => 1, // 快递公司
                ],
            ],
            'payer' => [
                'openid' => $this->shippingInfo->getPayer()->getOpenId(),
            ],
            'receiver_info' => [
                'receiver_contact' => $this->shippingInfo->getDeliveryMobile(),
            ],
            'upload_time' => (new \DateTimeImmutable())->format('Y-m-d\TH:i:sP'),
        ];

        return [
            'json' => $json,
        ];
    }

    public function getShippingInfo(): ShippingInfo
    {
        return $this->shippingInfo;
    }

    public function setShippingInfo(ShippingInfo $shippingInfo): self
    {
        $this->shippingInfo = $shippingInfo;

        return $this;
    }
}
