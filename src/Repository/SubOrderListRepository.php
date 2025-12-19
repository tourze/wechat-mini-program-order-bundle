<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;
use WechatMiniProgramOrderBundle\Enum\DeliveryMode;

/**
 * @extends ServiceEntityRepository<SubOrderList>
 */
#[AsRepository(entityClass: SubOrderList::class)]
final class SubOrderListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubOrderList::class);
    }

    /**
     * 根据合单ID查找所有子单
     * @return array<SubOrderList>
     */
    public function findByCombinedShippingInfoId(string $combinedShippingInfoId): array
    {
        return $this->findBy(['combinedShippingInfo' => $combinedShippingInfoId]);
    }

    /**
     * 根据发货模式查找子单
     * @return array<SubOrderList>
     */
    public function findByDeliveryMode(DeliveryMode $deliveryMode): array
    {
        return $this->findBy(['deliveryMode' => $deliveryMode]);
    }

    /**
     * 查找指定订单的子单信息
     */
    public function findByOrderKey(string $orderId, string $outOrderId): ?SubOrderList
    {
        $result = $this->createQueryBuilder('s')
            ->join('s.orderKey', 'ok')
            ->where('ok.orderId = :orderId')
            ->andWhere('ok.outOrderId = :outOrderId')
            ->setParameter('orderId', $orderId)
            ->setParameter('outOrderId', $outOrderId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result instanceof SubOrderList ? $result : null;
    }

    public function save(SubOrderList $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SubOrderList $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
