<?php

namespace WechatMiniProgramOrderBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Request\UploadShoppingInfoRequest;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ShoppingInfo::class)]
class ShoppingInfoListener
{
    public function __construct(
        private readonly Client $client,
    ) {
    }

    public function prePersist(ShoppingInfo $shoppingInfo): void
    {
        $request = new UploadShoppingInfoRequest();
        $request->setAccount($shoppingInfo->getAccount());
        $request->setShoppingInfo($shoppingInfo);
        $this->client->asyncRequest($request);
    }
}
