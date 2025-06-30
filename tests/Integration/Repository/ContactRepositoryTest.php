<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\Tests\Integration\Repository;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramOrderBundle\Repository\ContactRepository;

class ContactRepositoryTest extends TestCase
{
    public function testRepositoryExists(): void
    {
        $this->assertTrue(class_exists(ContactRepository::class));
    }
}
