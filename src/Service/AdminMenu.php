<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;
use WechatMiniProgramOrderBundle\Entity\CombinedShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\Contact;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\Entity\ShippingItemList;
use WechatMiniProgramOrderBundle\Entity\ShippingList;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfoVerifyUpload;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;

/**
 * 微信小程序订单管理后台菜单提供者
 */
#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
    ) {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('微信小程序')) {
            $item->addChild('微信小程序');
        }

        $wechatMenu = $item->getChild('微信小程序');
        if (null === $wechatMenu) {
            return;
        }

        // 添加订单管理子菜单
        if (null === $wechatMenu->getChild('订单管理')) {
            $wechatMenu->addChild('订单管理')
                ->setAttribute('icon', 'fas fa-shopping-cart')
            ;
        }

        $orderMenu = $wechatMenu->getChild('订单管理');
        if (null === $orderMenu) {
            return;
        }

        // 订单基础信息管理
        $orderMenu->addChild('订单标识管理')
            ->setUri($this->linkGenerator->getCurdListPage(OrderKey::class))
            ->setAttribute('icon', 'fas fa-key')
        ;

        $orderMenu->addChild('购物信息管理')
            ->setUri($this->linkGenerator->getCurdListPage(ShoppingInfo::class))
            ->setAttribute('icon', 'fas fa-shopping-bag')
        ;

        $orderMenu->addChild('子订单管理')
            ->setUri($this->linkGenerator->getCurdListPage(SubOrderList::class))
            ->setAttribute('icon', 'fas fa-list-ol')
        ;

        // 配送物流管理
        if (null === $orderMenu->getChild('配送管理')) {
            $orderMenu->addChild('配送管理')
                ->setAttribute('icon', 'fas fa-truck')
            ;
        }

        $shippingMenu = $orderMenu->getChild('配送管理');
        if (null !== $shippingMenu) {
            $shippingMenu->addChild('物流信息')
                ->setUri($this->linkGenerator->getCurdListPage(ShippingInfo::class))
                ->setAttribute('icon', 'fas fa-shipping-fast')
            ;

            $shippingMenu->addChild('物流列表')
                ->setUri($this->linkGenerator->getCurdListPage(ShippingList::class))
                ->setAttribute('icon', 'fas fa-list')
            ;

            $shippingMenu->addChild('物流商品')
                ->setUri($this->linkGenerator->getCurdListPage(ShippingItemList::class))
                ->setAttribute('icon', 'fas fa-boxes')
            ;

            $shippingMenu->addChild('合单物流')
                ->setUri($this->linkGenerator->getCurdListPage(CombinedShippingInfo::class))
                ->setAttribute('icon', 'fas fa-layer-group')
            ;
        }

        // 购物信息管理
        if (null === $orderMenu->getChild('购物信息')) {
            $orderMenu->addChild('购物信息')
                ->setAttribute('icon', 'fas fa-store')
            ;
        }

        $shoppingMenu = $orderMenu->getChild('购物信息');
        if (null !== $shoppingMenu) {
            $shoppingMenu->addChild('购物商品列表')
                ->setUri($this->linkGenerator->getCurdListPage(ShoppingItemList::class))
                ->setAttribute('icon', 'fas fa-list-ul')
            ;

            $shoppingMenu->addChild('信息验证上传')
                ->setUri($this->linkGenerator->getCurdListPage(ShoppingInfoVerifyUpload::class))
                ->setAttribute('icon', 'fas fa-check-circle')
            ;

            $shoppingMenu->addChild('合单购物信息')
                ->setUri($this->linkGenerator->getCurdListPage(CombinedShoppingInfo::class))
                ->setAttribute('icon', 'fas fa-shopping-basket')
            ;
        }

        // 联系人管理
        $orderMenu->addChild('联系人管理')
            ->setUri($this->linkGenerator->getCurdListPage(Contact::class))
            ->setAttribute('icon', 'fas fa-address-book')
        ;
    }
}
