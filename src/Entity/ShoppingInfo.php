<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;
use WechatMiniProgramOrderBundle\Repository\ShoppingInfoRepository;

/**
 * 购物信息
 */
#[ORM\Entity(repositoryClass: ShoppingInfoRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shopping_info', options: ['comment' => '购物信息表'])]
class ShoppingInfo implements Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;


    #[TrackColumn]
    private ?bool $valid = false;

    /**
     * 必填，小程序账号
     */
    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '小程序账号'])]
    private Account $account;

    /**
     * 必填，订单信息
     */
    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '订单信息'])]
    private OrderKey $orderKey;

    /**
     * 必填，支付者信息
     */
    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '支付者信息'])]
    private UserInterface $payer;

    #[ORM\Column(type: Types::INTEGER, enumType: LogisticsType::class, options: ['comment' => '物流形式'])]
    private LogisticsType $logisticsType = LogisticsType::PHYSICAL_LOGISTICS;

    #[ORM\Column(type: Types::INTEGER, enumType: OrderDetailType::class, options: ['comment' => '订单详情页链接类型'])]
    private OrderDetailType $orderDetailType = OrderDetailType::MINI_PROGRAM;

    #[ORM\Column(type: Types::STRING, length: 1024, options: ['comment' => '订单详情页链接'])]
    private ?string $orderDetailPath = null;

    /**
     * 必填，商品列表
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

    public function setValid(?bool $valid): self
    {
        $this->valid = $valid;

        return $this;
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

    public function getOrderKey(): OrderKey
    {
        return $this->orderKey;
    }

    public function setOrderKey(OrderKey $orderKey): self
    {
        $this->orderKey = $orderKey;

        return $this;
    }

    public function getPayer(): UserInterface
    {
        return $this->payer;
    }

    public function setPayer(UserInterface $payer): self
    {
        $this->payer = $payer;

        return $this;
    }

    public function getLogisticsType(): LogisticsType
    {
        return $this->logisticsType;
    }

    public function setLogisticsType(LogisticsType $logisticsType): self
    {
        $this->logisticsType = $logisticsType;

        return $this;
    }

    public function getOrderDetailType(): OrderDetailType
    {
        return $this->orderDetailType;
    }

    public function setOrderDetailType(OrderDetailType $orderDetailType): self
    {
        $this->orderDetailType = $orderDetailType;

        return $this;
    }

    public function getOrderDetailPath(): ?string
    {
        return $this->orderDetailPath;
    }

    public function setOrderDetailPath(?string $orderDetailPath): self
    {
        $this->orderDetailPath = $orderDetailPath;

        return $this;
    }

    /**
     * @return Collection<int, ShoppingItemList>
     */
    public function getItemList(): Collection
    {
        return $this->itemList;
    }

    public function addItemList(ShoppingItemList $itemList): self
    {
        if (!$this->itemList->contains($itemList)) {
            $this->itemList->add($itemList);
            $itemList->setShoppingInfo($this);
        }

        return $this;
    }

    public function removeItemList(ShoppingItemList $itemList): self
    {
        if ($this->itemList->removeElement($itemList)) {
            // set the owning side to null (unless already changed)
            if ($itemList->getShoppingInfo() === $this) {
                $itemList->setShoppingInfo(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
