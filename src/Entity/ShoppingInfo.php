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
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;
use WechatMiniProgramOrderBundle\Repository\ShoppingInfoRepository;

/**
 * 购物信息
 */
#[ORM\Entity(repositoryClass: ShoppingInfoRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shopping_info', options: ['comment' => '购物信息表'])]
class ShoppingInfo implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效状态'])]
    #[TrackColumn]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    /**
     * 必填，小程序账号
     */
    #[ORM\ManyToOne(targetEntity: MiniProgramInterface::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '小程序账号'])]
    private MiniProgramInterface $account;

    /**
     * 必填，订单信息
     */
    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '订单信息'])]
    private OrderKey $orderKey;

    /**
     * 必填，支付者信息
     */
    #[ORM\ManyToOne(targetEntity: UserInterface::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '支付者信息'])]
    private UserInterface $payer;

    #[ORM\Column(type: Types::INTEGER, enumType: LogisticsType::class, options: ['comment' => '物流形式'])]
    #[Assert\Choice(callback: [LogisticsType::class, 'cases'])]
    private LogisticsType $logisticsType = LogisticsType::PHYSICAL_LOGISTICS;

    #[ORM\Column(type: Types::INTEGER, enumType: OrderDetailType::class, options: ['comment' => '订单详情页链接类型'])]
    #[Assert\Choice(callback: [OrderDetailType::class, 'cases'])]
    private OrderDetailType $orderDetailType = OrderDetailType::MINI_PROGRAM;

    #[ORM\Column(type: Types::STRING, length: 1024, options: ['comment' => '订单详情页链接'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 1024)]
    private ?string $orderDetailPath = null;

    /**
     * 必填，商品列表
     * @var Collection<int, ShoppingItemList>
     */
    #[ORM\OneToMany(mappedBy: 'shoppingInfo', targetEntity: ShoppingItemList::class, cascade: ['persist', 'remove'])]
    private Collection $itemList;

    public function __construct()
    {
        $this->itemList = new ArrayCollection();
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    public function getAccount(): MiniProgramInterface
    {
        return $this->account;
    }

    public function setAccount(MiniProgramInterface $account): void
    {
        $this->account = $account;
    }

    public function getOrderKey(): OrderKey
    {
        return $this->orderKey;
    }

    public function setOrderKey(OrderKey $orderKey): void
    {
        $this->orderKey = $orderKey;
    }

    public function getPayer(): UserInterface
    {
        return $this->payer;
    }

    public function setPayer(UserInterface $payer): void
    {
        $this->payer = $payer;
    }

    public function getLogisticsType(): LogisticsType
    {
        return $this->logisticsType;
    }

    public function setLogisticsType(LogisticsType $logisticsType): void
    {
        $this->logisticsType = $logisticsType;
    }

    public function getOrderDetailType(): OrderDetailType
    {
        return $this->orderDetailType;
    }

    public function setOrderDetailType(OrderDetailType $orderDetailType): void
    {
        $this->orderDetailType = $orderDetailType;
    }

    public function getOrderDetailPath(): ?string
    {
        return $this->orderDetailPath;
    }

    public function setOrderDetailPath(?string $orderDetailPath): void
    {
        $this->orderDetailPath = $orderDetailPath;
    }

    /**
     * @return Collection<int, ShoppingItemList>
     */
    public function getItemList(): Collection
    {
        return $this->itemList;
    }

    public function addItemList(ShoppingItemList $itemList): void
    {
        if (!$this->itemList->contains($itemList)) {
            $this->itemList->add($itemList);
        }
    }

    public function removeItemList(ShoppingItemList $itemList): void
    {
        $this->itemList->removeElement($itemList);
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
