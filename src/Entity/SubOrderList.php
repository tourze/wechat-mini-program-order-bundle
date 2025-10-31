<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramOrderBundle\Enum\DeliveryMode;
use WechatMiniProgramOrderBundle\Repository\SubOrderListRepository;

/**
 * 子单物流详情
 */
#[ORM\Entity(repositoryClass: SubOrderListRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_sub_order_list', options: ['comment' => '子单物流详情表'])]
class SubOrderList implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    /**
     * 所属合单物流信息
     */
    #[ORM\ManyToOne(inversedBy: 'subOrders', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属合单物流信息'])]
    private ?CombinedShippingInfo $combinedShippingInfo = null;

    /**
     * 必填，订单信息
     */
    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '订单信息'])]
    private ?OrderKey $orderKey = null;

    #[ORM\Column(type: Types::STRING, enumType: DeliveryMode::class, options: ['comment' => '发货模式：统一发货/分拆发货'])]
    #[Assert\Choice(callback: [DeliveryMode::class, 'cases'])]
    private DeliveryMode $deliveryMode = DeliveryMode::UNIFIED_DELIVERY;

    /**
     * 必填，物流信息列表，支持统一发货（单个物流单）和分拆发货（多个物流单）两种模式
     * @var Collection<int, ShippingList>
     */
    #[ORM\OneToMany(mappedBy: 'subOrder', targetEntity: ShippingList::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '物流信息列表'])]
    private Collection $shippingList;

    public function __construct()
    {
        $this->shippingList = new ArrayCollection();
    }

    public function getCombinedShippingInfo(): ?CombinedShippingInfo
    {
        return $this->combinedShippingInfo;
    }

    public function setCombinedShippingInfo(?CombinedShippingInfo $combinedShippingInfo): void
    {
        $this->combinedShippingInfo = $combinedShippingInfo;
    }

    public function getOrderKey(): ?OrderKey
    {
        return $this->orderKey;
    }

    public function setOrderKey(?OrderKey $orderKey): void
    {
        $this->orderKey = $orderKey;
    }

    public function getDeliveryMode(): DeliveryMode
    {
        return $this->deliveryMode;
    }

    public function setDeliveryMode(DeliveryMode $deliveryMode): void
    {
        $this->deliveryMode = $deliveryMode;
    }

    /**
     * @return Collection<int, ShippingList>
     */
    public function getShippingList(): Collection
    {
        return $this->shippingList;
    }

    public function addShippingList(ShippingList $shippingList): void
    {
        if (!$this->shippingList->contains($shippingList)) {
            $this->shippingList->add($shippingList);
        }
    }

    public function removeShippingList(ShippingList $shippingList): void
    {
        $this->shippingList->removeElement($shippingList);
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
