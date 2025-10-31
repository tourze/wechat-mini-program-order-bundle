<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatMiniProgramOrderBundle\Entity\ShippingList;

/**
 * 物流信息列表管理控制器
 *
 * @extends AbstractCrudController<ShippingList>
 */
#[AdminCrud(routePath: '/wechat-mini-program-order/shipping-list', routeName: 'wechat_mini_program_order_shipping_list')]
final class ShippingListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShippingList::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            AssociationField::new('subOrder', '所属子订单')
                ->setRequired(true)
                ->setHelp('该物流信息所属的子订单'),

            TextField::new('trackingNo', '物流单号')
                ->setRequired(true)
                ->setHelp('快递追踪单号')
                ->setMaxLength(128),

            TextField::new('expressCompany', '快递公司编码')
                ->setRequired(true)
                ->setHelp('快递公司ID或编码')
                ->setMaxLength(128),

            AssociationField::new('itemList', '商品列表')
                ->setRequired(false)
                ->setHelp('该物流单关联的商品清单'),

            AssociationField::new('contact', '联系方式')
                ->setRequired(false)
                ->setHelp('收件人或寄件人联系方式（顺丰快递必填）'),

            CodeEditorField::new('trackingInfo', '物流轨迹信息')
                ->setLanguage('javascript')
                ->setRequired(false)
                ->setHelp('物流状态和轨迹信息')
                ->hideOnIndex()
                ->formatValue(function ($value) {
                    if (is_array($value)) {
                        $encoded = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        return false !== $encoded ? $encoded : '';
                    }
                    if (is_string($value)) {
                        return $value;
                    }
                    if (is_null($value)) {
                        return '';
                    }
                    // 对于其他类型（如数字、布尔值等），安全转换为字符串
                    $encoded = json_encode($value);

                    return false !== $encoded ? $encoded : '';
                }),

            DateTimeField::new('lastTrackingTime', '最后更新物流时间')
                ->setRequired(false)
                ->setHelp('最后一次更新物流信息的时间')
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

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
            ->setEntityLabelInSingular('ShippingList')
            ->setEntityLabelInPlural('ShippingList列表')
            ->setPageTitle('index', 'ShippingList管理')
            ->setPageTitle('detail', 'ShippingList详情')
            ->setPageTitle('edit', '编辑ShippingList')
            ->setPageTitle('new', '新建ShippingList')
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
