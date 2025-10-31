<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramOrderBundle\Repository\CombinedShoppingInfoRepository;

/**
 * 合单购物信息
 */
#[ORM\Entity(repositoryClass: CombinedShoppingInfoRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_combined_shopping_info', options: ['comment' => '合单购物信息表'])]
class CombinedShoppingInfo implements \Stringable
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

    #[ORM\Column(length: 64, nullable: false, options: ['comment' => '订单ID'])]
    #[Assert\Length(max: 64)]
    #[Assert\NotBlank]
    private string $orderId;

    #[ORM\Column(length: 64, options: ['comment' => '字段说明'])]
    #[Assert\Length(max: 64)]
    #[Assert\NotBlank]
    private ?string $outOrderId = null;

    #[ORM\Column(length: 64, options: ['comment' => '字段说明'])]
    #[Assert\Length(max: 64)]
    #[Assert\NotBlank]
    private ?string $pathId = null;

    #[ORM\Column(length: 32, options: ['comment' => '字段说明'])]
    #[Assert\Length(max: 32)]
    #[Assert\NotBlank]
    private ?string $status = null;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '字段说明'])]
    #[Assert\PositiveOrZero]
    #[Assert\NotNull]
    private ?int $totalAmount = null;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '字段说明'])]
    #[Assert\PositiveOrZero]
    #[Assert\NotNull]
    private ?int $payAmount = null;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '字段说明'])]
    #[Assert\Range(min: 0, max: 100)]
    private ?int $discountAmount = null;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '字段说明'])]
    #[Assert\PositiveOrZero]
    private ?int $freightAmount = null;

    #[ORM\OneToOne(targetEntity: UserInterface::class, cascade: ['persist', 'remove'])]
    private ?UserInterface $payer = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Contact $contact = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ShippingInfo $shippingInfo = null;

    public function getAccount(): MiniProgramInterface
    {
        return $this->account;
    }

    public function setAccount(MiniProgramInterface $account): void
    {
        $this->account = $account;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getOutOrderId(): ?string
    {
        return $this->outOrderId;
    }

    public function setOutOrderId(?string $outOrderId): void
    {
        $this->outOrderId = $outOrderId;
    }

    public function getPathId(): ?string
    {
        return $this->pathId;
    }

    public function setPathId(?string $pathId): void
    {
        $this->pathId = $pathId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(?int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getPayAmount(): ?int
    {
        return $this->payAmount;
    }

    public function setPayAmount(?int $payAmount): void
    {
        $this->payAmount = $payAmount;
    }

    public function getDiscountAmount(): ?int
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(?int $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getFreightAmount(): ?int
    {
        return $this->freightAmount;
    }

    public function setFreightAmount(?int $freightAmount): void
    {
        $this->freightAmount = $freightAmount;
    }

    public function getPayer(): ?UserInterface
    {
        return $this->payer;
    }

    public function setPayer(?UserInterface $payer): void
    {
        $this->payer = $payer;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): void
    {
        $this->contact = $contact;
    }

    public function getShippingInfo(): ?ShippingInfo
    {
        return $this->shippingInfo;
    }

    public function setShippingInfo(?ShippingInfo $shippingInfo): void
    {
        $this->shippingInfo = $shippingInfo;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
