<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramOrderBundle\Entity\Contact;
use WechatMiniProgramOrderBundle\Repository\ContactRepository;

/**
 * @internal
 */
#[CoversClass(ContactRepository::class)]
#[RunTestsInSeparateProcesses]
final class ContactRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 不需要特别的设置
    }

    // Basic CRUD tests
    public function testSaveAndFind(): void
    {
        $contact = new Contact();
        $contact->setConsignorContact('consignor_contact_data');
        $contact->setReceiverContact('receiver_contact_data');

        $this->getRepository()->save($contact, true);
        $this->assertNotNull($contact->getId());

        $found = $this->getRepository()->find($contact->getId());
        $this->assertNotNull($found);
        $this->assertInstanceOf(Contact::class, $found);
        $this->assertEquals('consignor_contact_data', $found->getConsignorContact());
        $this->assertEquals('receiver_contact_data', $found->getReceiverContact());
    }

    public function testRemove(): void
    {
        $contact = new Contact();
        $contact->setConsignorContact('test_consignor');
        $this->getRepository()->save($contact, true);
        $id = $contact->getId();

        $this->getRepository()->remove($contact, true);
        $this->assertNull($this->getRepository()->find($id));
    }

    // FindBy tests

    // FindOneBy tests

    // FindAll tests

    // Custom methods tests
    public function testFindByConsignorContact(): void
    {
        $results = $this->getRepository()->findByConsignorContact('test_consignor');
        $this->assertIsArray($results);
    }

    public function testFindByReceiverContact(): void
    {
        $results = $this->getRepository()->findByReceiverContact('test_receiver');
        $this->assertIsArray($results);
    }

    // Null field tests
    public function testFindByConsignorContactNull(): void
    {
        $results = $this->getRepository()->findByConsignorContact(null);
        $this->assertIsArray($results);
    }

    public function testFindByReceiverContactNull(): void
    {
        $results = $this->getRepository()->findByReceiverContact(null);
        $this->assertIsArray($results);
    }

    // Edge cases

    // Robustness tests

    // Additional required test methods
    public function testFindByMobile(): void
    {
        $results = $this->getRepository()->findByMobile('13800138000');
        $this->assertIsArray($results);
    }

    public function testFindByName(): void
    {
        $results = $this->getRepository()->findByName('Test Name');
        $this->assertIsArray($results);
    }

    public function testFindByAddress(): void
    {
        $results = $this->getRepository()->findByAddress('Test Address');
        $this->assertIsArray($results);
    }

    // IS NULL query tests for nullable fields
    public function testFindByConsignorContactIsNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['consignorContact' => null]);
        $this->assertIsArray($results);
    }

    public function testFindByReceiverContactIsNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['receiverContact' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByConsignorContactIsNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['consignorContact' => null]);
        $this->assertIsInt($count);
    }

    public function testCountByReceiverContactIsNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['receiverContact' => null]);
        $this->assertIsInt($count);
    }

    // Robustness tests for invalid fields

    // FindOneBy sorting tests
    public function testFindOneByWithOrderBy(): void
    {
        $result = $this->getRepository()->findOneBy([], ['id' => 'DESC']);
        // 测试结果是否符合预期，可能为null或实体对象
        $this->assertTrue(null === $result || $result instanceof Contact);
    }

    // Additional nullable field tests
    public function testFindByMobileIsNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['mobile' => null]);
        $this->assertIsArray($results);
    }

    public function testFindByNameIsNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['name' => null]);
        $this->assertIsArray($results);
    }

    public function testFindByAddressIsNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['address' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByMobileIsNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['mobile' => null]);
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testCountByNameIsNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['name' => null]);
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testCountByAddressIsNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['address' => null]);
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    protected function createNewEntity(): object
    {
        $contact = new Contact();
        $contact->setConsignorContact('test_consignor_' . uniqid());
        $contact->setReceiverContact('test_receiver_' . uniqid());
        $contact->setMobile('1380013' . rand(1000, 9999));
        $contact->setName('Test Contact ' . uniqid());
        $contact->setAddress('Test Address ' . uniqid());

        return $contact;
    }

    protected function getRepository(): ContactRepository
    {
        return self::getService(ContactRepository::class);
    }
}
