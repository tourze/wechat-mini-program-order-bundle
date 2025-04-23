<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
use WechatMiniProgramOrderBundle\Repository\ShippingListRepository;

/**
 * 物流信息
 */
#[ORM\Entity(repositoryClass: ShippingListRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shipping_list', options: ['comment' => '物流信息表'])]
class ShippingList
{
    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[Groups(['restful_read', 'admin_curd', 'recursive_view', 'api_tree'])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = '0';

    #[CreatedByColumn]
    #[Groups(['restful_read'])]
    #[ORM\Column(nullable: true, options: ['comment' => '创建人'])]
    private ?string $createdBy = null;

    #[UpdatedByColumn]
    #[Groups(['restful_read'])]
    #[ORM\Column(nullable: true, options: ['comment' => '更新人'])]
    private ?string $updatedBy = null;

    /**
     * 所属子订单
     */
    #[ORM\ManyToOne(inversedBy: 'shippingList')]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属子订单'])]
    private ?SubOrderList $subOrder = null;

    /**
     * 必填，物流单号，物流快递发货时必填
     * 字符字节限制: [1, 128]
     */
    #[ORM\Column(length: 128, options: ['comment' => '物流单号，物流快递发货时必填'])]
    private ?string $trackingNo = null;

    /**
     * 必填，物流公司编码，快递公司ID
     * 参见「查询物流公司编码列表」，物流快递发货时必填
     * 字符字节限制: [1, 128]
     */
    #[ORM\Column(length: 128, options: ['comment' => '物流公司编码，快递公司ID'])]
    private ?string $expressCompany = null;

    /**
     * 物流关联的商品列表
     * 当统一发货（单个物流单）时，该项不填
     * 当分拆发货（多个物流单）时，需填入各物流单关联的商品列表
     * 多重性: [0, 50]
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
     * 物流轨迹信息
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '物流轨迹信息，包含物流状态和时间等'])]
    private ?array $trackingInfo = null;

    /**
     * 最后更新物流信息的时间
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: true, options: ['comment' => '最后更新物流信息的时间'])]
    private ?\DateTimeImmutable $lastTrackingTime = null;

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

    public function __construct()
    {
        $this->itemList = new ArrayCollection();
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

    public function getSubOrder(): ?SubOrderList
    {
        return $this->subOrder;
    }

    public function setSubOrder(?SubOrderList $subOrder): self
    {
        $this->subOrder = $subOrder;

        return $this;
    }

    public function getTrackingNo(): ?string
    {
        return $this->trackingNo;
    }

    public function setTrackingNo(?string $trackingNo): self
    {
        $this->trackingNo = $trackingNo;

        return $this;
    }

    public function getExpressCompany(): ?string
    {
        return $this->expressCompany;
    }

    public function setExpressCompany(?string $expressCompany): self
    {
        $this->expressCompany = $expressCompany;

        return $this;
    }

    /**
     * @return Collection<int, ShippingItemList>
     */
    public function getItemList(): Collection
    {
        return $this->itemList;
    }

    public function addItemList(ShippingItemList $itemList): self
    {
        if (!$this->itemList->contains($itemList)) {
            $this->itemList->add($itemList);
            $itemList->setShippingList($this);
        }

        return $this;
    }

    public function removeItemList(ShippingItemList $itemList): self
    {
        if ($this->itemList->removeElement($itemList)) {
            if ($itemList->getShippingList() === $this) {
                $itemList->setShippingList(null);
            }
        }

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

    public function getTrackingInfo(): ?array
    {
        return $this->trackingInfo;
    }

    public function setTrackingInfo(?array $trackingInfo): self
    {
        $this->trackingInfo = $trackingInfo;

        return $this;
    }

    public function getLastTrackingTime(): ?\DateTimeImmutable
    {
        return $this->lastTrackingTime;
    }

    public function setLastTrackingTime(?\DateTimeImmutable $lastTrackingTime): self
    {
        $this->lastTrackingTime = $lastTrackingTime;

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
