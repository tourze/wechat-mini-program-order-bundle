<?php

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramOrderBundle\Entity\SubOrderList;
use WechatMiniProgramOrderBundle\Enum\DeliveryMode;

/**
 * @method SubOrderList|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubOrderList|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubOrderList[]    findAll()
 * @method SubOrderList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubOrderListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubOrderList::class);
    }

    /**
     * 根据合单ID查找所有子单
     */
    public function findByCombinedShippingInfoId(string $combinedShippingInfoId): array
    {
        return $this->findBy(['combinedShippingInfo' => $combinedShippingInfoId]);
    }

    /**
     * 根据发货模式查找子单
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
        return $this->createQueryBuilder('s')
            ->join('s.orderKey', 'ok')
            ->where('ok.orderId = :orderId')
            ->andWhere('ok.outOrderId = :outOrderId')
            ->setParameter('orderId', $orderId)
            ->setParameter('outOrderId', $outOrderId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
