<?php

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramOrderBundle\Entity\CombinedShippingInfo;

/**
 * @method CombinedShippingInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method CombinedShippingInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method CombinedShippingInfo[]    findAll()
 * @method CombinedShippingInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CombinedShippingInfoRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CombinedShippingInfo::class);
    }

    /**
     * 根据订单ID查找合并物流信息
     */
    public function findByOrderId(string $orderId): ?CombinedShippingInfo
    {
        return $this->findOneBy(['orderId' => $orderId]);
    }

    /**
     * 根据物流单号查找合并物流信息
     */
    public function findByTrackingNo(string $trackingNo): ?CombinedShippingInfo
    {
        return $this->findOneBy(['trackingNo' => $trackingNo]);
    }

    /**
     * 查找需要更新物流信息的记录
     */
    public function findNeedUpdateTracking(\DateTimeInterface $beforeTime): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.lastTrackingTime IS NULL OR c.lastTrackingTime < :beforeTime')
            ->setParameter('beforeTime', $beforeTime)
            ->getQuery()
            ->getResult();
    }
}
