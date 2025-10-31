<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;
use WechatMiniProgramOrderBundle\Enum\DeliveryMode;

/**
 * 子单物流详情管理控制器
 *
 * @extends AbstractCrudController<SubOrderList>
 */
#[AdminCrud(routePath: '/wechat-mini-program-order/sub-order-list', routeName: 'wechat_mini_program_order_sub_order_list')]
final class SubOrderListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SubOrderList::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            AssociationField::new('combinedShippingInfo', '所属合单物流信息')
                ->setRequired(true)
                ->setHelp('该子单所属的合单物流信息'),

            AssociationField::new('orderKey', '订单信息')
                ->setRequired(true)
                ->setHelp('关联的订单标识信息'),

            ChoiceField::new('deliveryMode', '发货模式')
                ->setChoices([
                    '统一发货' => DeliveryMode::UNIFIED_DELIVERY,
                    '分拆发货' => DeliveryMode::SPLIT_DELIVERY,
                ])
                ->setRequired(true)
                ->setHelp('选择发货模式：统一发货或分拆发货'),

            AssociationField::new('shippingList', '物流信息列表')
                ->setRequired(false)
                ->setHelp('该子单关联的物流信息列表'),

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
            ->setEntityLabelInSingular('SubOrderList')
            ->setEntityLabelInPlural('SubOrderList列表')
            ->setPageTitle('index', 'SubOrderList管理')
            ->setPageTitle('detail', 'SubOrderList详情')
            ->setPageTitle('edit', '编辑SubOrderList')
            ->setPageTitle('new', '新建SubOrderList')
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
