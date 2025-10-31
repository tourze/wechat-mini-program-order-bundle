<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramAuthBundle\Entity\User;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\CombinedShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\Contact;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;

class CombinedShoppingInfoFixtures extends Fixture
{
    public const COMBINED_SHOPPING_INFO_REFERENCE = 'combined-shopping-info-1';

    public function load(ObjectManager $manager): void
    {
        $account = new Account();
        $account->setName('Test Combined Shopping Account');
        $account->setAppId('TEST2');
        $account->setAppSecret('TEST1');

        $orderKey = new OrderKey();
        $orderKey->setMchId('test_mch_combined_shopping');
        $orderKey->setOutTradeNo('test_trade_combined_shopping');
        $orderKey->setTransactionId('wx_trans_combined_shopping');

        $contact = new Contact();
        $contact->setConsignorContact('test_consignor');
        $contact->setReceiverContact('test_receiver');

        $payer = $this->createMockPayer($account);

        $shippingInfo = new ShippingInfo();
        $shippingInfo->setAccount($account);
        $shippingInfo->setOrderKey($orderKey);
        $shippingInfo->setPayer($payer);
        $shippingInfo->setDeliveryMobile('13800138000');
        $shippingInfo->setTrackingNo('test_tracking_123');
        $shippingInfo->setDeliveryCompany('test_company');

        $info = new CombinedShoppingInfo();
        $info->setAccount($account);
        $info->setOrderId('order_123');
        $info->setOutOrderId('out_order_456');
        $info->setPathId('path_789');
        $info->setStatus('PENDING');
        $info->setTotalAmount(10000);
        $info->setPayAmount(9000);
        $info->setDiscountAmount(500);
        $info->setFreightAmount(500);
        $info->setPayer($payer);
        $info->setContact($contact);
        $info->setShippingInfo($shippingInfo);

        $manager->persist($account);
        $manager->persist($orderKey);
        $manager->persist($payer);
        $manager->persist($contact);
        $manager->persist($shippingInfo);
        $manager->persist($info);
        $manager->flush();

        $this->addReference(self::COMBINED_SHOPPING_INFO_REFERENCE, $info);
    }

    private function createMockPayer(Account $account): User
    {
        $user = new User();
        $user->setOpenId('test_open_id_combined_shopping_' . uniqid());
        $user->setAccount($account);

        return $user;
    }
}
