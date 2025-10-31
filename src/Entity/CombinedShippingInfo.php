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
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramOrderBundle\Repository\CombinedShippingInfoRepository;

/**
 * 合单物流信息
 */
#[ORM\Entity(repositoryClass: CombinedShippingInfoRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_combined_shipping_info', options: ['comment' => '合单物流信息表'])]
class CombinedShippingInfo implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    /**
     * 必填，小程序账号
     */
    #[ORM\ManyToOne(targetEntity: MiniProgramInterface::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '小程序账号'])]
    private MiniProgramInterface $account;

    /**
     * 必填，合单订单信息
     */
    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '合单订单信息'])]
    private ?OrderKey $orderKey = null;

    /**
     * 子单物流详情列表
     * @var Collection<int, SubOrderList>
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
    #[Assert\NotNull]
    private \DateTimeImmutable $uploadTime;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效状态'])]
    #[TrackColumn]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    public function __construct()
    {
        $this->subOrders = new ArrayCollection();
        $this->uploadTime = new \DateTimeImmutable();
    }

    public function getAccount(): MiniProgramInterface
    {
        return $this->account;
    }

    public function setAccount(MiniProgramInterface $account): void
    {
        $this->account = $account;
    }

    public function getOrderKey(): ?OrderKey
    {
        return $this->orderKey;
    }

    public function setOrderKey(?OrderKey $orderKey): void
    {
        $this->orderKey = $orderKey;
    }

    /**
     * @return Collection<int, SubOrderList>
     */
    public function getSubOrders(): Collection
    {
        return $this->subOrders;
    }

    public function addSubOrder(SubOrderList $subOrder): void
    {
        if (!$this->subOrders->contains($subOrder)) {
            $this->subOrders->add($subOrder);
        }
    }

    public function removeSubOrder(SubOrderList $subOrder): void
    {
        $this->subOrders->removeElement($subOrder);
    }

    public function getPayer(): ?UserInterface
    {
        return $this->payer;
    }

    public function setPayer(?UserInterface $payer): void
    {
        $this->payer = $payer;
    }

    public function getUploadTime(): \DateTimeImmutable
    {
        return $this->uploadTime;
    }

    public function setUploadTime(\DateTimeImmutable $uploadTime): void
    {
        $this->uploadTime = $uploadTime;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
