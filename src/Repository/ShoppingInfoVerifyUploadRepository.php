<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfoVerifyUpload;
use WechatMiniProgramOrderBundle\Enum\ShoppingInfoVerifyStatus;

/**
 * @extends ServiceEntityRepository<ShoppingInfoVerifyUpload>
 */
#[AsRepository(entityClass: ShoppingInfoVerifyUpload::class)]
final class ShoppingInfoVerifyUploadRepository extends ServiceEntityRepository
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
        $result = $this->createQueryBuilder('s')
            ->andWhere('s.orderId = :orderId')
            ->setParameter('orderId', $orderId)
            ->orderBy('s.createTime', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result instanceof ShoppingInfoVerifyUpload ? $result : null;
    }

    /**
     * 查找所有待验证的记录
     * @return array<ShoppingInfoVerifyUpload>
     */
    public function findAllPending(): array
    {
        return $this->findBy(['status' => ShoppingInfoVerifyStatus::PENDING]);
    }

    public function save(ShoppingInfoVerifyUpload $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShoppingInfoVerifyUpload $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
