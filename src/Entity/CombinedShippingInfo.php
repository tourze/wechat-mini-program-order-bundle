<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Repository\CombinedShippingInfoRepository;

/**
 * 合单物流信息
 */
#[ORM\Entity(repositoryClass: CombinedShippingInfoRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_combined_shipping_info', options: ['comment' => '合单物流信息表'])]
class CombinedShippingInfo implements Stringable
{
    use TimestampableAware;
    use BlameableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    /**
     * 必填，小程序账号
     */
    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '小程序账号'])]
    private Account $account;

    /**
     * 必填，合单订单信息
     */
    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '合单订单信息'])]
    private ?OrderKey $orderKey = null;

    /**
     * 子单物流详情列表
     */
    #[ORM\OneToMany(targetEntity: SubOrderList::class, mappedBy: 'combinedShippingInfo', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $subOrders;

    /**
     * 必填，支付者信息
     */
    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '支付者信息'])]
    private ?UserInterface $payer = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['comment' => '上传时间，用于标识请求的先后顺序'])]
    private \DateTimeImmutable $uploadTime;

    #[TrackColumn]
    private ?bool $valid = false;

    public function __construct()
    {
        $this->subOrders = new ArrayCollection();
        $this->uploadTime = new \DateTimeImmutable();
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getOrderKey(): ?OrderKey
    {
        return $this->orderKey;
    }

    public function setOrderKey(?OrderKey $orderKey): self
    {
        $this->orderKey = $orderKey;

        return $this;
    }

    /**
     * @return Collection<int, SubOrderList>
     */
    public function getSubOrders(): Collection
    {
        return $this->subOrders;
    }

    public function addSubOrder(SubOrderList $subOrder): self
    {
        if (!$this->subOrders->contains($subOrder)) {
            $this->subOrders->add($subOrder);
            $subOrder->setCombinedShippingInfo($this);
        }

        return $this;
    }

    public function removeSubOrder(SubOrderList $subOrder): self
    {
        if ($this->subOrders->removeElement($subOrder)) {
            if ($subOrder->getCombinedShippingInfo() === $this) {
                $subOrder->setCombinedShippingInfo(null);
            }
        }

        return $this;
    }

    public function getPayer(): ?UserInterface
    {
        return $this->payer;
    }

    public function setPayer(?UserInterface $payer): self
    {
        $this->payer = $payer;

        return $this;
    }

    public function getUploadTime(): \DateTimeImmutable
    {
        return $this->uploadTime;
    }

    public function setUploadTime(\DateTimeImmutable $uploadTime): self
    {
        $this->uploadTime = $uploadTime;

        return $this;
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

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
