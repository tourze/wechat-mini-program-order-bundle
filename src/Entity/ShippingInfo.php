<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\DoctrineUserBundle\Attribute\UpdatedByColumn;
use Tourze\EasyAdmin\Attribute\Column\BoolColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Field\FormField;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Repository\ShippingInfoRepository;

/**
 * 物流信息
 */
#[ORM\Entity(repositoryClass: ShippingInfoRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shipping_info', options: ['comment' => '物流信息表'])]
class ShippingInfo
{
    use TimestampableAware;
    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[CreatedByColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '创建人'])]
    private ?string $createdBy = null;

    #[UpdatedByColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '更新人'])]
    private ?string $updatedBy = null;

    #[BoolColumn]
    #[IndexColumn]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[ListColumn(order: 97)]
    #[FormField(order: 97)]
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

    /**
     * 必填，物流形式
     */
    #[ORM\Column(type: 'integer', enumType: LogisticsType::class, options: ['comment' => '物流形式'])]
    private LogisticsType $logisticsType = LogisticsType::PHYSICAL_LOGISTICS;

    /**
     * 必填，收件人手机号码
     * 示例值: 13800138000
     * 字符字节限制: [1, 128]
     */
    #[ORM\Column(length: 128, options: ['comment' => '收件人手机号码'])]
    private string $deliveryMobile;

    /**
     * 必填，物流单号
     * 示例值: SF1234567890123
     * 字符字节限制: [1, 128]
     */
    #[ORM\Column(length: 128, options: ['comment' => '物流单号'])]
    private string $trackingNo;

    /**
     * 必填，物流公司名称
     * 示例值: 顺丰速运
     * 字符字节限制: [1, 128]
     */
    #[ORM\Column(length: 128, options: ['comment' => '物流公司名称'])]
    private string $deliveryCompany;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setUpdatedBy(?string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

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
    }}
