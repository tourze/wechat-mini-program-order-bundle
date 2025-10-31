<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramOrderBundle\Repository\ContactRepository;

/**
 * 联系方式信息
 */
#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_contact', options: ['comment' => '联系方式信息表'])]
class Contact implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    #[ORM\Column(length: 1024, nullable: true, options: ['comment' => '寄件人联系方式，采用掩码传输'])]
    #[Assert\Length(max: 1024)]
    private ?string $consignorContact = null;

    #[ORM\Column(length: 1024, nullable: true, options: ['comment' => '收件人联系方式，采用掩码传输'])]
    #[Assert\Length(max: 1024)]
    private ?string $receiverContact = null;

    #[ORM\Column(length: 64, nullable: true, options: ['comment' => '联系人手机号'])]
    #[Assert\Length(max: 64)]
    private ?string $mobile = null;

    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '联系人姓名'])]
    #[Assert\Length(max: 128)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true, options: ['comment' => '联系人地址'])]
    #[Assert\Length(max: 255)]
    private ?string $address = null;

    public function getConsignorContact(): ?string
    {
        return $this->consignorContact;
    }

    public function setConsignorContact(?string $consignorContact): void
    {
        $this->consignorContact = $consignorContact;
    }

    public function getReceiverContact(): ?string
    {
        return $this->receiverContact;
    }

    public function setReceiverContact(?string $receiverContact): void
    {
        $this->receiverContact = $receiverContact;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
