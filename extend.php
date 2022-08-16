<?php

namespace Flamarkt\StockNotifications;

use Flamarkt\Core\Api\Serializer\BasicProductSerializer;
use Flamarkt\Core\Api\Serializer\ProductSerializer;
use Flamarkt\Core\Product\Event\Saving;
use Flamarkt\Core\Product\Product;
use Flarum\Extend;

return [
    (new Extend\Frontend('backoffice'))
        ->js(__DIR__ . '/js/dist/backoffice.js'),

    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js'),

    new Extend\Locales(__DIR__ . '/locale'),

    (new Extend\Routes('api'))
        ->post('/flamarkt/products/{id}/stock-notifications', 'flamarkt-stock-notifications.notify', Api\Controller\NotifyController::class),

    (new Extend\Event())
        ->listen(Saving::class, Listener\SaveProduct::class),

    (new Extend\ApiSerializer(ProductSerializer::class))
        ->attributes(ProductAttributes::class),

    (new Extend\Policy())
        ->modelPolicy(Product::class, Access\ProductPolicy::class),

    (new Extend\View())
        ->namespace('flamarkt-stock-notifications', __DIR__ . '/views'),

    (new Extend\Notification())
        ->type(Notification\BackInStockBlueprint::class, BasicProductSerializer::class, ['alert', 'email']),
];
