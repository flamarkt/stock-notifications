<?php

namespace Flamarkt\StockNotifications;

use Flamarkt\Core\Api\Serializer\ProductSerializer;
use Flamarkt\Core\Product\Product;

class ProductAttributes
{
    public function __invoke(ProductSerializer $serializer, Product $product): array
    {
        $attributes = [];

        if ($serializer->getActor()->can('backoffice')) {
            $attributes['stockNotificationRequestCount'] = (int)$product->stock_notification_request_count;
        }

        if ($serializer->getActor()->cannot('subscribeToStockNotification', $product)) {
            return $attributes;
        }

        return $attributes + [
                'canSubscribeToStockNotification' => true,
                'stockNotification' => !is_null(optional($product->cartState)->stock_notification_requested_at),
            ];
    }
}
