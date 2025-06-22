<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Repository\OrderKeyRepository;

/**
 * 订单标识信息
 */
#[ORM\Entity(repositoryClass: OrderKeyRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_order_key', options: ['comment' => '订单标识信息表'])]
class OrderKey implements Stringable
{
    use TimestampableAware;
    use BlameableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;


    #[ORM\Column(type: Types::INTEGER, enumType: OrderNumberType::class, options: ['comment' => '订单单号类型：1-使用商户单号，2-使用微信单号'])]
    private OrderNumberType $orderNumberType = OrderNumberType::USE_MCH_ORDER;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '原支付交易对应的微信订单号'])]
    private ?string $transactionId = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '支付下单商户的商户号'])]
    private ?string $mchId = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '商户系统内部订单号'])]
    private ?string $outTradeNo = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getOrderNumberType(): OrderNumberType
    {
        return $this->orderNumberType;
    }

    public function setOrderNumberType(OrderNumberType $orderNumberType): self
    {
        $this->orderNumberType = $orderNumberType;

        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getMchId(): ?string
    {
        return $this->mchId;
    }

    public function setMchId(?string $mchId): self
    {
        $this->mchId = $mchId;

        return $this;
    }

    public function getOutTradeNo(): ?string
    {
        return $this->outTradeNo;
    }

    public function setOutTradeNo(?string $outTradeNo): self
    {
        $this->outTradeNo = $outTradeNo;

        return $this;
    }
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
