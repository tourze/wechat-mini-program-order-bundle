<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;

class OrderKeyFixtures extends Fixture
{
    public const ORDER_KEY_REFERENCE = 'order-key-1';

    public function load(ObjectManager $manager): void
    {
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('wx_transaction_123456789');
        $orderKey->setMchId('mch_123456');
        $orderKey->setOutTradeNo('out_trade_123456789');

        $manager->persist($orderKey);
        $manager->flush();

        $this->addReference(self::ORDER_KEY_REFERENCE, $orderKey);
    }
}
