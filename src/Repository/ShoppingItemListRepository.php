<?php

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;

/**
 * @method ShoppingItemList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppingItemList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppingItemList[]    findAll()
 * @method ShoppingItemList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingItemListRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingItemList::class);
    }

    /**
     * 根据商品ID查找购物商品列表项
     */
    public function findByMerchantItemId(string $merchantItemId): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.merchantItemId = :merchantItemId')
            ->setParameter('merchantItemId', $merchantItemId)
            ->getQuery()
            ->getResult();
    }

    /**
     * 根据购物信息查找购物商品列表项
     */
    public function findByShoppingInfo(string $shoppingInfoId): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.shoppingInfo', 'si')
            ->where('si.id = :shoppingInfoId')
            ->setParameter('shoppingInfoId', $shoppingInfoId)
            ->getQuery()
            ->getResult();
    }
}
