<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramOrderBundle\Controller\ShippingItemListCrudController;
use WechatMiniProgramOrderBundle\Entity\ShippingItemList;

/**
 * @internal
 */
#[CoversClass(ShippingItemListCrudController::class)]
#[RunTestsInSeparateProcesses]
class ShippingItemListCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testGetEntityFqcn(): void
    {
        $this->assertSame(ShippingItemList::class, ShippingItemListCrudController::getEntityFqcn());
    }

    public function testConfigureFields(): void
    {
        $controller = new ShippingItemListCrudController();
        $fields = iterator_to_array($controller->configureFields('index'));

        $this->assertNotEmpty($fields);
    }

    public function testControllerCanBeInstantiated(): void
    {
        $controller = new ShippingItemListCrudController();
        $this->assertInstanceOf(ShippingItemListCrudController::class, $controller);
    }

    protected function getControllerService(): ShippingItemListCrudController
    {
        return self::getService(ShippingItemListCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '所属物流信息' => ['所属物流信息'];
        yield '商户商品ID' => ['商户商品ID'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'shippingList' => ['shippingList'];
        yield 'merchantItemId' => ['merchantItemId'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'shippingList' => ['shippingList'];
        yield 'merchantItemId' => ['merchantItemId'];
    }

    /**
     * 测试表单验证错误 - 提交无效数据应该显示验证错误
     *
     * ShippingItemList实体验证约束：
     * - merchantItemId: NotBlank + Length(max: 64)
     */
    public function testValidationErrors(): void
    {
        $entity = new ShippingItemList();

        // 使用验证器服务直接测试实体验证约束
        $violations = self::getService(ValidatorInterface::class)->validate($entity);

        // 应该有验证错误：缺少必填字段（merchantItemId）和必填关联（shippingList）
        // 验证错误信息类似于 "should not be blank"
        self::assertGreaterThan(
            0,
            $violations->count(),
            '实体应该有验证错误，因为缺少必填字段（merchantItemId should not be blank）'
        );

        // 测试字符串长度约束 - merchantItemId超长
        $longString = str_repeat('x', 65); // 超过64字符限制
        $entity->setMerchantItemId($longString);

        $violations = self::getService(ValidatorInterface::class)->validate($entity);

        // 查找merchantItemId的长度验证错误
        $hasLengthError = false;
        foreach ($violations as $violation) {
            if ('merchantItemId' === $violation->getPropertyPath()) {
                $message = (string) $violation->getMessage();
                if (str_contains($message, 'long') || str_contains($message, '长度')) {
                    $hasLengthError = true;
                    break;
                }
            }
        }

        self::assertTrue(
            $hasLengthError,
            'merchantItemId字段应该有长度验证错误（值65字符超出max:64限制）'
        );
    }
}
