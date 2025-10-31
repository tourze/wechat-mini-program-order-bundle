<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramOrderBundle\Entity\ShippingItemList;
use WechatMiniProgramOrderBundle\Entity\ShippingList;

class ShippingItemListFixtures extends Fixture implements DependentFixtureInterface
{
    public const SHIPPING_ITEM_LIST_REFERENCE = 'shipping-item-list-1';

    public function load(ObjectManager $manager): void
    {
        $shippingList = $this->getReference(ShippingListFixtures::SHIPPING_LIST_REFERENCE, ShippingList::class);

        $item = new ShippingItemList();
        $item->setShippingList($shippingList);
        $item->setMerchantItemId('item_123456');

        $manager->persist($item);
        $manager->flush();

        $this->addReference(self::SHIPPING_ITEM_LIST_REFERENCE, $item);
    }

    public function getDependencies(): array
    {
        return [
            ShippingListFixtures::class,
        ];
    }
}
