<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use WechatMiniProgramAuthBundle\Entity\User;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;

class ShoppingInfoFixtures extends Fixture
{
    public const SHOPPING_INFO_REFERENCE = 'shopping-info-1';

    public function load(ObjectManager $manager): void
    {
        $account = $this->createMockAccount();
        $orderKey = new OrderKey();
        $orderKey->setMchId('test_mch_789');
        $orderKey->setOutTradeNo('test_trade_012');
        $orderKey->setTransactionId('wx_trans_345');

        $payer = $this->createMockPayer($account);

        $info = new ShoppingInfo();
        $info->setAccount($account);
        $info->setOrderKey($orderKey);
        $info->setPayer($payer);
        $info->setLogisticsType(LogisticsType::PHYSICAL_LOGISTICS);
        $info->setOrderDetailType(OrderDetailType::MINI_PROGRAM);
        $info->setOrderDetailPath('pages/order/detail?id=123');
        $info->setValid(true);

        $manager->persist($account);
        $manager->persist($orderKey);
        $manager->persist($payer);
        $manager->persist($info);
        $manager->flush();

        $this->addReference(self::SHOPPING_INFO_REFERENCE, $info);
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
        $user->setOpenId('test_open_id_shopping_' . uniqid());
        $user->setUnionId('test_union_id_789');
        $user->setAvatarUrl('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face');
        $user->setAccount($account);

        return $user;
    }
}
