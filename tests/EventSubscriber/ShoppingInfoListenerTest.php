<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\EventSubscriber\ShoppingInfoListener;
use WechatMiniProgramOrderBundle\Request\UploadShoppingInfoRequest;

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

        $account = $this->createMock(Account::class);
        $shoppingInfo = $this->createPartialMock(ShoppingInfo::class, ['getAccount']);

        $shoppingInfo->expects($this->once())
            ->method('getAccount')
            ->willReturn($account)
        ;

        // Assert
        $client->expects($this->once())
            ->method('asyncRequest')
            ->with(self::callback(function ($request) use ($account, $shoppingInfo) {
                if (!($request instanceof UploadShoppingInfoRequest)) {
                    return false;
                }

                return $request->getAccount() === $account
                    && $request->getShoppingInfo() === $shoppingInfo;
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

        $account = $this->createMock(Account::class);
        $shoppingInfo = $this->createMock(ShoppingInfo::class);

        $shoppingInfo->expects($this->once())
            ->method('getAccount')
            ->willReturn($account)
        ;

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

        $account = $this->createMock(Account::class);
        $shoppingInfo = $this->createMock(ShoppingInfo::class);

        $shoppingInfo->expects($this->once())
            ->method('getAccount')
            ->willReturn($account)
        ;

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
