<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\EventSubscriber\ShippingInfoListener;
use WechatMiniProgramOrderBundle\Request\UploadShippingInfoRequest;

/**
 * @internal
 * @phpstan-ignore-next-line tourze.serviceTestShouldExtendIntegrationTestCase
 */
#[CoversClass(ShippingInfoListener::class)]
final class ShippingInfoListenerTest extends TestCase
{
    public function testPrePersistShouldCreateRequestAndCallAsyncRequestWithCorrectData(): void
    {
        // Arrange
        $client = $this->createMock(Client::class);
        $listener = new ShippingInfoListener($client);

        $account = $this->createMock(Account::class);
        $shippingInfo = $this->createPartialMock(ShippingInfo::class, ['getAccount']);

        $shippingInfo->expects($this->once())
            ->method('getAccount')
            ->willReturn($account)
        ;

        // Assert
        $client->expects($this->once())
            ->method('asyncRequest')
            ->with(self::callback(function ($request) use ($account, $shippingInfo) {
                if (!($request instanceof UploadShippingInfoRequest)) {
                    return false;
                }

                return $request->getAccount() === $account
                    && $request->getShippingInfo() === $shippingInfo;
            }))
        ;

        // Act
        $listener->prePersist($shippingInfo);
    }

    public function testPrePersistShouldIgnoreErrorWhenAccountNotInitialized(): void
    {
        // Arrange
        $client = $this->createMock(Client::class);
        $listener = new ShippingInfoListener($client);

        $shippingInfo = $this->createPartialMock(ShippingInfo::class, ['getAccount']);

        // Simulate \Error when accessing uninitialized account property
        $shippingInfo->expects($this->once())
            ->method('getAccount')
            ->willThrowException(new \Error('Typed property must not be accessed before initialization'))
        ;

        // Assert - client should not be called when Error occurs
        $client->expects($this->never())
            ->method('asyncRequest')
        ;

        // Act - should not throw exception due to Error handling
        $listener->prePersist($shippingInfo);

        // The test implicitly passes if no exception is thrown
    }

    public function testPrePersistShouldPropagateOtherExceptions(): void
    {
        // Arrange
        $client = $this->createMock(Client::class);
        $listener = new ShippingInfoListener($client);

        $shippingInfo = $this->createPartialMock(ShippingInfo::class, ['getAccount']);

        // Simulate other exception (not \Error)
        $shippingInfo->expects($this->once())
            ->method('getAccount')
            ->willThrowException(new \RuntimeException('Some other error'))
        ;

        // Assert - client should not be called when exception occurs
        $client->expects($this->never())
            ->method('asyncRequest')
        ;

        // Act & Assert - should propagate RuntimeException
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Some other error');

        $listener->prePersist($shippingInfo);
    }
}
