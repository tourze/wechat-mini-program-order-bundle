<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;

class ShoppingItemListFixtures extends Fixture implements DependentFixtureInterface
{
    public const SHOPPING_ITEM_LIST_REFERENCE = 'shopping-item-list-1';

    public function load(ObjectManager $manager): void
    {
        $shoppingInfo = $this->getReference(ShoppingInfoFixtures::SHOPPING_INFO_REFERENCE, ShoppingInfo::class);

        $item = new ShoppingItemList();
        $item->setMerchantItemId('item_abc123');
        $item->setItemName('测试商品');
        $item->setItemCount(2);
        $item->setItemPrice('99.99');
        $item->setItemAmount('199.98');
        $item->setShoppingInfo($shoppingInfo);

        $manager->persist($item);
        $manager->flush();

        $this->addReference(self::SHOPPING_ITEM_LIST_REFERENCE, $item);
    }

    public function getDependencies(): array
    {
        return [
            ShoppingInfoFixtures::class,
        ];
    }
}
