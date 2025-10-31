<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;

/**
 * @extends ServiceEntityRepository<ShoppingItemList>
 */
#[AsRepository(entityClass: ShoppingItemList::class)]
class ShoppingItemListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingItemList::class);
    }

    /**
     * 根据商品ID查找购物商品列表项
     * @return ShoppingItemList[]
     */
    public function findByMerchantItemId(string $merchantItemId): array
    {
        /** @var ShoppingItemList[] $result */
        $result = $this->createQueryBuilder('s')
            ->where('s.merchantItemId = :merchantItemId')
            ->setParameter('merchantItemId', $merchantItemId)
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    /**
     * 根据购物信息查找购物商品列表项
     * @return ShoppingItemList[]
     */
    public function findByShoppingInfo(string $shoppingInfoId): array
    {
        /** @var ShoppingItemList[] $result */
        $result = $this->createQueryBuilder('s')
            ->join('s.shoppingInfo', 'si')
            ->where('si.id = :shoppingInfoId')
            ->setParameter('shoppingInfoId', $shoppingInfoId)
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    public function save(ShoppingItemList $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShoppingItemList $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
