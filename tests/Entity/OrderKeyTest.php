<?php

namespace WechatMiniProgramOrderBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;

class OrderKeyTest extends TestCase
{
    private OrderKey $orderKey;

    protected function setUp(): void
    {
        $this->orderKey = new OrderKey();
    }

    public function testGetterAndSetterForOrderNumberType(): void
    {
        $this->assertSame(OrderNumberType::USE_MCH_ORDER, $this->orderKey->getOrderNumberType());
        
        $this->orderKey->setOrderNumberType(OrderNumberType::USE_WECHAT_ORDER);
        $this->assertSame(OrderNumberType::USE_WECHAT_ORDER, $this->orderKey->getOrderNumberType());
    }

    public function testGetterAndSetterForTransactionId(): void
    {
        $this->assertNull($this->orderKey->getTransactionId());
        
        $transactionId = 'transaction-123';
        $this->orderKey->setTransactionId($transactionId);
        $this->assertSame($transactionId, $this->orderKey->getTransactionId());
    }

    public function testGetterAndSetterForMchId(): void
    {
        $this->assertNull($this->orderKey->getMchId());
        
        $mchId = 'merchant-456';
        $this->orderKey->setMchId($mchId);
        $this->assertSame($mchId, $this->orderKey->getMchId());
    }

    public function testGetterAndSetterForOutTradeNo(): void
    {
        $this->assertNull($this->orderKey->getOutTradeNo());
        
        $outTradeNo = 'OUT-TRADE-789';
        $this->orderKey->setOutTradeNo($outTradeNo);
        $this->assertSame($outTradeNo, $this->orderKey->getOutTradeNo());
    }
    
    public function testGetterAndSetterForCreatedBy(): void
    {
        $this->assertNull($this->orderKey->getCreatedBy());
        
        $createdBy = 'admin-user';
        $this->orderKey->setCreatedBy($createdBy);
        $this->assertSame($createdBy, $this->orderKey->getCreatedBy());
    }

    public function testGetterAndSetterForUpdatedBy(): void
    {
        $this->assertNull($this->orderKey->getUpdatedBy());
        
        $updatedBy = 'editor-user';
        $this->orderKey->setUpdatedBy($updatedBy);
        $this->assertSame($updatedBy, $this->orderKey->getUpdatedBy());
    }

    public function testGetterAndSetterForCreateTime(): void
    {
        $this->assertNull($this->orderKey->getCreateTime());
        
        $createTime = new \DateTimeImmutable();
        $this->orderKey->setCreateTime($createTime);
        $this->assertSame($createTime, $this->orderKey->getCreateTime());
    }

    public function testGetterAndSetterForUpdateTime(): void
    {
        $this->assertNull($this->orderKey->getUpdateTime());
        
        $updateTime = new \DateTimeImmutable();
        $this->orderKey->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $this->orderKey->getUpdateTime());
    }

    public function testGetId(): void
    {
        $this->assertNull($this->orderKey->getId());
    }

    public function testFluentInterfaces(): void
    {
        $returnedOrderKey = $this->orderKey->setOrderNumberType(OrderNumberType::USE_WECHAT_ORDER);
        $this->assertSame($this->orderKey, $returnedOrderKey);
        
        $returnedOrderKey = $this->orderKey->setTransactionId('transaction-123');
        $this->assertSame($this->orderKey, $returnedOrderKey);
        
        $returnedOrderKey = $this->orderKey->setMchId('merchant-456');
        $this->assertSame($this->orderKey, $returnedOrderKey);
        
        $returnedOrderKey = $this->orderKey->setOutTradeNo('OUT-TRADE-789');
        $this->assertSame($this->orderKey, $returnedOrderKey);
        
        $returnedOrderKey = $this->orderKey->setCreatedBy('admin-user');
        $this->assertSame($this->orderKey, $returnedOrderKey);
        
        $returnedOrderKey = $this->orderKey->setUpdatedBy('editor-user');
        $this->assertSame($this->orderKey, $returnedOrderKey);
    }
}
