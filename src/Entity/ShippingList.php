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
use WechatMiniProgramOrderBundle\Repository\ShippingListRepository;

/**
 * 物流信息
 */
#[ORM\Entity(repositoryClass: ShippingListRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shipping_list', options: ['comment' => '物流信息表'])]
class ShippingList implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    /**
     * 所属子订单
     */
    #[ORM\ManyToOne(inversedBy: 'shippingList', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属子订单'])]
    private ?SubOrderList $subOrder = null;

    #[ORM\Column(type: Types::STRING, length: 128, options: ['comment' => '物流单号，物流快递发货时必填'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    private ?string $trackingNo = null;

    #[ORM\Column(type: Types::STRING, length: 128, options: ['comment' => '物流公司编码，快递公司ID'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    private ?string $expressCompany = null;

    /**
     * 物流关联的商品列表
     * 当统一发货（单个物流单）时，该项不填
     * 当分拆发货（多个物流单）时，需填入各物流单关联的商品列表
     * 多重性: [0, 50]
     * @var Collection<int, ShippingItemList>
     */
    #[ORM\OneToMany(targetEntity: ShippingItemList::class, mappedBy: 'shippingList', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $itemList;

    /**
     * 联系方式，当发货的物流公司为顺丰时，联系方式为必填
     * 收件人或寄件人联系方式二选一
     */
    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true, options: ['comment' => '联系方式，顺丰快递必填'])]
    private ?Contact $contact = null;

    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '物流轨迹信息，包含物流状态和时间等'])]
    #[Assert\Type(type: 'array')]
    private ?array $trackingInfo = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '最后更新物流信息的时间'])]
    #[Assert\Type(type: '\DateTimeImmutable')]
    private ?\DateTimeImmutable $lastTrackingTime = null;

    public function __construct()
    {
        $this->itemList = new ArrayCollection();
    }

    public function getSubOrder(): ?SubOrderList
    {
        return $this->subOrder;
    }

    public function setSubOrder(?SubOrderList $subOrder): void
    {
        $this->subOrder = $subOrder;
    }

    public function getTrackingNo(): ?string
    {
        return $this->trackingNo;
    }

    public function setTrackingNo(?string $trackingNo): void
    {
        $this->trackingNo = $trackingNo;
    }

    public function getExpressCompany(): ?string
    {
        return $this->expressCompany;
    }

    public function setExpressCompany(?string $expressCompany): void
    {
        $this->expressCompany = $expressCompany;
    }

    /**
     * @return Collection<int, ShippingItemList>
     */
    public function getItemList(): Collection
    {
        return $this->itemList;
    }

    public function addItemList(ShippingItemList $itemList): void
    {
        if (!$this->itemList->contains($itemList)) {
            $this->itemList->add($itemList);
        }
    }

    public function removeItemList(ShippingItemList $itemList): void
    {
        $this->itemList->removeElement($itemList);
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): void
    {
        $this->contact = $contact;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getTrackingInfo(): ?array
    {
        return $this->trackingInfo;
    }

    /**
     * @param array<string, mixed>|null $trackingInfo
     */
    public function setTrackingInfo(?array $trackingInfo): void
    {
        $this->trackingInfo = $trackingInfo;
    }

    public function getLastTrackingTime(): ?\DateTimeImmutable
    {
        return $this->lastTrackingTime;
    }

    public function setLastTrackingTime(?\DateTimeImmutable $lastTrackingTime): void
    {
        $this->lastTrackingTime = $lastTrackingTime;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
