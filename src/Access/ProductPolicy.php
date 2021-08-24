<?php

namespace Flamarkt\StockNotifications\Access;

use Flamarkt\Core\Product\Product;
use Flarum\User\Access\AbstractPolicy;
use Flarum\User\User;

class ProductPolicy extends AbstractPolicy
{
    public function subscribeToStockNotification(User $actor, Product $product)
    {
        return $actor->hasPermission('flamarkt-stock-notifications.subscribe');
    }

    public function sendStockNotification(User $actor, Product $product)
    {
        return $actor->can('backoffice');
    }
}
