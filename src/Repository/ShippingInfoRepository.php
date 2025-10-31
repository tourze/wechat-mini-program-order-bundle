<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;

/**
 * @extends ServiceEntityRepository<ShippingInfo>
 */
#[AsRepository(entityClass: ShippingInfo::class)]
class ShippingInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingInfo::class);
    }

    /**
     * 根据物流单号查找物流信息
     */
    public function findByTrackingNo(string $trackingNo): ?ShippingInfo
    {
        $result = $this->findOneBy(['trackingNo' => $trackingNo]);

        return $result instanceof ShippingInfo ? $result : null;
    }

    /**
     * 根据快递公司查找物流信息列表
     * @return array<ShippingInfo>
     */
    public function findByExpressCompany(string $expressCompany): array
    {
        return $this->findBy(['expressCompany' => $expressCompany]);
    }

    /**
     * 根据收货人手机号查找物流信息
     * @return array<ShippingInfo>
     */
    public function findByDeliveryMobile(string $mobile): array
    {
        return $this->findBy(['deliveryMobile' => $mobile]);
    }

    /**
     * 根据收货人姓名查找物流信息
     * @return array<ShippingInfo>
     */
    public function findByDeliveryName(string $name): array
    {
        return $this->findBy(['deliveryName' => $name]);
    }

    /**
     * 根据账号查找物流信息
     * @param mixed $account
     * @return array<ShippingInfo>
     */
    public function findByAccount($account): array
    {
        return $this->findBy(['account' => $account]);
    }

    public function save(ShippingInfo $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShippingInfo $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
