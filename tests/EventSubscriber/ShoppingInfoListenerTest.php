<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\EventSubscriber\ShoppingInfoListener;
use WechatMiniProgramOrderBundle\Request\UploadShoppingInfoRequest;
use Tourze\WechatMiniProgramUserContracts\UserInterface;

/**
 * @internal
 */
#[CoversClass(ShoppingInfoListener::class)]
final class ShoppingInfoListenerTest extends TestCase
{
    public function testPrePersistShouldCreateRequestAndCallAsyncRequestWithCorrectData(): void
    {
        // Arrange
        $client = $this->createMock(Client::class);
        $listener = new ShoppingInfoListener($client);

        // 创建真实的 ShoppingInfo 实体，避免 Mock
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
        $orderKey->setTransactionId('tx-123456');
        $orderKey->setMchId('mch-123456');
        $orderKey->setOutTradeNo('out-trade-123456');

        $payer = $this->createMock(UserInterface::class);
        $payer->method('getOpenId')->willReturn('openid-123456');

        $account = $this->createMock(Account::class);

        $shoppingInfo = new ShoppingInfo();
        $shoppingInfo->setOrderKey($orderKey);
        $shoppingInfo->setPayer($payer);
        $shoppingInfo->setAccount($account);
        $shoppingInfo->setOrderDetailType(OrderDetailType::MINI_PROGRAM);
        $shoppingInfo->setOrderDetailPath('/pages/order/detail');
        $shoppingInfo->setLogisticsType(LogisticsType::VIRTUAL_GOODS);

        // Assert
        $client->expects($this->once())
            ->method('asyncRequest')
            ->with(self::callback(function ($request) use ($shoppingInfo) {
                if (!($request instanceof UploadShoppingInfoRequest)) {
                    return false;
                }

                return $request->getShoppingInfo() === $shoppingInfo;
            }))
        ;

        // Act
        $listener->prePersist($shoppingInfo);
    }

    public function testPrePersistShouldCreateUploadShoppingInfoRequestInstance(): void
    {
        // Arrange
        $client = $this->createMock(Client::class);
        $listener = new ShoppingInfoListener($client);

        // 创建真实的 ShoppingInfo 实体，避免 Mock
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);

        $payer = $this->createMock(UserInterface::class);
        $payer->method('getOpenId')->willReturn('openid-123456');

        $account = $this->createMock(Account::class);

        $shoppingInfo = new ShoppingInfo();
        $shoppingInfo->setOrderKey($orderKey);
        $shoppingInfo->setPayer($payer);
        $shoppingInfo->setAccount($account);

        // Assert - verify the request is of correct type
        $client->expects($this->once())
            ->method('asyncRequest')
            ->with(Assert::isInstanceOf(UploadShoppingInfoRequest::class))
        ;

        // Act
        $listener->prePersist($shoppingInfo);
    }

    public function testPrePersistShouldHandleExceptionFromAsyncRequest(): void
    {
        // Arrange
        $client = $this->createMock(Client::class);
        $listener = new ShoppingInfoListener($client);

        // 创建真实的 ShoppingInfo 实体，避免 Mock
        $orderKey = new OrderKey();
        $orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);

        $payer = $this->createMock(UserInterface::class);
        $payer->method('getOpenId')->willReturn('openid-123456');

        $account = $this->createMock(Account::class);

        $shoppingInfo = new ShoppingInfo();
        $shoppingInfo->setOrderKey($orderKey);
        $shoppingInfo->setPayer($payer);
        $shoppingInfo->setAccount($account);

        // Simulate exception from asyncRequest
        $client->expects($this->once())
            ->method('asyncRequest')
            ->willThrowException(new \RuntimeException('Network error'))
        ;

        // Act & Assert - exception should be propagated
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Network error');

        $listener->prePersist($shoppingInfo);
    }
}
