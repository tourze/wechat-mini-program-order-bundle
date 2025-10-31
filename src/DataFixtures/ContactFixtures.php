<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramOrderBundle\Entity\Contact;

class ContactFixtures extends Fixture
{
    public const CONTACT_REFERENCE = 'contact-1';

    public function load(ObjectManager $manager): void
    {
        $contact = new Contact();
        $contact->setConsignorContact('张三|13800138000|北京市朝阳区');
        $contact->setReceiverContact('李四|13900139000|上海市浦东新区');

        $manager->persist($contact);
        $manager->flush();

        $this->addReference(self::CONTACT_REFERENCE, $contact);
    }
}
