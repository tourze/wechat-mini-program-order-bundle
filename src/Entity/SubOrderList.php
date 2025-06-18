<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\DoctrineUserBundle\Attribute\UpdatedByColumn;
use WechatMiniProgramOrderBundle\Enum\DeliveryMode;
use WechatMiniProgramOrderBundle\Repository\SubOrderListRepository;

/**
 * 子单物流详情
 */
#[ORM\Entity(repositoryClass: SubOrderListRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_sub_order_list', options: ['comment' => '子单物流详情表'])]
class SubOrderList implements Stringable
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
     * 所属合单物流信息
     */
    #[ORM\ManyToOne(inversedBy: 'subOrders')]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属合单物流信息'])]
    private ?CombinedShippingInfo $combinedShippingInfo = null;

    /**
     * 必填，订单信息
     */
    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '订单信息'])]
    private ?OrderKey $orderKey = null;

    /**
     * 必填，发货模式，枚举值：
     * 1. UNIFIED_DELIVERY（统一发货）
     * 2. SPLIT_DELIVERY（分拆发货）
     */
    #[ORM\Column(type: 'string', enumType: DeliveryMode::class, options: ['comment' => '发货模式：统一发货/分拆发货'])]
    private DeliveryMode $deliveryMode = DeliveryMode::UNIFIED_DELIVERY;

    /**
     * 必填，物流信息列表，支持统一发货（单个物流单）和分拆发货（多个物流单）两种模式
     */
    #[ORM\OneToMany(mappedBy: 'subOrder', targetEntity: ShippingList::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '物流信息列表'])]
    private Collection $shippingList;

    public function __construct()
    {
        $this->shippingList = new ArrayCollection();
    }

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

    public function getCombinedShippingInfo(): ?CombinedShippingInfo
    {
        return $this->combinedShippingInfo;
    }

    public function setCombinedShippingInfo(?CombinedShippingInfo $combinedShippingInfo): self
    {
        $this->combinedShippingInfo = $combinedShippingInfo;

        return $this;
    }

    public function getOrderKey(): ?OrderKey
    {
        return $this->orderKey;
    }

    public function setOrderKey(?OrderKey $orderKey): self
    {
        $this->orderKey = $orderKey;

        return $this;
    }

    public function getDeliveryMode(): DeliveryMode
    {
        return $this->deliveryMode;
    }

    public function setDeliveryMode(DeliveryMode $deliveryMode): self
    {
        $this->deliveryMode = $deliveryMode;

        return $this;
    }

    /**
     * @return Collection<int, ShippingList>
     */
    public function getShippingList(): Collection
    {
        return $this->shippingList;
    }

    public function addShippingList(ShippingList $shippingList): self
    {
        if (!$this->shippingList->contains($shippingList)) {
            $this->shippingList->add($shippingList);
            $shippingList->setSubOrder($this);
        }

        return $this;
    }

    public function removeShippingList(ShippingList $shippingList): self
    {
        if ($this->shippingList->removeElement($shippingList)) {
            if ($shippingList->getSubOrder() === $this) {
                $shippingList->setSubOrder(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
