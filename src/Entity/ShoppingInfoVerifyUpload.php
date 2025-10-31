<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramOrderBundle\Enum\ShoppingInfoVerifyStatus;
use WechatMiniProgramOrderBundle\Repository\ShoppingInfoVerifyUploadRepository;

#[ORM\Entity(repositoryClass: ShoppingInfoVerifyUploadRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_shopping_info_verify_upload', options: ['comment' => '表描述'])]
class ShoppingInfoVerifyUpload implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '订单ID'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    private ?string $orderId = null;

    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '商户订单ID'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    private ?string $outOrderId = null;

    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '路径ID'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    private ?string $pathId = null;

    #[ORM\Column(type: Types::STRING, enumType: ShoppingInfoVerifyStatus::class, options: ['comment' => '验证状态'])]
    #[Assert\Choice(callback: [ShoppingInfoVerifyStatus::class, 'cases'])]
    private ShoppingInfoVerifyStatus $status = ShoppingInfoVerifyStatus::PENDING;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '验证失败原因'])]
    #[Assert\Length(max: 65535)]
    private ?string $failReason = null;

    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '验证结果数据'])]
    #[Assert\Type(type: 'array')]
    private ?array $resultData = null;

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

    public function getPathId(): ?string
    {
        return $this->pathId;
    }

    public function setPathId(?string $pathId): void
    {
        $this->pathId = $pathId;
    }

    public function getStatus(): ShoppingInfoVerifyStatus
    {
        return $this->status;
    }

    public function setStatus(ShoppingInfoVerifyStatus $status): void
    {
        $this->status = $status;
    }

    public function getFailReason(): ?string
    {
        return $this->failReason;
    }

    public function setFailReason(?string $failReason): void
    {
        $this->failReason = $failReason;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getResultData(): ?array
    {
        return $this->resultData;
    }

    /**
     * @param array<string, mixed>|null $resultData
     */
    public function setResultData(?array $resultData): void
    {
        $this->resultData = $resultData;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
