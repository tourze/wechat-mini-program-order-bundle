<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramOrderBundle\Controller\ShippingListCrudController;
use WechatMiniProgramOrderBundle\Entity\ShippingList;

/**
 * @internal
 */
#[CoversClass(ShippingListCrudController::class)]
#[RunTestsInSeparateProcesses]
class ShippingListCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testGetEntityFqcn(): void
    {
        $this->assertSame(ShippingList::class, ShippingListCrudController::getEntityFqcn());
    }

    public function testConfigureFields(): void
    {
        $controller = new ShippingListCrudController();
        $fields = iterator_to_array($controller->configureFields('index'));

        $this->assertNotEmpty($fields);
    }

    public function testControllerCanBeInstantiated(): void
    {
        $controller = new ShippingListCrudController();
        $this->assertInstanceOf(ShippingListCrudController::class, $controller);
    }

    protected function getControllerService(): ShippingListCrudController
    {
        return self::getService(ShippingListCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '所属子订单' => ['所属子订单'];
        yield '物流单号' => ['物流单号'];
        yield '快递公司编码' => ['快递公司编码'];
        yield '商品列表' => ['商品列表'];
        yield '联系方式' => ['联系方式'];
        yield '最后更新物流时间' => ['最后更新物流时间'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'subOrder' => ['subOrder'];
        yield 'trackingNo' => ['trackingNo'];
        yield 'expressCompany' => ['expressCompany'];
        yield 'itemList' => ['itemList'];
        yield 'contact' => ['contact'];
        yield 'trackingInfo' => ['trackingInfo'];
        yield 'lastTrackingTime' => ['lastTrackingTime'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'subOrder' => ['subOrder'];
        yield 'trackingNo' => ['trackingNo'];
        yield 'expressCompany' => ['expressCompany'];
        yield 'itemList' => ['itemList'];
        yield 'contact' => ['contact'];
        yield 'trackingInfo' => ['trackingInfo'];
        yield 'lastTrackingTime' => ['lastTrackingTime'];
    }

    /**
     * 测试表单验证错误 - 提交无效数据应该显示验证错误
     *
     * ShippingList实体验证约束：
     * - trackingNo: NotBlank + Length(max: 128)
     * - expressCompany: NotBlank + Length(max: 128)
     * - trackingInfo: Type(array)
     * - lastTrackingTime: Type(\DateTimeImmutable)
     */
    public function testValidationErrors(): void
    {
        $entity = new ShippingList();

        // 使用验证器服务直接测试实体验证约束
        $violations = self::getService(ValidatorInterface::class)->validate($entity);

        // 应该有验证错误：缺少必填字段（trackingNo、expressCompany）
        // 验证错误信息类似于 "should not be blank"
        self::assertGreaterThan(
            0,
            $violations->count(),
            '实体应该有验证错误，因为缺少必填字段（trackingNo、expressCompany should not be blank）'
        );

        // 测试字符串长度约束 - 设置超长字符串
        $longString = str_repeat('x', 129); // 超过128字符限制
        $entity->setTrackingNo($longString);
        $entity->setExpressCompany($longString);

        $violations = self::getService(ValidatorInterface::class)->validate($entity);

        // 查找长度验证错误
        $hasLengthError = false;
        foreach ($violations as $violation) {
            if (in_array($violation->getPropertyPath(), ['trackingNo', 'expressCompany'], true)) {
                $hasLengthError = true;
                break;
            }
        }

        self::assertTrue(
            $hasLengthError,
            '应该有字段长度验证错误（超过128字符限制）'
        );
    }
}
