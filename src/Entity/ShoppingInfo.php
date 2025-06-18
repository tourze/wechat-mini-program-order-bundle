<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\DoctrineUserBundle\Attribute\UpdatedByColumn;
use Tourze\EasyAdmin\Attribute\Column\BoolColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Field\FormField;
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
class ShoppingInfo
{
    use TimestampableAware;
    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[CreatedByColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '创建人'])]
    private ?string $createdBy = null;

    #[UpdatedByColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '更新人'])]
    private ?string $updatedBy = null;

    #[BoolColumn]
    #[IndexColumn]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[ListColumn(order: 97)]
    #[FormField(order: 97)]
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

    /**
     * 必填，物流形式
     * 1. PHYSICAL_LOGISTICS - 实体物流配送采用快递公司进行实体物流配送形式
     * 2. LOCAL_DELIVERY - 同城配送
     * 3. VIRTUAL_GOODS - 虚拟商品，例如话费充值，点卡等，无实体配送形式
     * 4. SELF_PICKUP - 用户自提
     */
    #[ORM\Column(type: 'integer', enumType: LogisticsType::class, options: ['comment' => '物流形式'])]
    private LogisticsType $logisticsType = LogisticsType::PHYSICAL_LOGISTICS;

    /**
     * 必填，订单详情页链接类型
     * 1. URL - H5链接
     * 2. MINI_PROGRAM - 小程序链接
     */
    #[ORM\Column(type: 'integer', enumType: OrderDetailType::class, options: ['comment' => '订单详情页链接类型'])]
    private OrderDetailType $orderDetailType = OrderDetailType::MINI_PROGRAM;

    /**
     * 必填，订单详情页链接
     * 示例值: pages/order/order?id=123456
     * 字符字节限制: [1, 1024]
     */
    #[ORM\Column(length: 1024, options: ['comment' => '订单详情页链接'])]
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
    }}
