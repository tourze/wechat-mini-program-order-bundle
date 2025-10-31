<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramOrderBundle\Controller\ShoppingInfoVerifyUploadCrudController;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfoVerifyUpload;

/**
 * @internal
 */
#[CoversClass(ShoppingInfoVerifyUploadCrudController::class)]
#[RunTestsInSeparateProcesses]
class ShoppingInfoVerifyUploadCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testGetEntityFqcn(): void
    {
        $this->assertSame(ShoppingInfoVerifyUpload::class, ShoppingInfoVerifyUploadCrudController::getEntityFqcn());
    }

    public function testConfigureFields(): void
    {
        $controller = new ShoppingInfoVerifyUploadCrudController();
        $fields = iterator_to_array($controller->configureFields('index'));

        $this->assertNotEmpty($fields);
    }

    public function testControllerCanBeInstantiated(): void
    {
        $controller = new ShoppingInfoVerifyUploadCrudController();
        $this->assertInstanceOf(ShoppingInfoVerifyUploadCrudController::class, $controller);
    }

    protected function getControllerService(): ShoppingInfoVerifyUploadCrudController
    {
        return self::getService(ShoppingInfoVerifyUploadCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '订单ID' => ['订单ID'];
        yield '商户订单ID' => ['商户订单ID'];
        yield '路径ID' => ['路径ID'];
        yield '验证状态' => ['验证状态'];
        yield '验证失败原因' => ['验证失败原因'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'orderId' => ['orderId'];
        yield 'outOrderId' => ['outOrderId'];
        yield 'pathId' => ['pathId'];
        yield 'status' => ['status'];
        yield 'failReason' => ['failReason'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'orderId' => ['orderId'];
        yield 'outOrderId' => ['outOrderId'];
        yield 'pathId' => ['pathId'];
        yield 'status' => ['status'];
        yield 'failReason' => ['failReason'];
    }

    /**
     * 测试表单验证错误 - 提交无效数据应该显示验证错误
     *
     * ShoppingInfoVerifyUpload实体验证约束：
     * - orderId: NotBlank + Length(max: 64)
     * - outOrderId: NotBlank + Length(max: 64)
     * - pathId: NotBlank + Length(max: 64)
     * - status: Choice(ShoppingInfoVerifyStatus::cases)
     * - failReason: Length(max: 65535)
     * - resultData: Type(array)
     */
    public function testValidationErrors(): void
    {
        $entity = new ShoppingInfoVerifyUpload();

        // 使用验证器服务直接测试实体验证约束
        $violations = self::getService(ValidatorInterface::class)->validate($entity);

        // 应该有验证错误：缺少必填字段（orderId、outOrderId、pathId）
        // 验证错误信息类似于 "should not be blank"
        self::assertGreaterThan(
            0,
            $violations->count(),
            '实体应该有验证错误，因为缺少必填字段（orderId、outOrderId、pathId should not be blank）'
        );

        // 测试字符串长度约束 - 设置超长字符串
        $longString = str_repeat('x', 65); // 超过64字符限制
        $entity->setOrderId($longString);
        $entity->setOutOrderId($longString);
        $entity->setPathId($longString);

        $violations = self::getService(ValidatorInterface::class)->validate($entity);

        // 查找长度验证错误
        $hasLengthError = false;
        foreach ($violations as $violation) {
            if (in_array($violation->getPropertyPath(), ['orderId', 'outOrderId', 'pathId'], true)) {
                $hasLengthError = true;
                break;
            }
        }

        self::assertTrue(
            $hasLengthError,
            '应该有字段长度验证错误（超过64字符限制）'
        );
    }
}
