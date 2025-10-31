# 微信小程序购物订单包

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)](https://php.net)
[![Symfony](https://img.shields.io/badge/symfony-%5E6.4-green)](https://symfony.com)
[![License](https://img.shields.io/badge/license-MIT-blue)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](../../actions)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)](tests)
[![Tests](https://img.shields.io/badge/tests-passing-brightgreen)](tests)

[English](README.md) | [中文](README.zh-CN.md)

一个用于微信小程序购物订单管理的 Symfony Bundle，
提供购物信息和物流信息上传功能。

## 功能特性

- 🛍️ **购物信息管理**: 上传和管理购物订单详情
- 🚚 **物流信息管理**: 上传和管理物流配送信息
- 🏗️ **实体管理**: 完整的 Doctrine 实体定义，包括订单、商品和物流
- 📊 **仓储模式**: 数据访问的仓储类实现
- 🔄 **事件驱动**: 订单处理的事件订阅者
- 🧪 **完整测试**: 基于 PHPUnit 的全面测试覆盖

## 安装

```bash
composer require tourze/wechat-mini-program-order-bundle
```

## 快速开始

### 1. Bundle 配置

在 `config/bundles.php` 中添加此 Bundle：

```php
return [
    // ... 其他 bundles
    WechatMiniProgramOrderBundle\WechatMiniProgramOrderBundle::class => ['all' => true],
];
```

### 2. 基本用法

```php
<?php

use WechatMiniProgramOrderBundle\Request\UploadShoppingInfoRequest;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;

// 1. 创建订单标识
$orderKey = new OrderKey();
$orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
$orderKey->setMchId('your-mch-id');
$orderKey->setOutTradeNo('your-out-trade-no');

// 2. 创建购物信息
$shoppingInfo = new ShoppingInfo();
$shoppingInfo->setOrderKey($orderKey);
$shoppingInfo->setPayer($user); // UserInterface 实例
$shoppingInfo->setOrderDetailType(OrderDetailType::SHOPPING_ORDER);
$shoppingInfo->setLogisticsType(LogisticsType::EXPRESS);

// 3. 上传购物信息
$request = new UploadShoppingInfoRequest();
$request->setShoppingInfo($shoppingInfo);
```

### 3. 上传购物信息

```php
use WechatMiniProgramOrderBundle\Request\UploadShoppingInfoRequest;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;

$request = new UploadShoppingInfoRequest();
$shoppingInfo = new ShoppingInfo();
$orderKey = new OrderKey();

// 配置购物信息
$shoppingInfo->setOrderKey($orderKey);
$shoppingInfo->setPayer($user);
$shoppingInfo->setOrderDetailType(OrderDetailType::SHOPPING_ORDER);

$request->setShoppingInfo($shoppingInfo);
```

### 4. 上传物流信息

```php
use WechatMiniProgramOrderBundle\Request\UploadShippingInfoRequest;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;

$request = new UploadShippingInfoRequest();
$shippingInfo = new ShippingInfo();

// 配置物流信息
$shippingInfo->setTrackingNo('1234567890');
$shippingInfo->setDeliveryCompany('顺丰快递');

$request->setShippingInfo($shippingInfo);
```

## 详细文档

### 实体概览

#### 核心实体

- **ShoppingInfo**: 主要购物订单信息
- **ShippingInfo**: 物流配送信息
- **OrderKey**: 订单标识键
- **ShoppingItemList**: 购物商品详情
- **Contact**: 订单联系人信息

#### 枚举类

- **OrderStatus**: 订单状态枚举
- **LogisticsType**: 物流类型枚举
- **DeliveryMode**: 配送方式枚举
- **OrderDetailType**: 订单详情类型枚举

### 高级用法

#### 批量操作

```php
// 上传多个购物项目
$items = [];
foreach ($products as $product) {
    $item = new ShoppingItemList();
    $item->setMerchantItemId($product->getId());
    $item->setItemName($product->getName());
    $item->setItemCount($product->getQuantity());
    $items[] = $item;
}

$shoppingInfo->setItemList($items);
```

#### 事件处理

```php
// 监听订单事件
class OrderEventSubscriber implements EventSubscriberInterface
{
    public function onOrderCreated(OrderCreatedEvent $event): void
    {
        // 处理订单创建
    }
}
```

### 测试

运行测试套件：

```bash
./vendor/bin/phpunit packages/wechat-mini-program-order-bundle/tests
```

### 系统要求

- PHP 8.1+
- Symfony 6.4+
- Doctrine ORM 3.0+

### 相关文档

- [微信小程序购物订单文档](
  https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/
  business-capabilities/shopping-order/shopping-order.html)
- [上传购物信息 API](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/
  shopping-order/normal-shopping-detail/uploadShoppingInfo.html)
- [上传物流信息 API](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/
  shopping-order/normal-shopping-detail/uploadShippingInfo.html)

## 贡献

我们欢迎贡献！请查看 [CONTRIBUTING.md](../../CONTRIBUTING.md) 了解详情。

## 许可证

MIT