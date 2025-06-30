<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Repository\ShippingInfoRepository;

/**
 * 物流信息
 */
#[ORM\Entity(repositoryClass: ShippingInfoRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shipping_info', options: ['comment' => '物流信息表'])]
class ShippingInfo implements Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;


    #[TrackColumn]
    private ?bool $valid = false;

    /**
     * 必填，小程序账号
     */
    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '小程序账号'])]
    private Account $account;

    /**
     * 必填，订单信息
     */
    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '订单信息'])]
    private OrderKey $orderKey;

    /**
     * 必填，支付者信息
     */
    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '支付者信息'])]
    private UserInterface $payer;

    #[ORM\Column(type: Types::INTEGER, enumType: LogisticsType::class, options: ['comment' => '物流形式'])]
    private LogisticsType $logisticsType = LogisticsType::PHYSICAL_LOGISTICS;

    #[ORM\Column(type: Types::STRING, length: 128, options: ['comment' => '收件人手机号码'])]
    private string $deliveryMobile;

    #[ORM\Column(type: Types::STRING, length: 128, options: ['comment' => '物流单号'])]
    private string $trackingNo;

    #[ORM\Column(type: Types::STRING, length: 128, options: ['comment' => '物流公司名称'])]
    private string $deliveryCompany;


    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getOrderKey(): OrderKey
    {
        return $this->orderKey;
    }

    public function setOrderKey(OrderKey $orderKey): self
    {
        $this->orderKey = $orderKey;

        return $this;
    }

    public function getPayer(): UserInterface
    {
        return $this->payer;
    }

    public function setPayer(UserInterface $payer): self
    {
        $this->payer = $payer;

        return $this;
    }

    public function getLogisticsType(): LogisticsType
    {
        return $this->logisticsType;
    }

    public function setLogisticsType(LogisticsType $logisticsType): self
    {
        $this->logisticsType = $logisticsType;

        return $this;
    }

    public function getDeliveryMobile(): string
    {
        return $this->deliveryMobile;
    }

    public function setDeliveryMobile(string $deliveryMobile): self
    {
        $this->deliveryMobile = $deliveryMobile;

        return $this;
    }

    public function getTrackingNo(): string
    {
        return $this->trackingNo;
    }

    public function setTrackingNo(string $trackingNo): self
    {
        $this->trackingNo = $trackingNo;

        return $this;
    }

    public function getDeliveryCompany(): string
    {
        return $this->deliveryCompany;
    }

    public function setDeliveryCompany(string $deliveryCompany): self
    {
        $this->deliveryCompany = $deliveryCompany;

        return $this;
    }
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
