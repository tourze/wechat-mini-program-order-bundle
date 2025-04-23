<?php

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramOrderBundle\Entity\ShippingList;

/**
 * @method ShippingList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShippingList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShippingList[]    findAll()
 * @method ShippingList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShippingListRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingList::class);
    }

    /**
     * 根据订单ID查找物流信息
     */
    public function findByOrderId(string $orderId): array
    {
        return $this->findBy(['orderId' => $orderId], ['createdAt' => 'DESC']);
    }

    /**
     * 根据物流单号查找物流信息
     */
    public function findByTrackingNo(string $trackingNo): ?ShippingList
    {
        return $this->findOneBy(['trackingNo' => $trackingNo]);
    }

    /**
     * 查找需要更新物流信息的记录
     */
    public function findNeedUpdateTracking(\DateTimeInterface $beforeTime): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.lastTrackingTime IS NULL OR s.lastTrackingTime < :beforeTime')
            ->setParameter('beforeTime', $beforeTime)
            ->getQuery()
            ->getResult();
    }
}
