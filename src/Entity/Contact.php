<?php

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramOrderBundle\Repository\ContactRepository;

/**
 * 联系方式信息
 */
#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_contact', options: ['comment' => '联系方式信息表'])]
class Contact implements Stringable
{
    use TimestampableAware;
    use BlameableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[ORM\Column(length: 1024, nullable: true, options: ['comment' => '寄件人联系方式，采用掩码传输'])]
    private ?string $consignorContact = null;

    #[ORM\Column(length: 1024, nullable: true, options: ['comment' => '收件人联系方式，采用掩码传输'])]
    private ?string $receiverContact = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getConsignorContact(): ?string
    {
        return $this->consignorContact;
    }

    public function setConsignorContact(?string $consignorContact): self
    {
        $this->consignorContact = $consignorContact;

        return $this;
    }

    public function getReceiverContact(): ?string
    {
        return $this->receiverContact;
    }

    public function setReceiverContact(?string $receiverContact): self
    {
        $this->receiverContact = $receiverContact;

        return $this;
    }
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
