<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use WechatMiniProgramAuthBundle\Entity\User;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;

class ShippingInfoFixtures extends Fixture
{
    public const SHIPPING_INFO_REFERENCE = 'shipping-info-1';

    public function load(ObjectManager $manager): void
    {
        $account = $this->createMockAccount();
        $orderKey = new OrderKey();
        $orderKey->setMchId('test_mch_456');
        $orderKey->setOutTradeNo('test_trade_789');
        $orderKey->setTransactionId('wx_trans_012');

        $payer = $this->createMockPayer($account);

        $info = new ShippingInfo();
        $info->setAccount($account);
        $info->setOrderKey($orderKey);
        $info->setPayer($payer);
        $info->setLogisticsType(LogisticsType::PHYSICAL_LOGISTICS);
        $info->setDeliveryMobile('13800138000');
        $info->setTrackingNo('SF1234567890');
        $info->setDeliveryCompany('顺丰速运');
        $info->setValid(true);

        $manager->persist($account);
        $manager->persist($orderKey);
        $manager->persist($payer);
        $manager->persist($info);
        $manager->flush();

        $this->addReference(self::SHIPPING_INFO_REFERENCE, $info);
    }

    /**
     * 创建测试用的 MiniProgram 账户
     */
    private function createMockAccount(): MiniProgramInterface
    {
        $account = new Account();
        $account->setAppId('TEST2');
        $account->setAppSecret('TEST1');
        $account->setName('Test Account');
        $account->setValid(true);

        return $account;
    }

    private function createMockPayer(MiniProgramInterface $account): User
    {
        $user = new User();
        $user->setOpenId('test_open_id_shipping_' . uniqid());
        $user->setUnionId('test_union_id_456');
        $user->setAvatarUrl('https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop&crop=face');
        $user->setAccount($account);

        return $user;
    }
}
