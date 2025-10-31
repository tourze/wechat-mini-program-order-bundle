<?php

declare(strict_types=1);

namespace WechatMiniProgramOrderBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfoVerifyUpload;
use WechatMiniProgramOrderBundle\Enum\ShoppingInfoVerifyStatus;

class ShoppingInfoVerifyUploadFixtures extends Fixture
{
    public const SHOPPING_INFO_VERIFY_UPLOAD_REFERENCE = 'shopping-info-verify-upload-1';

    public function load(ObjectManager $manager): void
    {
        $upload = new ShoppingInfoVerifyUpload();
        $upload->setOrderId('order_123456');
        $upload->setOutOrderId('out_order_789012');
        $upload->setPathId('path_345678');
        $upload->setStatus(ShoppingInfoVerifyStatus::APPROVED); // 使用APPROVED状态避免干扰pending测试
        $upload->setFailReason(null);
        $upload->setResultData(['status' => 'success']);

        $manager->persist($upload);
        $manager->flush();

        $this->addReference(self::SHOPPING_INFO_VERIFY_UPLOAD_REFERENCE, $upload);
    }
}
