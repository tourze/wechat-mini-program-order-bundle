<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramOrderBundle\Entity\OrderKey;

/**
 * @extends ServiceEntityRepository<OrderKey>
 */
#[AsRepository(entityClass: OrderKey::class)]
final class OrderKeyRepository extends ServiceEntityRepository
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
        $result = $this->findOneBy(['orderId' => $orderId]);

        return $result instanceof OrderKey ? $result : null;
    }

    /**
     * 根据商户订单ID查找订单
     */
    public function findByOutOrderId(string $outOrderId): ?OrderKey
    {
        $result = $this->findOneBy(['outOrderId' => $outOrderId]);

        return $result instanceof OrderKey ? $result : null;
    }

    /**
     * 根据OpenID查找订单列表
     * @return array<OrderKey>
     */
    public function findByOpenid(string $openid): array
    {
        return $this->findBy(['openid' => $openid]);
    }

    /**
     * 根据路径ID查找订单列表
     * @return array<OrderKey>
     */
    public function findByPathId(string $pathId): array
    {
        return $this->findBy(['pathId' => $pathId]);
    }

    /**
     * 根据创建者查找订单列表
     * @return array<OrderKey>
     */
    public function findByCreatedBy(mixed $createdBy): array
    {
        return $this->findBy(['createdBy' => $createdBy]);
    }

    /**
     * 根据商户号查找订单列表
     * @return array<OrderKey>
     */
    public function findByMchId(?string $mchId): array
    {
        return $this->findBy(['mchId' => $mchId]);
    }

    /**
     * 根据订单单号类型查找订单列表
     * @return array<OrderKey>
     */
    public function findByOrderNumberType(mixed $orderNumberType): array
    {
        return $this->findBy(['orderNumberType' => $orderNumberType]);
    }

    /**
     * 根据商户系统内部订单号查找订单
     */
    public function findByOutTradeNo(?string $outTradeNo): ?OrderKey
    {
        $result = $this->findOneBy(['outTradeNo' => $outTradeNo]);

        return $result instanceof OrderKey ? $result : null;
    }

    /**
     * 根据微信订单号查找订单
     */
    public function findByTransactionId(?string $transactionId): ?OrderKey
    {
        $result = $this->findOneBy(['transactionId' => $transactionId]);

        return $result instanceof OrderKey ? $result : null;
    }

    /**
     * 根据更新者查找订单列表
     * @return array<OrderKey>
     */
    public function findByUpdatedBy(mixed $updatedBy): array
    {
        return $this->findBy(['updatedBy' => $updatedBy]);
    }

    public function save(OrderKey $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OrderKey $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
