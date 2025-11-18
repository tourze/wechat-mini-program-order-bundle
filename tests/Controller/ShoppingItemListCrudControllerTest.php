<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramOrderBundle\Controller\ShoppingItemListCrudController;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;

/**
 * @internal
 */
#[CoversClass(ShoppingItemListCrudController::class)]
#[RunTestsInSeparateProcesses]
class ShoppingItemListCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testConfigureFields(): void
    {
        $controller = new ShoppingItemListCrudController();
        $fields = iterator_to_array($controller->configureFields('index'));

        $this->assertNotEmpty($fields);
    }

    public function testControllerCanBeInstantiated(): void
    {
        $controller = new ShoppingItemListCrudController();
        $this->assertInstanceOf(ShoppingItemListCrudController::class, $controller);
    }

    protected function getControllerService(): ShoppingItemListCrudController
    {
        return self::getService(ShoppingItemListCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '商品ID' => ['商品ID'];
        yield '商品名称' => ['商品名称'];
        yield '商品数量' => ['商品数量'];
        yield '商品单价' => ['商品单价'];
        yield '商品总价' => ['商品总价'];
        yield '所属购物信息' => ['所属购物信息'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'merchantItemId' => ['merchantItemId'];
        yield 'itemName' => ['itemName'];
        yield 'itemCount' => ['itemCount'];
        yield 'itemPrice' => ['itemPrice'];
        yield 'itemAmount' => ['itemAmount'];
        yield 'shoppingInfo' => ['shoppingInfo'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'merchantItemId' => ['merchantItemId'];
        yield 'itemName' => ['itemName'];
        yield 'itemCount' => ['itemCount'];
        yield 'itemPrice' => ['itemPrice'];
        yield 'itemAmount' => ['itemAmount'];
        yield 'shoppingInfo' => ['shoppingInfo'];
    }

    /**
     * 测试表单验证错误 - 提交无效数据应该显示验证错误
     *
     * ShoppingItemList实体验证约束：
     * - merchantItemId: NotBlank + Length(max: 128)
     * - itemName: NotBlank + Length(max: 128)
     * - itemCount: NotNull + PositiveOrZero
     * - itemPrice: NotBlank + Regex + Length(max: 13)
     * - itemAmount: NotBlank + Regex + Length(max: 13)
     */
    public function testValidationErrors(): void
    {
        $entity = new ShoppingItemList();

        // 使用验证器服务直接测试实体验证约束
        $violations = self::getService(ValidatorInterface::class)->validate($entity);

        // 应该有验证错误：缺少必填字段（merchantItemId、itemName、itemCount等）
        // 验证错误信息类似于 "should not be blank"
        self::assertGreaterThan(
            0,
            $violations->count(),
            '实体应该有验证错误，因为缺少必填字段（merchantItemId、itemName should not be blank）'
        );

        // 测试字符串长度约束 - 设置超长字符串
        $longString = str_repeat('x', 129); // 超过128字符限制
        $entity->setMerchantItemId($longString);
        $entity->setItemName($longString);
        $entity->setItemCount(10);
        $entity->setItemPrice('99.99');
        $entity->setItemAmount('999.90');

        $violations = self::getService(ValidatorInterface::class)->validate($entity);

        // 查找长度验证错误
        $hasLengthError = false;
        foreach ($violations as $violation) {
            if (in_array($violation->getPropertyPath(), ['merchantItemId', 'itemName'], true)) {
                $hasLengthError = true;
                break;
            }
        }

        self::assertTrue(
            $hasLengthError,
            '应该有字段长度验证错误（超过128字符限制）'
        );

        // 测试价格格式约束 - 设置无效的价格格式
        $entity->setMerchantItemId('test-item-id');
        $entity->setItemName('Test Item');
        $entity->setItemPrice('invalid-price'); // 无效的价格格式

        $violations = self::getService(ValidatorInterface::class)->validate($entity);

        // 查找价格格式验证错误
        $hasPriceFormatError = false;
        foreach ($violations as $violation) {
            if ('itemPrice' === $violation->getPropertyPath()) {
                $hasPriceFormatError = true;
                break;
            }
        }

        self::assertTrue(
            $hasPriceFormatError,
            'itemPrice字段应该有格式验证错误（无效的价格格式）'
        );
    }
}
