<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramOrderBundle\Repository\ShoppingItemListRepository;

/**
 * 购物商品列表项
 */
#[ORM\Entity(repositoryClass: ShoppingItemListRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shopping_item_list', options: ['comment' => '购物商品列表项表'])]
class ShoppingItemList implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    #[ORM\Column(type: Types::STRING, length: 128, options: ['comment' => '商品ID'])]
    #[Assert\Length(max: 128)]
    #[Assert\NotBlank]
    private ?string $merchantItemId = null;

    #[ORM\Column(type: Types::STRING, length: 128, options: ['comment' => '商品名称'])]
    #[Assert\Length(max: 128)]
    #[Assert\NotBlank]
    private ?string $itemName = null;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '商品数量'])]
    #[Assert\PositiveOrZero]
    #[Assert\NotNull]
    private ?int $itemCount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['comment' => '商品单价，单位：元'])]
    #[Assert\Regex(pattern: '/^\d+(\.\d{1,2})?$/')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 13)]
    private ?string $itemPrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['comment' => '商品总价，单位：元'])]
    #[Assert\Regex(pattern: '/^\d+(\.\d{1,2})?$/')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 13)]
    private ?string $itemAmount = null;

    /**
     * 所属购物信息
     */
    #[ORM\ManyToOne(inversedBy: 'itemList', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属购物信息'])]
    private ?ShoppingInfo $shoppingInfo = null;

    public function getMerchantItemId(): ?string
    {
        return $this->merchantItemId;
    }

    public function setMerchantItemId(?string $merchantItemId): void
    {
        $this->merchantItemId = $merchantItemId;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(?string $itemName): void
    {
        $this->itemName = $itemName;
    }

    public function getItemCount(): ?int
    {
        return $this->itemCount;
    }

    public function setItemCount(?int $itemCount): void
    {
        $this->itemCount = $itemCount;
    }

    public function getItemPrice(): ?string
    {
        return $this->itemPrice;
    }

    public function setItemPrice(?string $itemPrice): void
    {
        $this->itemPrice = $itemPrice;
    }

    public function getItemAmount(): ?string
    {
        return $this->itemAmount;
    }

    public function setItemAmount(?string $itemAmount): void
    {
        $this->itemAmount = $itemAmount;
    }

    public function getShoppingInfo(): ?ShoppingInfo
    {
        return $this->shoppingInfo;
    }

    public function setShoppingInfo(?ShoppingInfo $shoppingInfo): void
    {
        $this->shoppingInfo = $shoppingInfo;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
