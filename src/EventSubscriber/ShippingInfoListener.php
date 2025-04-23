<?php

namespace WechatMiniProgramOrderBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\Request\UploadShippingInfoRequest;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ShippingInfo::class)]
class ShippingInfoListener
{
    public function __construct(
        private readonly Client $client,
    ) {
    }

    public function prePersist(ShippingInfo $shippingInfo): void
    {
        $request = new UploadShippingInfoRequest();
        $request->setAccount($shippingInfo->getAccount());
        $request->setShippingInfo($shippingInfo);
        $this->client->asyncRequest($request);
    }
}
