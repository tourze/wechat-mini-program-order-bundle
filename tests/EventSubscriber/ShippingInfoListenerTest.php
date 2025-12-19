<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\EventSubscriber\ShippingInfoListener;
use WechatMiniProgramOrderBundle\Request\UploadShippingInfoRequest;


#[CoversClass(ShippingInfoListener::class)]
#[RunTestsInSeparateProcesses]
final class ShippingInfoListenerTest extends AbstractEventSubscriberTestCase
{
    private Client $client;

    protected function onSetUp(): void
    {
        $this->client = $this->createMock(Client::class);

        // 注入 Mock 客户端到容器
        self::getContainer()->set(Client::class, $this->client);
    }

    public function testPrePersistShouldCreateRequestAndCallAsyncRequestWithCorrectData(): void
    {
        // 从容器中获取监听器实例
        $listener = self::getService(ShippingInfoListener::class);

        // Arrange
        $account = $this->createMock(Account::class);
        $shippingInfo = $this->createPartialMock(ShippingInfo::class, ['getAccount']);

        $shippingInfo->expects($this->once())
            ->method('getAccount')
            ->willReturn($account)
        ;

        // Assert
        $this->client->expects($this->once())
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
        // 从容器中获取监听器实例
        $listener = self::getService(ShippingInfoListener::class);

        // Arrange
        $shippingInfo = $this->createPartialMock(ShippingInfo::class, ['getAccount']);

        // Simulate \Error when accessing uninitialized account property
        $shippingInfo->expects($this->once())
            ->method('getAccount')
            ->willThrowException(new \Error('Typed property must not be accessed before initialization'))
        ;

        // Assert - client should not be called when Error occurs
        $this->client->expects($this->never())
            ->method('asyncRequest')
        ;

        // Act - should not throw exception due to Error handling
        $listener->prePersist($shippingInfo);

        // The test implicitly passes if no exception is thrown
    }

    public function testPrePersistShouldPropagateOtherExceptions(): void
    {
        // 从容器中获取监听器实例
        $listener = self::getService(ShippingInfoListener::class);

        // Arrange
        $shippingInfo = $this->createPartialMock(ShippingInfo::class, ['getAccount']);

        // Simulate other exception (not \Error)
        $shippingInfo->expects($this->once())
            ->method('getAccount')
            ->willThrowException(new \RuntimeException('Some other error'))
        ;

        // Assert - client should not be called when exception occurs
        $this->client->expects($this->never())
            ->method('asyncRequest')
        ;

        // Act & Assert - should propagate RuntimeException
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Some other error');

        $listener->prePersist($shippingInfo);
    }
}
