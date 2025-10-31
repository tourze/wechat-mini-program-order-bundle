<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramOrderBundle\Repository\ShippingItemListRepository;

/**
 * 物流商品列表项
 */
#[ORM\Entity(repositoryClass: ShippingItemListRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shipping_item_list', options: ['comment' => '物流商品列表项'])]
class ShippingItemList implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    /**
     * 所属物流信息
     */
    #[ORM\ManyToOne(inversedBy: 'itemList', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属物流信息'])]
    private ?ShippingList $shippingList = null;

    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '商户侧商品ID，商户系统内部商品编码'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    private ?string $merchantItemId = null;

    public function getShippingList(): ?ShippingList
    {
        return $this->shippingList;
    }

    public function setShippingList(?ShippingList $shippingList): void
    {
        $this->shippingList = $shippingList;
    }

    public function getMerchantItemId(): ?string
    {
        return $this->merchantItemId;
    }

    public function setMerchantItemId(?string $merchantItemId): void
    {
        $this->merchantItemId = $merchantItemId;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
