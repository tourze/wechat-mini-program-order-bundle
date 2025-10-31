# WeChat Mini Program Order Bundle

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)](https://php.net)
[![Symfony](https://img.shields.io/badge/symfony-%5E6.4-green)](https://symfony.com)
[![License](https://img.shields.io/badge/license-MIT-blue)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](../../actions)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)](tests)
[![Tests](https://img.shields.io/badge/tests-passing-brightgreen)](tests)

[English](README.md) | [中文](README.zh-CN.md)

A Symfony Bundle for WeChat Mini Program shopping order management,
providing shopping information and shipping information upload capabilities.

## Features

- 🛍️ **Shopping Information Management**: Upload and manage shopping order details
- 🚚 **Shipping Information Management**: Upload and manage shipping/logistics information
- 🏗️ **Entity Management**: Complete Doctrine entity definitions for orders, items, and shipping
- 📊 **Repository Pattern**: Repository classes for data access
- 🔄 **Event-Driven**: Event subscribers for order processing
- 🧪 **Fully Tested**: Comprehensive test coverage with PHPUnit

## Installation

```bash
composer require tourze/wechat-mini-program-order-bundle
```

## Quick Start

### 1. Bundle Configuration

Add the bundle to your `config/bundles.php`:

```php
return [
    // ... other bundles
    WechatMiniProgramOrderBundle\WechatMiniProgramOrderBundle::class => ['all' => true],
];
```

### 2. Basic Usage

```php
<?php

use WechatMiniProgramOrderBundle\Request\UploadShoppingInfoRequest;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;
use WechatMiniProgramOrderBundle\Enum\OrderNumberType;
use WechatMiniProgramOrderBundle\Enum\OrderDetailType;
use WechatMiniProgramOrderBundle\Enum\LogisticsType;

// 1. Create order key
$orderKey = new OrderKey();
$orderKey->setOrderNumberType(OrderNumberType::USE_MCH_ORDER);
$orderKey->setMchId('your-mch-id');
$orderKey->setOutTradeNo('your-out-trade-no');

// 2. Create shopping info
$shoppingInfo = new ShoppingInfo();
$shoppingInfo->setOrderKey($orderKey);
$shoppingInfo->setPayer($user); // UserInterface instance
$shoppingInfo->setOrderDetailType(OrderDetailType::SHOPPING_ORDER);
$shoppingInfo->setLogisticsType(LogisticsType::EXPRESS);

// 3. Upload shopping info
$request = new UploadShoppingInfoRequest();
$request->setShoppingInfo($shoppingInfo);
```

### 3. Upload Shopping Information

```php
use WechatMiniProgramOrderBundle\Request\UploadShoppingInfoRequest;
use WechatMiniProgramOrderBundle\Entity\ShoppingInfo;
use WechatMiniProgramOrderBundle\Entity\OrderKey;
use WechatMiniProgramOrderBundle\Entity\ShoppingItemList;

$request = new UploadShoppingInfoRequest();
$shoppingInfo = new ShoppingInfo();
$orderKey = new OrderKey();

// Configure your shopping info
$shoppingInfo->setOrderKey($orderKey);
$shoppingInfo->setPayer($user);
$shoppingInfo->setOrderDetailType(OrderDetailType::SHOPPING_ORDER);

$request->setShoppingInfo($shoppingInfo);
```

### 4. Upload Shipping Information

```php
use WechatMiniProgramOrderBundle\Request\UploadShippingInfoRequest;
use WechatMiniProgramOrderBundle\Entity\ShippingInfo;

$request = new UploadShippingInfoRequest();
$shippingInfo = new ShippingInfo();

// Configure your shipping info
$shippingInfo->setTrackingNo('1234567890');
$shippingInfo->setDeliveryCompany('SF Express');

$request->setShippingInfo($shippingInfo);
```

## Documentation

### Entity Overview

#### Core Entities

- **ShoppingInfo**: Main shopping order information
- **ShippingInfo**: Shipping and logistics information
- **OrderKey**: Order identification keys
- **ShoppingItemList**: Shopping item details
- **Contact**: Contact information for orders

#### Enums

- **OrderStatus**: Order status enumeration
- **LogisticsType**: Logistics type enumeration
- **DeliveryMode**: Delivery mode enumeration
- **OrderDetailType**: Order detail type enumeration

### Advanced Usage

#### Batch Operations

```php
// Upload multiple shopping items
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

#### Event Handling

```php
// Listen to order events
class OrderEventSubscriber implements EventSubscriberInterface
{
    public function onOrderCreated(OrderCreatedEvent $event): void
    {
        // Handle order creation
    }
}
```

### Testing

Run the test suite:

```bash
./vendor/bin/phpunit packages/wechat-mini-program-order-bundle/tests
```

### Requirements

- PHP 8.1+
- Symfony 6.4+
- Doctrine ORM 3.0+

### References

- [WeChat Mini Program Shopping Order Documentation](
  https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/
  business-capabilities/shopping-order/shopping-order.html)
- [Upload Shopping Info API](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/
  shopping-order/normal-shopping-detail/uploadShoppingInfo.html)
- [Upload Shipping Info API](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/
  shopping-order/normal-shopping-detail/uploadShippingInfo.html)

## Contributing

We welcome contributions! Please see [CONTRIBUTING.md](../../CONTRIBUTING.md) for details.

## License

MIT