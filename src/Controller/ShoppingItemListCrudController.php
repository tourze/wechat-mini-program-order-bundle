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
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;

/**
 * 购物商品列表管理控制器
 *
 * @extends AbstractCrudController<ShoppingItemList>
 */
#[AdminCrud(routePath: '/wechat-mini-program-order/shopping-item-list', routeName: 'wechat_mini_program_order_shopping_item_list')]
final class ShoppingItemListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShoppingItemList::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            TextField::new('merchantItemId', '商品ID')
                ->setRequired(true)
                ->setHelp('商户系统商品编码')
                ->setMaxLength(128),

            TextField::new('itemName', '商品名称')
                ->setRequired(true)
                ->setHelp('商品的名称')
                ->setMaxLength(128),

            IntegerField::new('itemCount', '商品数量')
                ->setRequired(true)
                ->setHelp('购买的商品数量')
                ->setFormTypeOptions(['attr' => ['min' => 0]]),

            MoneyField::new('itemPrice', '商品单价')
                ->setRequired(true)
                ->setHelp('商品单价，单位：元')
                ->setCurrency('CNY')
                ->setStoredAsCents(false),

            MoneyField::new('itemAmount', '商品总价')
                ->setRequired(true)
                ->setHelp('商品总价，单位：元')
                ->setCurrency('CNY')
                ->setStoredAsCents(false),

            AssociationField::new('shoppingInfo', '所属购物信息')
                ->setRequired(true)
                ->setHelp('该商品所属的购物信息'),

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
            ->setEntityLabelInSingular('ShoppingItemList')
            ->setEntityLabelInPlural('ShoppingItemList列表')
            ->setPageTitle('index', 'ShoppingItemList管理')
            ->setPageTitle('detail', 'ShoppingItemList详情')
            ->setPageTitle('edit', '编辑ShoppingItemList')
            ->setPageTitle('new', '新建ShoppingItemList')
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
