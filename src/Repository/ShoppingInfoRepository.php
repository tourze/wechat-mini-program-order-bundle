<?php

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;

/**
 * @method ShoppingInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppingInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppingInfo[]    findAll()
 * @method ShoppingInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingInfo::class);
    }

    /**
     * 根据订单ID查找购物信息
     */
    public function findByOrderId(string $orderId): ?ShoppingInfo
    {
        return $this->findOneBy(['orderId' => $orderId]);
    }

    /**
     * 根据商户订单ID查找购物信息
     */
    public function findByOutOrderId(string $outOrderId): ?ShoppingInfo
    {
        return $this->findOneBy(['outOrderId' => $outOrderId]);
    }
}
