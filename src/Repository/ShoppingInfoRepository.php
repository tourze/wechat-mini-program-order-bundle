<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;

/**
 * @extends ServiceEntityRepository<ShoppingInfo>
 */
#[AsRepository(entityClass: ShoppingInfo::class)]
final class ShoppingInfoRepository extends ServiceEntityRepository
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
        $result = $this->createQueryBuilder('si')
            ->join('si.orderKey', 'ok')
            ->where('ok.orderId = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result instanceof ShoppingInfo ? $result : null;
    }

    /**
     * 根据订单ID查找购物信息（包含关联数据）
     * 预防N+1查询问题：一次性加载所有关联实体
     */
    public function findByOrderIdWithRelations(string $orderId): ?ShoppingInfo
    {
        $result = $this->createQueryBuilder('si')
            ->leftJoin('si.orderKey', 'ok')
            ->leftJoin('si.account', 'a')
            ->leftJoin('si.payer', 'p')
            ->leftJoin('si.itemList', 'il')
            ->addSelect('ok', 'a', 'p', 'il')
            ->where('ok.orderId = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result instanceof ShoppingInfo ? $result : null;
    }

    /**
     * 根据商户订单ID查找购物信息
     */
    public function findByOutOrderId(string $outOrderId): ?ShoppingInfo
    {
        $result = $this->createQueryBuilder('si')
            ->join('si.orderKey', 'ok')
            ->where('ok.outOrderId = :outOrderId')
            ->setParameter('outOrderId', $outOrderId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result instanceof ShoppingInfo ? $result : null;
    }

    /**
     * 根据商户订单ID查找购物信息（包含关联数据）
     * 预防N+1查询问题：一次性加载所有关联实体
     */
    public function findByOutOrderIdWithRelations(string $outOrderId): ?ShoppingInfo
    {
        $result = $this->createQueryBuilder('si')
            ->leftJoin('si.orderKey', 'ok')
            ->leftJoin('si.account', 'a')
            ->leftJoin('si.payer', 'p')
            ->leftJoin('si.itemList', 'il')
            ->addSelect('ok', 'a', 'p', 'il')
            ->where('ok.outOrderId = :outOrderId')
            ->setParameter('outOrderId', $outOrderId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result instanceof ShoppingInfo ? $result : null;
    }

    /**
     * 批量查找购物信息（包含关联数据）
     * 优化批量查询性能，预防N+1查询问题
     *
     * @param array<string> $orderIds
     * @return array<ShoppingInfo>
     */
    public function findByOrderIdsWithRelations(array $orderIds): array
    {
        if ([] === $orderIds) {
            return [];
        }

        $result = $this->createQueryBuilder('si')
            ->leftJoin('si.orderKey', 'ok')
            ->leftJoin('si.account', 'a')
            ->leftJoin('si.payer', 'p')
            ->leftJoin('si.itemList', 'il')
            ->addSelect('ok', 'a', 'p', 'il')
            ->where('ok.orderId IN (:orderIds)')
            ->setParameter('orderIds', $orderIds)
            ->orderBy('si.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        /** @var array<mixed> $result */
        return array_filter($result, static fn ($item): bool => $item instanceof ShoppingInfo);
    }

    /**
     * 根据支付者查找购物信息列表（包含关联数据）
     * 优化用户订单列表查询，预防N+1查询问题
     *
     * @return array<ShoppingInfo>
     */
    public function findByPayerWithRelations(string $payerOpenId, int $limit = 20, int $offset = 0): array
    {
        $result = $this->createQueryBuilder('si')
            ->leftJoin('si.orderKey', 'ok')
            ->leftJoin('si.account', 'a')
            ->leftJoin('si.payer', 'p')
            ->leftJoin('si.itemList', 'il')
            ->addSelect('ok', 'a', 'p', 'il')
            ->where('p.openId = :payerOpenId')
            ->setParameter('payerOpenId', $payerOpenId)
            ->orderBy('si.createTime', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;

        /** @var array<mixed> $result */
        return array_filter($result, static fn ($item): bool => $item instanceof ShoppingInfo);
    }

    /**
     * 根据账户查找购物信息列表（包含关联数据）
     * 优化商户订单管理查询，预防N+1查询问题
     *
     * @return array<ShoppingInfo>
     */
    public function findByAccountWithRelations(string $appId, int $limit = 50, int $offset = 0): array
    {
        $result = $this->createQueryBuilder('si')
            ->leftJoin('si.orderKey', 'ok')
            ->leftJoin('si.account', 'a')
            ->leftJoin('si.payer', 'p')
            ->leftJoin('si.itemList', 'il')
            ->addSelect('ok', 'a', 'p', 'il')
            ->where('a.appId = :appId')
            ->setParameter('appId', $appId)
            ->orderBy('si.createTime', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;

        /** @var array<mixed> $result */
        return array_filter($result, static fn ($item): bool => $item instanceof ShoppingInfo);
    }

    public function save(ShoppingInfo $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * 批量保存购物信息
     * 优化批量操作性能
     *
     * @param array<ShoppingInfo> $entities
     */
    public function saveBatch(array $entities, bool $flush = true): void
    {
        $em = $this->getEntityManager();

        foreach ($entities as $entity) {
            $em->persist($entity);
        }

        if ($flush) {
            $em->flush();
        }
    }

    public function remove(ShoppingInfo $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
