<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramOrderBundle\Enum\ShoppingInfoVerifyStatus;
use WechatMiniProgramOrderBundle\Repository\ShoppingInfoVerifyUploadRepository;

#[ORM\Entity(repositoryClass: ShoppingInfoVerifyUploadRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shopping_info_verify_upload', options: ['comment' => '表描述'])]
class ShoppingInfoVerifyUpload implements Stringable
{
    use TimestampableAware;
    use BlameableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;


    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '订单ID'])]
    private ?string $orderId = null;

    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '商户订单ID'])]
    private ?string $outOrderId = null;

    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '路径ID'])]
    private ?string $pathId = null;

    #[ORM\Column(type: Types::STRING, enumType: ShoppingInfoVerifyStatus::class, options: ['comment' => '验证状态'])]
    private ShoppingInfoVerifyStatus $status = ShoppingInfoVerifyStatus::PENDING;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '验证失败原因'])]
    private ?string $failReason = null;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '验证结果数据'])]
    private ?array $resultData = null;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getStatus(): ShoppingInfoVerifyStatus
    {
        return $this->status;
    }

    public function setStatus(ShoppingInfoVerifyStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFailReason(): ?string
    {
        return $this->failReason;
    }

    public function setFailReason(?string $failReason): self
    {
        $this->failReason = $failReason;

        return $this;
    }

    public function getResultData(): ?array
    {
        return $this->resultData;
    }

    public function setResultData(?array $resultData): self
    {
        $this->resultData = $resultData;

        return $this;
    }
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
