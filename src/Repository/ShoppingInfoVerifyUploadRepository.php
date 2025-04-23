<?php

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfoVerifyUpload;
use WechatMiniProgramOrderBundle\Enum\ShoppingInfoVerifyStatus;

/**
 * @method ShoppingInfoVerifyUpload|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppingInfoVerifyUpload|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppingInfoVerifyUpload[]    findAll()
 * @method ShoppingInfoVerifyUpload[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingInfoVerifyUploadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingInfoVerifyUpload::class);
    }

    /**
     * 根据订单ID查找最新的验证记录
     */
    public function findLatestByOrderId(string $orderId): ?ShoppingInfoVerifyUpload
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.orderId = :orderId')
            ->setParameter('orderId', $orderId)
            ->orderBy('s.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * 查找所有待验证的记录
     */
    public function findAllPending(): array
    {
        return $this->findBy(['status' => ShoppingInfoVerifyStatus::PENDING]);
    }
}
