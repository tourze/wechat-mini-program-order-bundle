<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramOrderBundle\Entity\Contact;
use WechatMiniProgramOrderBundle\Entity\ShippingList;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;

class ShippingListFixtures extends Fixture implements DependentFixtureInterface
{
    public const SHIPPING_LIST_REFERENCE = 'shipping-list-1';

    public function load(ObjectManager $manager): void
    {
        $subOrder = $this->getReference(SubOrderListFixtures::SUB_ORDER_LIST_REFERENCE, SubOrderList::class);

        $contact = new Contact();
        $contact->setConsignorContact('寄件人联系方式');
        $contact->setReceiverContact('收件人联系方式');

        $list = new ShippingList();
        $list->setSubOrder($subOrder);
        $list->setTrackingNo('YT9876543210');
        $list->setExpressCompany('圆通快递');
        $list->setContact($contact);
        $list->setTrackingInfo(['status' => '已发出', 'message' => '包裹已发出']);
        $list->setLastTrackingTime(new \DateTimeImmutable());

        $manager->persist($list);
        $manager->flush();

        $this->addReference(self::SHIPPING_LIST_REFERENCE, $list);
    }

    public function getDependencies(): array
    {
        return [
            SubOrderListFixtures::class,
        ];
    }
}
