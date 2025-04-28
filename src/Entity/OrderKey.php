<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\DoctrineTimestampBundle\Attribute\UpdateTimeColumn;
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\DoctrineUserBundle\Attribute\UpdatedByColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Repository\OrderKeyRepository;

/**
 * 订单标识信息
 */
#[ORM\Entity(repositoryClass: OrderKeyRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_order_key', options: ['comment' => '订单标识信息表'])]
class OrderKey
{
    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[Groups(['restful_read', 'admin_curd', 'recursive_view', 'api_tree'])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[CreatedByColumn]
    #[Groups(['restful_read'])]
    #[ORM\Column(nullable: true, options: ['comment' => '创建人'])]
    private ?string $createdBy = null;

    #[UpdatedByColumn]
    #[Groups(['restful_read'])]
    #[ORM\Column(nullable: true, options: ['comment' => '更新人'])]
    private ?string $updatedBy = null;

    /**
     * 必填，订单单号类型
     * 用于确认需要上传详情的订单
     * 枚举值：
     * 1. USE_MCH_ORDER - 使用下单商户号和商户侧单号
     * 2. USE_WECHAT_ORDER - 使用微信支付单号
     */
    #[ORM\Column(type: 'integer', enumType: OrderNumberType::class, options: ['comment' => '订单单号类型：1-使用商户单号，2-使用微信单号'])]
    private OrderNumberType $orderNumberType = OrderNumberType::USE_MCH_ORDER;

    /**
     * 原支付交易对应的微信订单号
     */
    #[ORM\Column(length: 64, nullable: true, options: ['comment' => '原支付交易对应的微信订单号'])]
    private ?string $transactionId = null;

    /**
     * 支付下单商户的商户号，由微信支付生成并下发
     */
    #[ORM\Column(length: 64, nullable: true, options: ['comment' => '支付下单商户的商户号'])]
    private ?string $mchId = null;

    /**
     * 商户系统内部订单号
     * 只能是数字、大小写字母`_-*`且在同一个商户号下唯一
     */
    #[ORM\Column(length: 64, nullable: true, options: ['comment' => '商户系统内部订单号'])]
    private ?string $outTradeNo = null;

    #[Filterable]
    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[Groups(['restful_read', 'admin_curd', 'restful_read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[UpdateTimeColumn]
    #[ListColumn(order: 99, sorter: true)]
    #[Groups(['restful_read', 'admin_curd', 'restful_read'])]
    #[Filterable]
    #[ExportColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '更新时间'])]
    private ?\DateTimeInterface $updateTime = null;

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

    public function getOrderNumberType(): OrderNumberType
    {
        return $this->orderNumberType;
    }

    public function setOrderNumberType(OrderNumberType $orderNumberType): self
    {
        $this->orderNumberType = $orderNumberType;

        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getMchId(): ?string
    {
        return $this->mchId;
    }

    public function setMchId(?string $mchId): self
    {
        $this->mchId = $mchId;

        return $this;
    }

    public function getOutTradeNo(): ?string
    {
        return $this->outTradeNo;
    }

    public function setOutTradeNo(?string $outTradeNo): self
    {
        $this->outTradeNo = $outTradeNo;

        return $this;
    }

    public function setCreateTime(?\DateTimeInterface $createdAt): void
    {
        $this->createTime = $createdAt;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function setUpdateTime(?\DateTimeInterface $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->updateTime;
    }
}
