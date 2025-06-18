<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\DoctrineUserBundle\Attribute\UpdatedByColumn;
use WechatMiniProgramOrderBundle\Repository\ShippingItemListRepository;

/**
 * 物流商品列表项
 */
#[ORM\Entity(repositoryClass: ShippingItemListRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shipping_item_list', options: ['comment' => '物流商品列表项'])]
class ShippingItemList implements Stringable
{
    use TimestampableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[CreatedByColumn]
    private ?string $createdBy = null;

    #[UpdatedByColumn]
    private ?string $updatedBy = null;

    /**
     * 所属物流信息
     */
    #[ORM\ManyToOne(inversedBy: 'itemList')]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属物流信息'])]
    private ?ShippingList $shippingList = null;

    /**
     * 必填，商户侧商品ID，商户系统内部商品编码
     * 分拆发货模式下为必填，用于标识每笔物流单号内包含的商品
     * 需与「上传购物详情」中传入的商品ID匹配
     */
    #[ORM\Column(length: 64, options: ['comment' => '商户侧商品ID，商户系统内部商品编码'])]
    private ?string $merchantItemId = null;

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

    public function getShippingList(): ?ShippingList
    {
        return $this->shippingList;
    }

    public function setShippingList(?ShippingList $shippingList): self
    {
        $this->shippingList = $shippingList;

        return $this;
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
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
