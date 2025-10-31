<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Repository\OrderKeyRepository;

/**
 * 订单标识信息
 */
#[ORM\Entity(repositoryClass: OrderKeyRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_order_key', options: ['comment' => '订单标识信息表'])]
class OrderKey implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    #[ORM\Column(type: Types::INTEGER, enumType: OrderNumberType::class, options: ['comment' => '订单单号类型：1-使用商户单号，2-使用微信单号'])]
    #[Assert\Choice(callback: [OrderNumberType::class, 'cases'])]
    private OrderNumberType $orderNumberType = OrderNumberType::USE_MCH_ORDER;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '原支付交易对应的微信订单号'])]
    #[Assert\Length(max: 64)]
    private ?string $transactionId = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '支付下单商户的商户号'])]
    #[Assert\Length(max: 64)]
    private ?string $mchId = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '商户系统内部订单号'])]
    #[Assert\Length(max: 64)]
    private ?string $outTradeNo = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '订单ID'])]
    #[Assert\Length(max: 64)]
    private ?string $orderId = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '外部订单ID'])]
    #[Assert\Length(max: 64)]
    private ?string $outOrderId = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '用户OpenID'])]
    #[Assert\Length(max: 64)]
    private ?string $openid = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '路径ID'])]
    #[Assert\Length(max: 64)]
    private ?string $pathId = null;

    public function getOrderNumberType(): OrderNumberType
    {
        return $this->orderNumberType;
    }

    public function setOrderNumberType(OrderNumberType $orderNumberType): void
    {
        $this->orderNumberType = $orderNumberType;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function getMchId(): ?string
    {
        return $this->mchId;
    }

    public function setMchId(?string $mchId): void
    {
        $this->mchId = $mchId;
    }

    public function getOutTradeNo(): ?string
    {
        return $this->outTradeNo;
    }

    public function setOutTradeNo(?string $outTradeNo): void
    {
        $this->outTradeNo = $outTradeNo;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(?string $orderId): void
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

    public function getOpenid(): ?string
    {
        return $this->openid;
    }

    public function setOpenid(?string $openid): void
    {
        $this->openid = $openid;
    }

    public function getPathId(): ?string
    {
        return $this->pathId;
    }

    public function setPathId(?string $pathId): void
    {
        $this->pathId = $pathId;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
