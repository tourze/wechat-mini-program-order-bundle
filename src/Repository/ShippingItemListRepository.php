<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramOrderBundle\Entity\ShippingItemList;

/**
 * @extends ServiceEntityRepository<ShippingItemList>
 */
#[AsRepository(entityClass: ShippingItemList::class)]
final class ShippingItemListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingItemList::class);
    }

    /**
     * 根据商户商品ID查找物流商品
     * @return array<ShippingItemList>
     */
    public function findByMerchantItemId(string $merchantItemId): array
    {
        return $this->findBy(['merchantItemId' => $merchantItemId]);
    }

    /**
     * 根据物流单号查找物流商品列表
     * @return ShippingItemList[]
     */
    public function findByShippingList(string $trackingNo): array
    {
        /** @var ShippingItemList[] $result */
        $result = $this->createQueryBuilder('sil')
            ->innerJoin('sil.shippingList', 'sl')
            ->where('sl.trackingNo = :trackingNo')
            ->setParameter('trackingNo', $trackingNo)
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    public function save(ShippingItemList $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShippingItemList $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
