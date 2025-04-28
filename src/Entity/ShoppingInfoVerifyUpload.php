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
use WechatMiniProgramOrderBundle\Enum\ShoppingInfoVerifyStatus;
use WechatMiniProgramOrderBundle\Repository\ShoppingInfoVerifyUploadRepository;

#[ORM\Entity(repositoryClass: ShoppingInfoVerifyUploadRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shopping_info_verify_upload')]
class ShoppingInfoVerifyUpload
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

    #[ORM\Column(length: 64, options: ['comment' => '订单ID'])]
    private ?string $orderId = null;

    #[ORM\Column(length: 64, options: ['comment' => '商户订单ID'])]
    private ?string $outOrderId = null;

    #[ORM\Column(length: 64, options: ['comment' => '路径ID'])]
    private ?string $pathId = null;

    #[ORM\Column(type: 'string', enumType: ShoppingInfoVerifyStatus::class, options: ['comment' => '验证状态'])]
    private ShoppingInfoVerifyStatus $status = ShoppingInfoVerifyStatus::PENDING;

    #[ORM\Column(type: 'text', nullable: true, options: ['comment' => '验证失败原因'])]
    private ?string $failReason = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '验证结果数据'])]
    private ?array $resultData = null;

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

    public function getStatus(): ShoppingInfoVerifyStatus
    {
        return $this->status;
    }

    public function setStatus(ShoppingInfoVerifyStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFailReason(): ?string
    {
        return $this->failReason;
    }

    public function setFailReason(?string $failReason): self
    {
        $this->failReason = $failReason;

        return $this;
    }

    public function getResultData(): ?array
    {
        return $this->resultData;
    }

    public function setResultData(?array $resultData): self
    {
        $this->resultData = $resultData;

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
