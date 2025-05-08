<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\DoctrineTimestampBundle\Attribute\UpdateTimeColumn;
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\DoctrineUserBundle\Attribute\UpdatedByColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Entity\User;
use WechatMiniProgramOrderBundle\Repository\CombinedShoppingInfoRepository;

/**
 * 合单购物信息
 */
#[ORM\Entity(repositoryClass: CombinedShoppingInfoRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_combined_shopping_info', options: ['comment' => '合单购物信息表'])]
class CombinedShoppingInfo
{
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

    /**
     * 必填，小程序账号
     */
    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '小程序账号'])]
    private Account $account;

    #[ORM\Column(length: 64)]
    private ?string $orderId = null;

    #[ORM\Column(length: 64)]
    private ?string $outOrderId = null;

    #[ORM\Column(length: 64)]
    private ?string $pathId = null;

    #[ORM\Column(length: 32)]
    private ?string $status = null;

    #[ORM\Column(type: 'integer')]
    private ?int $totalAmount = null;

    #[ORM\Column(type: 'integer')]
    private ?int $payAmount = null;

    #[ORM\Column(type: 'integer')]
    private ?int $discountAmount = null;

    #[ORM\Column(type: 'integer')]
    private ?int $freightAmount = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $payer = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Contact $contact = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ShippingInfo $shippingInfo = null;

    #[Filterable]
    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[UpdateTimeColumn]
    #[ListColumn(order: 99, sorter: true)]
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

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(?string $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getOutOrderId(): ?string
    {
        return $this->outOrderId;
    }

    public function setOutOrderId(?string $outOrderId): self
    {
        $this->outOrderId = $outOrderId;

        return $this;
    }

    public function getPathId(): ?string
    {
        return $this->pathId;
    }

    public function setPathId(?string $pathId): self
    {
        $this->pathId = $pathId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(?int $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getPayAmount(): ?int
    {
        return $this->payAmount;
    }

    public function setPayAmount(?int $payAmount): self
    {
        $this->payAmount = $payAmount;

        return $this;
    }

    public function getDiscountAmount(): ?int
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(?int $discountAmount): self
    {
        $this->discountAmount = $discountAmount;

        return $this;
    }

    public function getFreightAmount(): ?int
    {
        return $this->freightAmount;
    }

    public function setFreightAmount(?int $freightAmount): self
    {
        $this->freightAmount = $freightAmount;

        return $this;
    }

    public function getPayer(): ?User
    {
        return $this->payer;
    }

    public function setPayer(?User $payer): self
    {
        $this->payer = $payer;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getShippingInfo(): ?ShippingInfo
    {
        return $this->shippingInfo;
    }

    public function setShippingInfo(?ShippingInfo $shippingInfo): self
    {
        $this->shippingInfo = $shippingInfo;

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
