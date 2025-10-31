<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramOrderBundle\Controller\ShippingInfoCrudController;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;

/**
 * @internal
 */
#[CoversClass(ShippingInfoCrudController::class)]
#[RunTestsInSeparateProcesses]
class ShippingInfoCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testGetEntityFqcn(): void
    {
        $this->assertSame(ShippingInfo::class, ShippingInfoCrudController::getEntityFqcn());
    }

    public function testConfigureFields(): void
    {
        $controller = new ShippingInfoCrudController();
        $fields = iterator_to_array($controller->configureFields('index'));

        $this->assertNotEmpty($fields);
    }

    protected function getControllerService(): ShippingInfoCrudController
    {
        return self::getService(ShippingInfoCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '有效状态' => ['有效状态'];
        yield '小程序账号' => ['小程序账号'];
        yield '订单信息' => ['订单信息'];
        yield '支付者' => ['支付者'];
        yield '物流形式' => ['物流形式'];
        yield '收件人手机号' => ['收件人手机号'];
        yield '物流单号' => ['物流单号'];
        yield '物流公司名称' => ['物流公司名称'];
        yield '快递公司名称' => ['快递公司名称'];
        yield '收件人姓名' => ['收件人姓名'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        // 排除hideOnForm的字段：id, createTime, updateTime - 使用字段名而不是标签
        // 优先使用非关联字段，避免关联字段可能的渲染问题
        yield 'valid' => ['valid'];
        yield 'logisticsType' => ['logisticsType'];
        yield 'deliveryMobile' => ['deliveryMobile'];
        yield 'trackingNo' => ['trackingNo'];
        yield 'deliveryCompany' => ['deliveryCompany'];
        yield 'expressCompany' => ['expressCompany'];
        yield 'deliveryName' => ['deliveryName'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        // 排除hideOnForm的字段：id, createTime, updateTime - 使用字段名而不是标签
        // 优先使用非关联字段，避免关联字段可能的渲染问题
        yield 'valid' => ['valid'];
        yield 'logisticsType' => ['logisticsType'];
        yield 'deliveryMobile' => ['deliveryMobile'];
        yield 'trackingNo' => ['trackingNo'];
        yield 'deliveryCompany' => ['deliveryCompany'];
        yield 'expressCompany' => ['expressCompany'];
        yield 'deliveryName' => ['deliveryName'];
    }

    /**
     * 测试表单验证错误 - 提交无效数据应该显示验证错误
     *
     * ShippingInfo实体验证约束：
     * - deliveryMobile: NotBlank + Length(max: 128) + Regex(手机号格式)
     * - trackingNo: NotBlank + Length(max: 128)
     * - deliveryCompany: NotBlank + Length(max: 128)
     * - expressCompany: Length(max: 128)
     * - deliveryName: Length(max: 128)
     */
    public function testValidationErrors(): void
    {
        $entity = new ShippingInfo();

        // 使用验证器服务直接测试实体验证约束
        $violations = self::getService(ValidatorInterface::class)->validate($entity);

        // 应该有验证错误：缺少必填字段和必填关联（account、orderKey、payer、deliveryMobile等）
        // 验证错误信息类似于 "should not be blank"
        self::assertGreaterThan(
            0,
            $violations->count(),
            '实体应该有验证错误，因为缺少必填字段（deliveryMobile should not be blank）'
        );

        // 测试手机号格式验证 - 设置无效的手机号
        $entity->setDeliveryMobile('invalid-phone'); // 无效的手机号格式
        $entity->setTrackingNo('SF1234567890');
        $entity->setDeliveryCompany('顺丰速运');

        $violations = self::getService(ValidatorInterface::class)->validate($entity);

        // 查找deliveryMobile的正则验证错误
        $hasRegexError = false;
        foreach ($violations as $violation) {
            if ('deliveryMobile' === $violation->getPropertyPath()) {
                $hasRegexError = true;
                break;
            }
        }

        self::assertTrue(
            $hasRegexError,
            'deliveryMobile字段应该有正则验证错误（无效的手机号格式）'
        );
    }
}
