<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;

/**
 * 购物信息管理控制器
 *
 * @extends AbstractCrudController<ShoppingInfo>
 */
#[AdminCrud(routePath: '/wechat-mini-program-order/shopping-info', routeName: 'wechat_mini_program_order_shopping_info')]
final class ShoppingInfoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShoppingInfo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            BooleanField::new('valid', '有效状态')
                ->setRequired(false)
                ->setHelp('标记该购物信息是否有效'),

            AssociationField::new('account', '小程序账号')
                ->setRequired(true)
                ->setHelp('关联的小程序账号'),

            AssociationField::new('orderKey', '订单信息')
                ->setRequired(true)
                ->setHelp('关联的订单标识信息'),

            AssociationField::new('payer', '支付者')
                ->setRequired(true)
                ->setHelp('订单支付者信息'),

            ChoiceField::new('logisticsType', '物流形式')
                ->setChoices([
                    '实体物流配送' => LogisticsType::PHYSICAL_LOGISTICS,
                    '同城配送' => LogisticsType::LOCAL_DELIVERY,
                    '虚拟商品' => LogisticsType::VIRTUAL_GOODS,
                    '用户自提' => LogisticsType::SELF_PICKUP,
                ])
                ->setRequired(true)
                ->setHelp('选择物流配送方式'),

            ChoiceField::new('orderDetailType', '订单详情页类型')
                ->setChoices([
                    'H5链接' => OrderDetailType::URL,
                    '小程序链接' => OrderDetailType::MINI_PROGRAM,
                ])
                ->setRequired(true)
                ->setHelp('订单详情页链接类型'),

            TextField::new('orderDetailPath', '订单详情页链接')
                ->setRequired(true)
                ->setHelp('订单详情页的访问链接')
                ->setMaxLength(1024),

            AssociationField::new('itemList', '商品列表')
                ->setRequired(false)
                ->setHelp('该订单包含的商品清单'),

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
            ->setEntityLabelInSingular('ShoppingInfo')
            ->setEntityLabelInPlural('ShoppingInfo列表')
            ->setPageTitle('index', 'ShoppingInfo管理')
            ->setPageTitle('detail', 'ShoppingInfo详情')
            ->setPageTitle('edit', '编辑ShoppingInfo')
            ->setPageTitle('new', '新建ShoppingInfo')
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
