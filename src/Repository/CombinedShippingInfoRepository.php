<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;

/**
 * @extends ServiceEntityRepository<CombinedShippingInfo>
 */
#[AsRepository(entityClass: CombinedShippingInfo::class)]
final class CombinedShippingInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CombinedShippingInfo::class);
    }

    /**
     * 根据订单ID查找合并物流信息
     */
    public function findByOrderId(string $orderId): ?CombinedShippingInfo
    {
        // 由于没有 orderId 字段，这个方法可能需要根据业务逻辑调整
        // 暂时返回 null，避免查询错误
        return null;
    }

    /**
     * 根据物流单号查找合并物流信息
     */
    public function findByTrackingNo(string $trackingNo): ?CombinedShippingInfo
    {
        // 由于没有 trackingNo 字段，这个方法可能需要根据业务逻辑调整
        // 暂时返回 null，避免查询错误
        return null;
    }

    /**
     * 查找需要更新物流信息的记录
     * @return array<CombinedShippingInfo>
     */
    public function findNeedUpdateTracking(\DateTimeInterface $beforeTime): array
    {
        // 由于没有 lastTrackingTime 字段，这个方法可能需要根据业务逻辑调整
        // 暂时返回空数组，避免查询错误
        return [];
    }

    /**
     * 根据账号查找合并物流信息
     * @return array<CombinedShippingInfo>
     */
    public function findByAccount(mixed $account): array
    {
        return $this->findBy(['account' => $account]);
    }

    /**
     * 根据订单键查找合并物流信息
     */
    public function findByOrderKey(mixed $orderKey): ?CombinedShippingInfo
    {
        $result = $this->findOneBy(['orderKey' => $orderKey]);

        return $result instanceof CombinedShippingInfo ? $result : null;
    }

    /**
     * 根据支付者查找合并物流信息
     * @return array<CombinedShippingInfo>
     */
    public function findByPayer(mixed $payer): array
    {
        return $this->findBy(['payer' => $payer]);
    }

    public function save(CombinedShippingInfo $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CombinedShippingInfo $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
