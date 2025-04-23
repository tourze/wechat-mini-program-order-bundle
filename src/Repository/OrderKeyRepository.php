<?php

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramOrderBundle\Entity\OrderKey;

/**
 * @method OrderKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderKey[]    findAll()
 * @method OrderKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderKeyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderKey::class);
    }

    /**
     * 根据订单ID查找订单
     */
    public function findByOrderId(string $orderId): ?OrderKey
    {
        return $this->findOneBy(['orderId' => $orderId]);
    }

    /**
     * 根据商户订单ID查找订单
     */
    public function findByOutOrderId(string $outOrderId): ?OrderKey
    {
        return $this->findOneBy(['outOrderId' => $outOrderId]);
    }

    /**
     * 根据OpenID查找订单列表
     */
    public function findByOpenid(string $openid): array
    {
        return $this->findBy(['openid' => $openid]);
    }

    /**
     * 根据路径ID查找订单列表
     */
    public function findByPathId(string $pathId): array
    {
        return $this->findBy(['pathId' => $pathId]);
    }
}
