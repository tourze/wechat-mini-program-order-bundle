<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;

/**
 * 合单物流信息管理控制器
 *
 * @extends AbstractCrudController<CombinedShippingInfo>
 */
#[AdminCrud(routePath: '/wechat-mini-program-order/combined-shipping-info', routeName: 'wechat_mini_program_order_combined_shipping_info')]
final class CombinedShippingInfoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CombinedShippingInfo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            AssociationField::new('account', '小程序账号')
                ->setRequired(true)
                ->setHelp('关联的小程序账号'),

            AssociationField::new('orderKey', '合单订单信息')
                ->setRequired(true)
                ->setHelp('合单订单标识信息'),

            AssociationField::new('payer', '支付者')
                ->setRequired(true)
                ->setHelp('订单支付者信息'),

            DateTimeField::new('uploadTime', '上传时间')
                ->setRequired(true)
                ->setHelp('物流信息上传时间')
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            BooleanField::new('valid', '有效状态')
                ->setRequired(false)
                ->setHelp('标记该合单物流信息是否有效'),

            DateTimeField::new('createTime', '创建时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            DateTimeField::new('updateTime', '更新时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),
        ];

        // 根据页面调整字段显示
        if (Crud::PAGE_EDIT === $pageName) {
            // 编辑页面显示子单列表
            $fields[] = AssociationField::new('subOrders', '子单列表')
                ->setRequired(false)
                ->setHelp('该合单包含的子单列表')
            ;
        } elseif (Crud::PAGE_INDEX === $pageName) {
            // 索引页面显示子单列表概览
            $fields[] = AssociationField::new('subOrders', '子单列表')
                ->setRequired(false)
                ->setHelp('该合单包含的子单列表')
            ;
        }

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('CombinedShippingInfo')
            ->setEntityLabelInPlural('CombinedShippingInfo列表')
            ->setPageTitle('index', 'CombinedShippingInfo管理')
            ->setPageTitle('detail', 'CombinedShippingInfo详情')
            ->setPageTitle('edit', '编辑CombinedShippingInfo')
            ->setPageTitle('new', '新建CombinedShippingInfo')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('account')
            ->add('orderKey')
            ->add('payer')
            ->add('uploadTime')
            ->add('valid')
            ->add('createTime')
            ->add('updateTime')
        ;
    }
}
