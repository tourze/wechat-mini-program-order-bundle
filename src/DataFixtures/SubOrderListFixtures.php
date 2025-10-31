<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;
use WechatMiniProgramOrderBundle\Enum\DeliveryMode;

class SubOrderListFixtures extends Fixture implements DependentFixtureInterface
{
    public const SUB_ORDER_LIST_REFERENCE = 'sub-order-list-1';

    public function load(ObjectManager $manager): void
    {
        $combinedShippingInfo = $this->getReference(CombinedShippingInfoFixtures::COMBINED_SHIPPING_INFO_REFERENCE, CombinedShippingInfo::class);

        $orderKey = new OrderKey();
        $orderKey->setMchId('sub_mch_123');
        $orderKey->setOutTradeNo('sub_trade_456');
        $orderKey->setTransactionId('sub_wx_trans_789');

        $subOrder = new SubOrderList();
        $subOrder->setCombinedShippingInfo($combinedShippingInfo);
        $subOrder->setOrderKey($orderKey);
        $subOrder->setDeliveryMode(DeliveryMode::UNIFIED_DELIVERY);

        $manager->persist($subOrder);
        $manager->flush();

        $this->addReference(self::SUB_ORDER_LIST_REFERENCE, $subOrder);
    }

    public function getDependencies(): array
    {
        return [
            CombinedShippingInfoFixtures::class,
        ];
    }
}
