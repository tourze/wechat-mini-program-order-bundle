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
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;

/**
 * 物流信息管理控制器
 *
 * @extends AbstractCrudController<ShippingInfo>
 */
#[AdminCrud(routePath: '/wechat-mini-program-order/shipping-info', routeName: 'wechat_mini_program_order_shipping_info')]
final class ShippingInfoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShippingInfo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            BooleanField::new('valid', '有效状态')
                ->setRequired(false)
                ->setHelp('标记该物流信息是否有效'),

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

            TextField::new('deliveryMobile', '收件人手机号')
                ->setRequired(true)
                ->setHelp('收件人联系电话')
                ->setMaxLength(128),

            TextField::new('trackingNo', '物流单号')
                ->setRequired(true)
                ->setHelp('快递追踪单号')
                ->setMaxLength(128),

            TextField::new('deliveryCompany', '物流公司名称')
                ->setRequired(true)
                ->setHelp('配送物流公司名称')
                ->setMaxLength(128),

            TextField::new('expressCompany', '快递公司名称')
                ->setRequired(false)
                ->setHelp('快递公司名称（可选）')
                ->setMaxLength(128),

            TextField::new('deliveryName', '收件人姓名')
                ->setRequired(false)
                ->setHelp('收件人姓名（可选）')
                ->setMaxLength(128),

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
            ->setEntityLabelInSingular('ShippingInfo')
            ->setEntityLabelInPlural('ShippingInfo列表')
            ->setPageTitle('index', 'ShippingInfo管理')
            ->setPageTitle('detail', 'ShippingInfo详情')
            ->setPageTitle('edit', '编辑ShippingInfo')
            ->setPageTitle('new', '新建ShippingInfo')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('valid')
            ->add('account')
            ->add('orderKey')
            ->add('payer')
            ->add('logisticsType')
            ->add('deliveryMobile')
            ->add('trackingNo')
            ->add('deliveryCompany')
            ->add('expressCompany')
            ->add('deliveryName')
            ->add('createTime')
            ->add('updateTime')
        ;
    }
}
