<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatMiniProgramOrderBundle\Entity\ShippingItemList;

/**
 * 物流商品列表管理控制器
 *
 * @extends AbstractCrudController<ShippingItemList>
 */
#[AdminCrud(routePath: '/wechat-mini-program-order/shipping-item-list', routeName: 'wechat_mini_program_order_shipping_item_list')]
final class ShippingItemListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShippingItemList::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            AssociationField::new('shippingList', '所属物流信息')
                ->setRequired(true)
                ->setHelp('该商品所属的物流信息'),

            TextField::new('merchantItemId', '商户商品ID')
                ->setRequired(true)
                ->setHelp('商户系统内部商品编码')
                ->setMaxLength(64),

            DateTimeField::new('createTime', '创建时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            DateTimeField::new('updateTime', '更新时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('ShippingItemList')
            ->setEntityLabelInPlural('ShippingItemList列表')
            ->setPageTitle('index', 'ShippingItemList管理')
            ->setPageTitle('detail', 'ShippingItemList详情')
            ->setPageTitle('edit', '编辑ShippingItemList')
            ->setPageTitle('new', '新建ShippingItemList')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('createTime')
            ->add('updateTime')
        ;
    }
}
