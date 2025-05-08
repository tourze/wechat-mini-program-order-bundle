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
use WechatMiniProgramOrderBundle\Repository\ShoppingItemListRepository;

/**
 * 购物商品列表项
 */
#[ORM\Entity(repositoryClass: ShoppingItemListRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shopping_item_list', options: ['comment' => '购物商品列表项表'])]
class ShoppingItemList
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
     * 必填，商品ID
     * 示例值: 123456
     * 字符字节限制: [1, 128]
     */
    #[ORM\Column(length: 128, options: ['comment' => '商品ID'])]
    private ?string $merchantItemId = null;

    /**
     * 必填，商品名称
     * 示例值: 纯色白色短袖T恤
     * 字符字节限制: [1, 128]
     */
    #[ORM\Column(length: 128, options: ['comment' => '商品名称'])]
    private ?string $itemName = null;

    /**
     * 必填，商品数量
     * 示例值: 2
     */
    #[ORM\Column(type: 'integer', options: ['comment' => '商品数量'])]
    private ?int $itemCount = null;

    /**
     * 必填，商品单价，单位：元
     * 示例值: 123.45
     */
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, options: ['comment' => '商品单价，单位：元'])]
    private ?string $itemPrice = null;

    /**
     * 必填，商品总价，单位：元
     * 示例值: 246.90
     */
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, options: ['comment' => '商品总价，单位：元'])]
    private ?string $itemAmount = null;

    /**
     * 所属购物信息
     */
    #[ORM\ManyToOne(inversedBy: 'itemList')]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属购物信息'])]
    private ?ShoppingInfo $shoppingInfo = null;

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

    public function getMerchantItemId(): ?string
    {
        return $this->merchantItemId;
    }

    public function setMerchantItemId(?string $merchantItemId): self
    {
        $this->merchantItemId = $merchantItemId;

        return $this;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(?string $itemName): self
    {
        $this->itemName = $itemName;

        return $this;
    }

    public function getItemCount(): ?int
    {
        return $this->itemCount;
    }

    public function setItemCount(?int $itemCount): self
    {
        $this->itemCount = $itemCount;

        return $this;
    }

    public function getItemPrice(): ?string
    {
        return $this->itemPrice;
    }

    public function setItemPrice(?string $itemPrice): self
    {
        $this->itemPrice = $itemPrice;

        return $this;
    }

    public function getItemAmount(): ?string
    {
        return $this->itemAmount;
    }

    public function setItemAmount(?string $itemAmount): self
    {
        $this->itemAmount = $itemAmount;

        return $this;
    }

    public function getShoppingInfo(): ?ShoppingInfo
    {
        return $this->shoppingInfo;
    }

    public function setShoppingInfo(?ShoppingInfo $shoppingInfo): self
    {
        $this->shoppingInfo = $shoppingInfo;

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
