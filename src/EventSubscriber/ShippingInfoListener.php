<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\Request\UploadShippingInfoRequest;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ShippingInfo::class)]
#[Autoconfigure(public: true)]
final class ShippingInfoListener
{
    public function __construct(
        private readonly Client $client,
    ) {
    }

    public function prePersist(ShippingInfo $shippingInfo): void
    {
        try {
            $account = $shippingInfo->getAccount();
            $request = new UploadShippingInfoRequest();
            $request->setAccount($account);
            $request->setShippingInfo($shippingInfo);
            $this->client->asyncRequest($request);
        } catch (\Error $e) {
            // 如果 account 未初始化，忽略并跳过
            return;
        }
    }
}
