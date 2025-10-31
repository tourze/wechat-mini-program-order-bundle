<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;

/**
 * 订单标识管理控制器
 *
 * @extends AbstractCrudController<OrderKey>
 */
#[AdminCrud(routePath: '/wechat-mini-program-order/order-key', routeName: 'wechat_mini_program_order_order_key')]
final class OrderKeyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderKey::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            ChoiceField::new('orderNumberType', '订单号类型')
                ->setChoices([
                    '使用商户单号' => OrderNumberType::USE_MCH_ORDER,
                    '使用微信单号' => OrderNumberType::USE_WECHAT_ORDER,
                ])
                ->setRequired(true)
                ->setHelp('选择订单号使用类型'),

            TextField::new('transactionId', '微信订单号')
                ->setRequired(false)
                ->setHelp('原支付交易对应的微信订单号')
                ->setMaxLength(64),

            TextField::new('mchId', '商户号')
                ->setRequired(false)
                ->setHelp('支付下单商户的商户号')
                ->setMaxLength(64),

            TextField::new('outTradeNo', '商户订单号')
                ->setRequired(false)
                ->setHelp('商户系统内部订单号')
                ->setMaxLength(64),

            TextField::new('orderId', '订单ID')
                ->setRequired(false)
                ->setHelp('系统订单ID')
                ->setMaxLength(64),

            TextField::new('outOrderId', '外部订单ID')
                ->setRequired(false)
                ->setHelp('外部系统订单ID')
                ->setMaxLength(64),

            TextField::new('openid', '用户OpenID')
                ->setRequired(false)
                ->setHelp('微信用户唯一标识')
                ->setMaxLength(64),

            TextField::new('pathId', '路径ID')
                ->setRequired(false)
                ->setHelp('订单路径标识')
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
            ->setEntityLabelInSingular('OrderKey')
            ->setEntityLabelInPlural('OrderKey列表')
            ->setPageTitle('index', 'OrderKey管理')
            ->setPageTitle('detail', 'OrderKey详情')
            ->setPageTitle('edit', '编辑OrderKey')
            ->setPageTitle('new', '新建OrderKey')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('orderNumberType')
            ->add('transactionId')
            ->add('mchId')
            ->add('outTradeNo')
            ->add('orderId')
            ->add('outOrderId')
            ->add('openid')
            ->add('pathId')
            ->add('createTime')
            ->add('updateTime')
        ;
    }
}
