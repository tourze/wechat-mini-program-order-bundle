<?php

namespace WechatMiniProgramOrderBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramOrderBundle\Entity\Contact;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * 根据手机号查找联系人
     */
    public function findByMobile(string $mobile): array
    {
        return $this->findBy(['mobile' => $mobile]);
    }

    /**
     * 根据姓名查找联系人
     */
    public function findByName(string $name): array
    {
        return $this->findBy(['name' => $name]);
    }

    /**
     * 根据地址查找联系人
     */
    public function findByAddress(string $address): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.address LIKE :address')
            ->setParameter('address', '%' . $address . '%')
            ->getQuery()
            ->getResult();
    }
}
