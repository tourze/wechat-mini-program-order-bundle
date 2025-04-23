<?php

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramOrderBundle\Entity\CombinedShoppingInfo;

/**
 * @method CombinedShoppingInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method CombinedShoppingInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method CombinedShoppingInfo[]    findAll()
 * @method CombinedShoppingInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CombinedShoppingInfoRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CombinedShoppingInfo::class);
    }

    /**
     * 根据订单ID查找合单购物信息
     */
    public function findByOrderKey(string $orderId, string $outOrderId): ?CombinedShoppingInfo
    {
        return $this->createQueryBuilder('c')
            ->join('c.orderKey', 'ok')
            ->where('ok.orderId = :orderId')
            ->andWhere('ok.outOrderId = :outOrderId')
            ->setParameter('orderId', $orderId)
            ->setParameter('outOrderId', $outOrderId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * 根据支付者OpenID查找合单购物信息列表
     */
    public function findByPayerOpenid(string $openid): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.payer', 'p')
            ->where('p.openid = :openid')
            ->setParameter('openid', $openid)
            ->getQuery()
            ->getResult();
    }
}
