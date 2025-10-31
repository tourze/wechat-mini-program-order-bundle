<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramOrderBundle\Entity\CombinedShoppingInfo;

/**
 * @extends ServiceEntityRepository<CombinedShoppingInfo>
 */
#[AsRepository(entityClass: CombinedShoppingInfo::class)]
class CombinedShoppingInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CombinedShoppingInfo::class);
    }

    /**
     * 根据订单ID查找合单购物信息
     */
    public function findByOrderKey(string $orderId, string $outOrderId): ?CombinedShoppingInfo
    {
        $result = $this->createQueryBuilder('c')
            ->where('c.orderId = :orderId')
            ->andWhere('c.outOrderId = :outOrderId')
            ->setParameter('orderId', $orderId)
            ->setParameter('outOrderId', $outOrderId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result instanceof CombinedShoppingInfo ? $result : null;
    }

    /**
     * 根据支付者OpenID查找合单购物信息列表
     * @return CombinedShoppingInfo[]
     */
    public function findByPayerOpenid(string $openid): array
    {
        /** @var CombinedShoppingInfo[] $result */
        $result = $this->createQueryBuilder('c')
            ->join('c.payer', 'p')
            ->where('p.openId = :openid')
            ->setParameter('openid', $openid)
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    /**
     * 根据账号查找合单购物信息
     * @return array<CombinedShoppingInfo>
     */
    public function findByAccount(mixed $account): array
    {
        return $this->findBy(['account' => $account]);
    }

    /**
     * 根据订单ID查找合单购物信息
     * @return array<CombinedShoppingInfo>
     */
    public function findByOrderId(?string $orderId): array
    {
        return $this->findBy(['orderId' => $orderId]);
    }

    /**
     * 根据外部订单ID查找合单购物信息
     * @return array<CombinedShoppingInfo>
     */
    public function findByOutOrderId(?string $outOrderId): array
    {
        return $this->findBy(['outOrderId' => $outOrderId]);
    }

    /**
     * 根据支付者查找合单购物信息
     * @return array<CombinedShoppingInfo>
     */
    public function findByPayer(mixed $payer): array
    {
        return $this->findBy(['payer' => $payer]);
    }

    public function save(CombinedShoppingInfo $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CombinedShoppingInfo $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
