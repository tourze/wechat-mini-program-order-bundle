<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramOrderBundle\Entity\Contact;

/**
 * @extends ServiceEntityRepository<Contact>
 */
#[AsRepository(entityClass: Contact::class)]
final class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * 根据手机号查找联系人
     * @return array<Contact>
     */
    public function findByMobile(string $mobile): array
    {
        return $this->findBy(['mobile' => $mobile]);
    }

    /**
     * 根据姓名查找联系人
     * @return array<Contact>
     */
    public function findByName(string $name): array
    {
        return $this->findBy(['name' => $name]);
    }

    /**
     * 根据地址查找联系人
     * @return array<Contact>
     */
    public function findByAddress(string $address): array
    {
        /** @var Contact[] $result */
        $result = $this->createQueryBuilder('c')
            ->where('c.address LIKE :address')
            ->setParameter('address', '%' . $address . '%')
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    /**
     * 根据寄件人联系方式查找联系人
     * @return array<Contact>
     */
    public function findByConsignorContact(?string $consignorContact): array
    {
        return $this->findBy(['consignorContact' => $consignorContact]);
    }

    /**
     * 根据收件人联系方式查找联系人
     * @return array<Contact>
     */
    public function findByReceiverContact(?string $receiverContact): array
    {
        return $this->findBy(['receiverContact' => $receiverContact]);
    }

    public function save(Contact $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contact $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
