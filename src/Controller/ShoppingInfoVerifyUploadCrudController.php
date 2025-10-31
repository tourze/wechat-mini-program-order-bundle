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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfoVerifyUpload;
use WechatMiniProgramOrderBundle\Enum\ShoppingInfoVerifyStatus;

/**
 * 购物信息验证上传管理控制器
 *
 * @extends AbstractCrudController<ShoppingInfoVerifyUpload>
 */
#[AdminCrud(routePath: '/wechat-mini-program-order/shopping-info-verify-upload', routeName: 'wechat_mini_program_order_shopping_info_verify_upload')]
final class ShoppingInfoVerifyUploadCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShoppingInfoVerifyUpload::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            TextField::new('orderId', '订单ID')
                ->setRequired(true)
                ->setHelp('系统订单ID')
                ->setMaxLength(64),

            TextField::new('outOrderId', '商户订单ID')
                ->setRequired(true)
                ->setHelp('商户系统订单ID')
                ->setMaxLength(64),

            TextField::new('pathId', '路径ID')
                ->setRequired(true)
                ->setHelp('订单路径标识')
                ->setMaxLength(64),

            ChoiceField::new('status', '验证状态')
                ->setChoices([
                    '待审核' => ShoppingInfoVerifyStatus::PENDING,
                    '已验证' => ShoppingInfoVerifyStatus::VERIFIED,
                    '已批准' => ShoppingInfoVerifyStatus::APPROVED,
                    '已拒绝' => ShoppingInfoVerifyStatus::REJECTED,
                    '验证失败' => ShoppingInfoVerifyStatus::FAILED,
                ])
                ->setRequired(true)
                ->setHelp('购物信息验证状态'),

            TextareaField::new('failReason', '验证失败原因')
                ->setRequired(false)
                ->setHelp('验证失败时的详细原因说明')
                ->setMaxLength(65535),

            TextareaField::new('resultData', '验证结果数据')
                ->setRequired(false)
                ->setHelp('验证过程的详细结果数据')
                ->hideOnIndex()
                ->hideOnForm(),

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
            ->setEntityLabelInSingular('ShoppingInfoVerifyUpload')
            ->setEntityLabelInPlural('ShoppingInfoVerifyUpload列表')
            ->setPageTitle('index', 'ShoppingInfoVerifyUpload管理')
            ->setPageTitle('detail', 'ShoppingInfoVerifyUpload详情')
            ->setPageTitle('edit', '编辑ShoppingInfoVerifyUpload')
            ->setPageTitle('new', '新建ShoppingInfoVerifyUpload')
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
