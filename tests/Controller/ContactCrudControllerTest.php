<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramOrderBundle\Controller\ContactCrudController;
use WechatMiniProgramOrderBundle\Entity\Contact;

/**
 * @internal
 */
#[CoversClass(ContactCrudController::class)]
#[RunTestsInSeparateProcesses]
class ContactCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testConfigureFields(): void
    {
        $controller = new ContactCrudController();
        $fields = iterator_to_array($controller->configureFields('index'));

        $this->assertNotEmpty($fields);
    }

    protected function getControllerService(): ContactCrudController
    {
        return self::getService(ContactCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '寄件人联系方式' => ['寄件人联系方式'];
        yield '收件人联系方式' => ['收件人联系方式'];
        yield '联系人手机号' => ['联系人手机号'];
        yield '联系人姓名' => ['联系人姓名'];
        yield '联系人地址' => ['联系人地址'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        // 排除hideOnForm的字段：id, createTime, updateTime - 使用字段名而不是标签
        yield 'consignorContact' => ['consignorContact'];
        yield 'receiverContact' => ['receiverContact'];
        yield 'mobile' => ['mobile'];
        yield 'name' => ['name'];
        yield 'address' => ['address'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        // 排除hideOnForm的字段：id, createTime, updateTime - 使用字段名而不是标签
        yield 'consignorContact' => ['consignorContact'];
        yield 'receiverContact' => ['receiverContact'];
        yield 'mobile' => ['mobile'];
        yield 'name' => ['name'];
        yield 'address' => ['address'];
    }
}
