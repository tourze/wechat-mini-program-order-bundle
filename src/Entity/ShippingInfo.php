<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Repository\ShippingInfoRepository;

/**
 * 物流信息
 */
#[ORM\Entity(repositoryClass: ShippingInfoRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shipping_info', options: ['comment' => '物流信息表'])]
class ShippingInfo implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效状态'])]
    #[TrackColumn]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    /**
     * 必填，小程序账号
     */
    #[ORM\ManyToOne(targetEntity: MiniProgramInterface::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '小程序账号'])]
    private MiniProgramInterface $account;

    /**
     * 必填，订单信息
     */
    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '订单信息'])]
    private OrderKey $orderKey;

    /**
     * 必填，支付者信息
     */
    #[ORM\ManyToOne(targetEntity: UserInterface::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '支付者信息'])]
    private UserInterface $payer;

    #[ORM\Column(type: Types::INTEGER, enumType: LogisticsType::class, options: ['comment' => '物流形式'])]
    #[Assert\Choice(callback: [LogisticsType::class, 'cases'])]
    private LogisticsType $logisticsType = LogisticsType::PHYSICAL_LOGISTICS;

    #[ORM\Column(type: Types::STRING, length: 128, options: ['comment' => '收件人手机号码'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    #[Assert\Regex(pattern: '/^1[3-9]\d{9}$/')]
    private string $deliveryMobile;

    #[ORM\Column(type: Types::STRING, length: 128, options: ['comment' => '物流单号'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    private string $trackingNo;

    #[ORM\Column(type: Types::STRING, length: 128, options: ['comment' => '物流公司名称'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    private string $deliveryCompany;

    #[ORM\Column(type: Types::STRING, length: 128, nullable: true, options: ['comment' => '快递公司名称'])]
    #[Assert\Length(max: 128)]
    private ?string $expressCompany = null;

    #[ORM\Column(type: Types::STRING, length: 128, nullable: true, options: ['comment' => '收件人姓名'])]
    #[Assert\Length(max: 128)]
    private ?string $deliveryName = null;

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    public function getAccount(): MiniProgramInterface
    {
        return $this->account;
    }

    public function setAccount(MiniProgramInterface $account): void
    {
        $this->account = $account;
    }

    public function getOrderKey(): OrderKey
    {
        return $this->orderKey;
    }

    public function setOrderKey(OrderKey $orderKey): void
    {
        $this->orderKey = $orderKey;
    }

    public function getPayer(): UserInterface
    {
        return $this->payer;
    }

    public function setPayer(UserInterface $payer): void
    {
        $this->payer = $payer;
    }

    public function getLogisticsType(): LogisticsType
    {
        return $this->logisticsType;
    }

    public function setLogisticsType(LogisticsType $logisticsType): void
    {
        $this->logisticsType = $logisticsType;
    }

    public function getDeliveryMobile(): string
    {
        return $this->deliveryMobile;
    }

    public function setDeliveryMobile(string $deliveryMobile): void
    {
        $this->deliveryMobile = $deliveryMobile;
    }

    public function getTrackingNo(): string
    {
        return $this->trackingNo;
    }

    public function setTrackingNo(string $trackingNo): void
    {
        $this->trackingNo = $trackingNo;
    }

    public function getDeliveryCompany(): string
    {
        return $this->deliveryCompany;
    }

    public function setDeliveryCompany(string $deliveryCompany): void
    {
        $this->deliveryCompany = $deliveryCompany;
    }

    public function getExpressCompany(): ?string
    {
        return $this->expressCompany;
    }

    public function setExpressCompany(?string $expressCompany): void
    {
        $this->expressCompany = $expressCompany;
    }

    public function getDeliveryName(): ?string
    {
        return $this->deliveryName;
    }

    public function setDeliveryName(?string $deliveryName): void
    {
        $this->deliveryName = $deliveryName;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
