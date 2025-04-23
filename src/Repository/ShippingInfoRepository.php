<?php

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;

/**
 * @method ShippingInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShippingInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShippingInfo[]    findAll()
 * @method ShippingInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShippingInfoRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingInfo::class);
    }

    /**
     * 根据物流单号查找物流信息
     */
    public function findByTrackingNo(string $trackingNo): ?ShippingInfo
    {
        return $this->findOneBy(['trackingNo' => $trackingNo]);
    }

    /**
     * 根据快递公司查找物流信息列表
     */
    public function findByExpressCompany(string $expressCompany): array
    {
        return $this->findBy(['expressCompany' => $expressCompany]);
    }

    /**
     * 根据收货人手机号查找物流信息
     */
    public function findByDeliveryMobile(string $mobile): array
    {
        return $this->findBy(['deliveryMobile' => $mobile]);
    }

    /**
     * 根据收货人姓名查找物流信息
     */
    public function findByDeliveryName(string $name): array
    {
        return $this->findBy(['deliveryName' => $name]);
    }
}
