<?php

namespace WechatMiniProgramOrderBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;

class ShippingInfoTest extends TestCase
{
    private ShippingInfo $shippingInfo;

    protected function setUp(): void
    {
        $this->shippingInfo = new ShippingInfo();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->shippingInfo->getId());
    }

    public function testGetterAndSetterForValid(): void
    {
        $this->assertFalse($this->shippingInfo->isValid());
        
        $this->shippingInfo->setValid(true);
        $this->assertTrue($this->shippingInfo->isValid());
        
        $this->shippingInfo->setValid(null);
        $this->assertNull($this->shippingInfo->isValid());
    }

    public function testGetterAndSetterForAccount(): void
    {
        $account = $this->createMock(Account::class);
        
        $this->shippingInfo->setAccount($account);
        $this->assertSame($account, $this->shippingInfo->getAccount());
    }

    public function testGetterAndSetterForOrderKey(): void
    {
        $orderKey = $this->createMock(OrderKey::class);
        
        $this->shippingInfo->setOrderKey($orderKey);
        $this->assertSame($orderKey, $this->shippingInfo->getOrderKey());
    }

    public function testGetterAndSetterForPayer(): void
    {
        $payer = $this->createMock(UserInterface::class);
        
        $this->shippingInfo->setPayer($payer);
        $this->assertSame($payer, $this->shippingInfo->getPayer());
    }

    public function testGetterAndSetterForLogisticsType(): void
    {
        $this->assertSame(LogisticsType::PHYSICAL_LOGISTICS, $this->shippingInfo->getLogisticsType());
        
        $this->shippingInfo->setLogisticsType(LogisticsType::VIRTUAL_GOODS);
        $this->assertSame(LogisticsType::VIRTUAL_GOODS, $this->shippingInfo->getLogisticsType());
    }

    public function testGetterAndSetterForDeliveryMobile(): void
    {
        $deliveryMobile = '13800138000';
        $this->shippingInfo->setDeliveryMobile($deliveryMobile);
        $this->assertSame($deliveryMobile, $this->shippingInfo->getDeliveryMobile());
    }

    public function testGetterAndSetterForTrackingNo(): void
    {
        $trackingNo = '1234567890';
        $this->shippingInfo->setTrackingNo($trackingNo);
        $this->assertSame($trackingNo, $this->shippingInfo->getTrackingNo());
    }

    public function testGetterAndSetterForDeliveryCompany(): void
    {
        $deliveryCompany = '顺丰快递';
        $this->shippingInfo->setDeliveryCompany($deliveryCompany);
        $this->assertSame($deliveryCompany, $this->shippingInfo->getDeliveryCompany());
    }

    public function testFluentInterfaces(): void
    {
        $account = $this->createMock(Account::class);
        $orderKey = $this->createMock(OrderKey::class);
        $payer = $this->createMock(UserInterface::class);
        
        $result = $this->shippingInfo->setValid(true);
        $this->assertSame($this->shippingInfo, $result);
        
        $result = $this->shippingInfo->setAccount($account);
        $this->assertSame($this->shippingInfo, $result);
        
        $result = $this->shippingInfo->setOrderKey($orderKey);
        $this->assertSame($this->shippingInfo, $result);
        
        $result = $this->shippingInfo->setPayer($payer);
        $this->assertSame($this->shippingInfo, $result);
        
        $result = $this->shippingInfo->setLogisticsType(LogisticsType::VIRTUAL_GOODS);
        $this->assertSame($this->shippingInfo, $result);
        
        $result = $this->shippingInfo->setDeliveryMobile('13800138000');
        $this->assertSame($this->shippingInfo, $result);
        
        $result = $this->shippingInfo->setTrackingNo('1234567890');
        $this->assertSame($this->shippingInfo, $result);
        
        $result = $this->shippingInfo->setDeliveryCompany('顺丰快递');
        $this->assertSame($this->shippingInfo, $result);
    }

    public function testToString(): void
    {
        $result = (string) $this->shippingInfo;
        $this->assertSame('', $result);
    }
}