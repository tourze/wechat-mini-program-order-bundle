<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Service;

use Symfony\Bundle\FrameworkBundle\Routing\AttributeRouteControllerLoader;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Routing\RouteCollection;
use Tourze\RoutingAutoLoaderBundle\Service\RoutingAutoLoaderInterface;
use WechatMiniProgramOrderBundle\Controller\CombinedShippingInfoCrudController;
use WechatMiniProgramOrderBundle\Controller\CombinedShoppingInfoCrudController;
use WechatMiniProgramOrderBundle\Controller\ContactCrudController;
use WechatMiniProgramOrderBundle\Controller\OrderKeyCrudController;
use WechatMiniProgramOrderBundle\Controller\ShippingInfoCrudController;
use WechatMiniProgramOrderBundle\Controller\ShippingItemListCrudController;
use WechatMiniProgramOrderBundle\Controller\ShippingListCrudController;
use WechatMiniProgramOrderBundle\Controller\ShoppingInfoCrudController;
use WechatMiniProgramOrderBundle\Controller\ShoppingInfoVerifyUploadCrudController;
use WechatMiniProgramOrderBundle\Controller\ShoppingItemListCrudController;
use WechatMiniProgramOrderBundle\Controller\SubOrderListCrudController;

#[AutoconfigureTag(name: 'routing.loader')]
#[Autoconfigure(public: true)]
final class AttributeControllerLoader extends Loader implements RoutingAutoLoaderInterface
{
    private AttributeRouteControllerLoader $controllerLoader;

    public function __construct()
    {
        parent::__construct();
        $this->controllerLoader = new AttributeRouteControllerLoader();
    }

    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        return $this->autoload();
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return false;
    }

    public function autoload(): RouteCollection
    {
        $collection = new RouteCollection();

        // 订单基础管理控制器
        $collection->addCollection($this->controllerLoader->load(OrderKeyCrudController::class));
        $collection->addCollection($this->controllerLoader->load(ShoppingInfoCrudController::class));
        $collection->addCollection($this->controllerLoader->load(SubOrderListCrudController::class));

        // 配送物流管理控制器
        $collection->addCollection($this->controllerLoader->load(ShippingInfoCrudController::class));
        $collection->addCollection($this->controllerLoader->load(ShippingListCrudController::class));
        $collection->addCollection($this->controllerLoader->load(ShippingItemListCrudController::class));
        $collection->addCollection($this->controllerLoader->load(CombinedShippingInfoCrudController::class));

        // 购物信息管理控制器
        $collection->addCollection($this->controllerLoader->load(ShoppingItemListCrudController::class));
        $collection->addCollection($this->controllerLoader->load(ShoppingInfoVerifyUploadCrudController::class));
        $collection->addCollection($this->controllerLoader->load(CombinedShoppingInfoCrudController::class));

        // 联系人管理控制器
        $collection->addCollection($this->controllerLoader->load(ContactCrudController::class));

        return $collection;
    }
}
