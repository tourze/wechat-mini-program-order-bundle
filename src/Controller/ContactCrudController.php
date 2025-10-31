<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatMiniProgramOrderBundle\Entity\Contact;

/**
 * 联系方式信息管理控制器
 *
 * @extends AbstractCrudController<Contact>
 */
#[AdminCrud(routePath: '/wechat-mini-program-order/contact', routeName: 'wechat_mini_program_order_contact')]
final class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            TextareaField::new('consignorContact', '寄件人联系方式')
                ->setRequired(false)
                ->setHelp('寄件人联系方式，采用掩码传输')
                ->setMaxLength(1024),

            TextareaField::new('receiverContact', '收件人联系方式')
                ->setRequired(false)
                ->setHelp('收件人联系方式，采用掩码传输')
                ->setMaxLength(1024),

            TextField::new('mobile', '联系人手机号')
                ->setRequired(false)
                ->setHelp('联系人手机号码')
                ->setMaxLength(64),

            TextField::new('name', '联系人姓名')
                ->setRequired(false)
                ->setHelp('联系人真实姓名')
                ->setMaxLength(128),

            TextField::new('address', '联系人地址')
                ->setRequired(false)
                ->setHelp('联系人详细地址')
                ->setMaxLength(255),

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
            ->setEntityLabelInSingular('Contact')
            ->setEntityLabelInPlural('Contact列表')
            ->setPageTitle('index', 'Contact管理')
            ->setPageTitle('detail', 'Contact详情')
            ->setPageTitle('edit', '编辑Contact')
            ->setPageTitle('new', '新建Contact')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('consignorContact')
            ->add('receiverContact')
            ->add('mobile')
            ->add('name')
            ->add('address')
            ->add('createTime')
            ->add('updateTime')
        ;
    }
}
