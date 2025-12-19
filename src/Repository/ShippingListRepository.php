<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramOrderBundle\Entity\ShippingList;

/**
 * @extends ServiceEntityRepository<ShippingList>
 */
#[AsRepository(entityClass: ShippingList::class)]
final class ShippingListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingList::class);
    }

    /**
     * 根据订单ID查找物流信息
     * @return array<ShippingList>
     */
    public function findByOrderId(string $orderId): array
    {
        /** @var ShippingList[] $result */
        $result = $this->createQueryBuilder('sl')
            ->join('sl.subOrder', 'so')
            ->join('so.orderKey', 'ok')
            ->where('ok.orderId = :orderId')
            ->setParameter('orderId', $orderId)
            ->orderBy('sl.createTime', 'DESC')
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    /**
     * 根据物流单号查找物流信息
     */
    public function findByTrackingNo(string $trackingNo): ?ShippingList
    {
        $result = $this->findOneBy(['trackingNo' => $trackingNo]);

        return $result instanceof ShippingList ? $result : null;
    }

    /**
     * 查找需要更新物流信息的记录
     * @return array<ShippingList>
     */
    public function findNeedUpdateTracking(\DateTimeInterface $beforeTime): array
    {
        /** @var ShippingList[] $result */
        $result = $this->createQueryBuilder('s')
            ->andWhere('s.lastTrackingTime IS NULL OR s.lastTrackingTime < :beforeTime')
            ->setParameter('beforeTime', $beforeTime)
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    public function save(ShippingList $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShippingList $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
