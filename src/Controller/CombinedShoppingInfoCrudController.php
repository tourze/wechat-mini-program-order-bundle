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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatMiniProgramOrderBundle\Entity\CombinedShoppingInfo;

/**
 * 合单购物信息管理控制器
 *
 * @extends AbstractCrudController<CombinedShoppingInfo>
 */
#[AdminCrud(routePath: '/wechat-mini-program-order/combined-shopping-info', routeName: 'wechat_mini_program_order_combined_shopping_info')]
final class CombinedShoppingInfoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CombinedShoppingInfo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            AssociationField::new('account', '小程序账号')
                ->setRequired(true)
                ->setHelp('关联的小程序账号'),

            TextField::new('orderId', '订单ID')
                ->setRequired(true)
                ->setHelp('系统订单ID')
                ->setMaxLength(64),

            TextField::new('outOrderId', '外部订单ID')
                ->setRequired(true)
                ->setHelp('外部系统订单ID')
                ->setMaxLength(64),

            TextField::new('pathId', '路径ID')
                ->setRequired(true)
                ->setHelp('订单路径标识')
                ->setMaxLength(64),

            TextField::new('status', '订单状态')
                ->setRequired(true)
                ->setHelp('合单状态')
                ->setMaxLength(32),

            IntegerField::new('totalAmount', '订单总金额')
                ->setRequired(true)
                ->setHelp('订单总金额，单位：分')
                ->setFormTypeOptions(['attr' => ['min' => 0]]),

            IntegerField::new('payAmount', '实付金额')
                ->setRequired(true)
                ->setHelp('实际支付金额，单位：分')
                ->setFormTypeOptions(['attr' => ['min' => 0]]),

            IntegerField::new('discountAmount', '优惠金额')
                ->setRequired(false)
                ->setHelp('优惠金额，单位：分')
                ->setFormTypeOptions(['attr' => ['min' => 0, 'max' => 100]]),

            IntegerField::new('freightAmount', '运费金额')
                ->setRequired(false)
                ->setHelp('运费金额，单位：分')
                ->setFormTypeOptions(['attr' => ['min' => 0]]),

            AssociationField::new('payer', '支付者')
                ->setRequired(false)
                ->setHelp('订单支付者信息'),

            AssociationField::new('contact', '联系方式')
                ->setRequired(false)
                ->setHelp('联系人信息'),

            AssociationField::new('shippingInfo', '物流信息')
                ->setRequired(false)
                ->setHelp('关联的物流信息'),

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
            ->setEntityLabelInSingular('CombinedShoppingInfo')
            ->setEntityLabelInPlural('CombinedShoppingInfo列表')
            ->setPageTitle('index', 'CombinedShoppingInfo管理')
            ->setPageTitle('detail', 'CombinedShoppingInfo详情')
            ->setPageTitle('edit', '编辑CombinedShoppingInfo')
            ->setPageTitle('new', '新建CombinedShoppingInfo')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('account')
            ->add('orderId')
            ->add('outOrderId')
            ->add('pathId')
            ->add('status')
            ->add('totalAmount')
            ->add('payAmount')
            ->add('discountAmount')
            ->add('freightAmount')
            ->add('payer')
            ->add('contact')
            ->add('shippingInfo')
            ->add('createTime')
            ->add('updateTime')
        ;
    }
}
