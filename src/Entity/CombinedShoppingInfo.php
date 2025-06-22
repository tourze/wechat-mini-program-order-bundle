<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Repository\CombinedShoppingInfoRepository;

/**
 * 合单购物信息
 */
#[ORM\Entity(repositoryClass: CombinedShoppingInfoRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_combined_shopping_info', options: ['comment' => '合单购物信息表'])]
class CombinedShoppingInfo implements Stringable
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

#[ORM\Column(length: 64, options: ['comment' => '字段说明'])]
    private ?string $orderId = null;

#[ORM\Column(length: 64, options: ['comment' => '字段说明'])]
    private ?string $outOrderId = null;

#[ORM\Column(length: 64, options: ['comment' => '字段说明'])]
    private ?string $pathId = null;

#[ORM\Column(length: 32, options: ['comment' => '字段说明'])]
    private ?string $status = null;

#[ORM\Column(type: Types::INTEGER, options: ['comment' => '字段说明'])]
    private ?int $totalAmount = null;

#[ORM\Column(type: Types::INTEGER, options: ['comment' => '字段说明'])]
    private ?int $payAmount = null;

#[ORM\Column(type: Types::INTEGER, options: ['comment' => '字段说明'])]
    private ?int $discountAmount = null;

#[ORM\Column(type: Types::INTEGER, options: ['comment' => '字段说明'])]
    private ?int $freightAmount = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?UserInterface $payer = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Contact $contact = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ShippingInfo $shippingInfo = null;

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

    public function getPayer(): ?UserInterface
    {
        return $this->payer;
    }

    public function setPayer(?UserInterface $payer): self
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
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
