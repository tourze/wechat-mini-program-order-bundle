<?php

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramOrderBundle\Entity\ShippingItemList;

/**
 * @method ShippingItemList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShippingItemList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShippingItemList[]    findAll()
 * @method ShippingItemList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShippingItemListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingItemList::class);
    }

    /**
     * 根据商户商品ID查找物流商品
     */
    public function findByMerchantItemId(string $merchantItemId): array
    {
        return $this->findBy(['merchantItemId' => $merchantItemId]);
    }

    /**
     * 根据物流单号查找物流商品列表
     */
    public function findByShippingList(string $shippingListId): array
    {
        return $this->findBy(['shippingList' => $shippingListId]);
    }
}
