<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramAuthBundle\Entity\User;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;

class CombinedShippingInfoFixtures extends Fixture
{
    public const COMBINED_SHIPPING_INFO_REFERENCE = 'combined-shipping-info-1';

    public function load(ObjectManager $manager): void
    {
        $account = new Account();
        $account->setName('Test Combined Shipping Account');
        $account->setAppId('TEST2');
        $account->setAppSecret('TEST1');
        $manager->persist($account);

        $orderKey = new OrderKey();
        $orderKey->setMchId('test_mch_123');
        $orderKey->setOutTradeNo('test_trade_456');
        $orderKey->setTransactionId('wx_trans_789');
        $manager->persist($orderKey);

        $payer = new User();
        $payer->setOpenId('test_open_id_combined_shipping_' . uniqid());
        $payer->setAccount($account);
        $manager->persist($payer);

        $info = new CombinedShippingInfo();
        $info->setAccount($account);
        $info->setOrderKey($orderKey);
        $info->setPayer($payer);
        $info->setValid(true);

        $manager->persist($info);
        $manager->flush();

        $this->addReference(self::COMBINED_SHIPPING_INFO_REFERENCE, $info);
    }
}
