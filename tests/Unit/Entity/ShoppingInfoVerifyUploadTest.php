<?php

namespace WechatMiniProgramOrderBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfoVerifyUpload;
use WechatMiniProgramOrderBundle\Enum\ShoppingInfoVerifyStatus;

class ShoppingInfoVerifyUploadTest extends TestCase
{
    private ShoppingInfoVerifyUpload $shoppingInfoVerifyUpload;

    protected function setUp(): void
    {
        $this->shoppingInfoVerifyUpload = new ShoppingInfoVerifyUpload();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->shoppingInfoVerifyUpload->getId());
    }

    public function testGetterAndSetterForOrderId(): void
    {
        $this->assertNull($this->shoppingInfoVerifyUpload->getOrderId());
        
        $orderId = 'order-123';
        $this->shoppingInfoVerifyUpload->setOrderId($orderId);
        $this->assertSame($orderId, $this->shoppingInfoVerifyUpload->getOrderId());
    }

    public function testGetterAndSetterForOutOrderId(): void
    {
        $this->assertNull($this->shoppingInfoVerifyUpload->getOutOrderId());
        
        $outOrderId = 'out-order-456';
        $this->shoppingInfoVerifyUpload->setOutOrderId($outOrderId);
        $this->assertSame($outOrderId, $this->shoppingInfoVerifyUpload->getOutOrderId());
    }

    public function testGetterAndSetterForPathId(): void
    {
        $this->assertNull($this->shoppingInfoVerifyUpload->getPathId());
        
        $pathId = 'path-789';
        $this->shoppingInfoVerifyUpload->setPathId($pathId);
        $this->assertSame($pathId, $this->shoppingInfoVerifyUpload->getPathId());
    }

    public function testGetterAndSetterForStatus(): void
    {
        $this->assertSame(ShoppingInfoVerifyStatus::PENDING, $this->shoppingInfoVerifyUpload->getStatus());
        
        $this->shoppingInfoVerifyUpload->setStatus(ShoppingInfoVerifyStatus::VERIFIED);
        $this->assertSame(ShoppingInfoVerifyStatus::VERIFIED, $this->shoppingInfoVerifyUpload->getStatus());
        
        $this->shoppingInfoVerifyUpload->setStatus(ShoppingInfoVerifyStatus::FAILED);
        $this->assertSame(ShoppingInfoVerifyStatus::FAILED, $this->shoppingInfoVerifyUpload->getStatus());
    }

    public function testGetterAndSetterForFailReason(): void
    {
        $this->assertNull($this->shoppingInfoVerifyUpload->getFailReason());
        
        $failReason = 'Validation failed: missing required field';
        $this->shoppingInfoVerifyUpload->setFailReason($failReason);
        $this->assertSame($failReason, $this->shoppingInfoVerifyUpload->getFailReason());
    }

    public function testGetterAndSetterForResultData(): void
    {
        $this->assertNull($this->shoppingInfoVerifyUpload->getResultData());
        
        $resultData = [
            'code' => 0,
            'message' => 'Success',
            'data' => ['order_id' => '123']
        ];
        $this->shoppingInfoVerifyUpload->setResultData($resultData);
        $this->assertSame($resultData, $this->shoppingInfoVerifyUpload->getResultData());
    }

    public function testFluentInterfaces(): void
    {
        $result = $this->shoppingInfoVerifyUpload->setOrderId('order-123');
        $this->assertSame($this->shoppingInfoVerifyUpload, $result);
        
        $result = $this->shoppingInfoVerifyUpload->setOutOrderId('out-order-456');
        $this->assertSame($this->shoppingInfoVerifyUpload, $result);
        
        $result = $this->shoppingInfoVerifyUpload->setPathId('path-789');
        $this->assertSame($this->shoppingInfoVerifyUpload, $result);
        
        $result = $this->shoppingInfoVerifyUpload->setStatus(ShoppingInfoVerifyStatus::VERIFIED);
        $this->assertSame($this->shoppingInfoVerifyUpload, $result);
        
        $result = $this->shoppingInfoVerifyUpload->setFailReason('Test failure');
        $this->assertSame($this->shoppingInfoVerifyUpload, $result);
        
        $result = $this->shoppingInfoVerifyUpload->setResultData(['test' => 'data']);
        $this->assertSame($this->shoppingInfoVerifyUpload, $result);
    }

    public function testToString(): void
    {
        $result = (string) $this->shoppingInfoVerifyUpload;
        $this->assertSame('', $result);
    }
}