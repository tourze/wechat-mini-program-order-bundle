<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramOrderBundle\Controller\OrderKeyCrudController;
use WechatMiniProgramOrderBundle\Entity\OrderKey;

/**
 * @internal
 */
#[CoversClass(OrderKeyCrudController::class)]
#[RunTestsInSeparateProcesses]
class OrderKeyCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testConfigureFields(): void
    {
        $controller = new OrderKeyCrudController();
        $fields = iterator_to_array($controller->configureFields('index'));

        $this->assertNotEmpty($fields);
    }

    protected function getControllerService(): OrderKeyCrudController
    {
        return self::getService(OrderKeyCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '订单号类型' => ['订单号类型'];
        yield '微信订单号' => ['微信订单号'];
        yield '商户号' => ['商户号'];
        yield '商户订单号' => ['商户订单号'];
        yield '订单ID' => ['订单ID'];
        yield '外部订单ID' => ['外部订单ID'];
        yield '用户OpenID' => ['用户OpenID'];
        yield '路径ID' => ['路径ID'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        // 排除hideOnForm的字段：id, createTime, updateTime - 使用字段名而不是标签
        yield 'orderNumberType' => ['orderNumberType'];
        yield 'transactionId' => ['transactionId'];
        yield 'mchId' => ['mchId'];
        yield 'outTradeNo' => ['outTradeNo'];
        yield 'orderId' => ['orderId'];
        yield 'outOrderId' => ['outOrderId'];
        yield 'openid' => ['openid'];
        yield 'pathId' => ['pathId'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        // 排除hideOnForm的字段：id, createTime, updateTime - 使用字段名而不是标签
        yield 'orderNumberType' => ['orderNumberType'];
        yield 'transactionId' => ['transactionId'];
        yield 'mchId' => ['mchId'];
        yield 'outTradeNo' => ['outTradeNo'];
        yield 'orderId' => ['orderId'];
        yield 'outOrderId' => ['outOrderId'];
        yield 'openid' => ['openid'];
        yield 'pathId' => ['pathId'];
    }

    /**
     * 测试表单验证错误 - 提交无效数据应该显示验证错误
     *
     * OrderKey实体验证约束：
     * - transactionId: Length(max: 64)
     * - mchId: Length(max: 64)
     * - outTradeNo: Length(max: 64)
     * - orderId: Length(max: 64)
     * - outOrderId: Length(max: 64)
     * - openid: Length(max: 64)
     * - pathId: Length(max: 64)
     */
    public function testValidationErrors(): void
    {
        $entity = new OrderKey();

        // 测试字符串长度约束 - 设置超长字符串
        $longString = str_repeat('x', 65); // 超过64字符限制
        $entity->setTransactionId($longString);
        $entity->setMchId($longString);
        $entity->setOutTradeNo($longString);
        $entity->setOrderId($longString);
        $entity->setOutOrderId($longString);
        $entity->setOpenid($longString);
        $entity->setPathId($longString);

        // 使用验证器服务直接测试实体验证约束
        $violations = self::getService(ValidatorInterface::class)->validate($entity);

        // 应该有多个长度验证错误（每个字段都超过64字符）
        // 验证错误信息类似于 "should not be blank" 或长度错误
        self::assertGreaterThanOrEqual(
            7,
            $violations->count(),
            '实体应该有至少7个验证错误，因为所有字段都超过了64字符的长度限制'
        );
    }
}
