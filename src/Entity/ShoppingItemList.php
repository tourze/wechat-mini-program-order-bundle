<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramOrderBundle\Repository\ShoppingItemListRepository;

/**
 * 购物商品列表项
 */
#[ORM\Entity(repositoryClass: ShoppingItemListRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shopping_item_list', options: ['comment' => '购物商品列表项表'])]
class ShoppingItemList implements Stringable
{
    use TimestampableAware;
    use BlameableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[ORM\Column(type: Types::STRING, length: 128, options: ['comment' => '商品ID'])]
    private ?string $merchantItemId = null;

    #[ORM\Column(type: Types::STRING, length: 128, options: ['comment' => '商品名称'])]
    private ?string $itemName = null;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '商品数量'])]
    private ?int $itemCount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['comment' => '商品单价，单位：元'])]
    private ?string $itemPrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['comment' => '商品总价，单位：元'])]
    private ?string $itemAmount = null;

    /**
     * 所属购物信息
     */
    #[ORM\ManyToOne(inversedBy: 'itemList')]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属购物信息'])]
    private ?ShoppingInfo $shoppingInfo = null;


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMerchantItemId(): ?string
    {
        return $this->merchantItemId;
    }

    public function setMerchantItemId(?string $merchantItemId): self
    {
        $this->merchantItemId = $merchantItemId;

        return $this;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(?string $itemName): self
    {
        $this->itemName = $itemName;

        return $this;
    }

    public function getItemCount(): ?int
    {
        return $this->itemCount;
    }

    public function setItemCount(?int $itemCount): self
    {
        $this->itemCount = $itemCount;

        return $this;
    }

    public function getItemPrice(): ?string
    {
        return $this->itemPrice;
    }

    public function setItemPrice(?string $itemPrice): self
    {
        $this->itemPrice = $itemPrice;

        return $this;
    }

    public function getItemAmount(): ?string
    {
        return $this->itemAmount;
    }

    public function setItemAmount(?string $itemAmount): self
    {
        $this->itemAmount = $itemAmount;

        return $this;
    }

    public function getShoppingInfo(): ?ShoppingInfo
    {
        return $this->shoppingInfo;
    }

    public function setShoppingInfo(?ShoppingInfo $shoppingInfo): self
    {
        $this->shoppingInfo = $shoppingInfo;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
