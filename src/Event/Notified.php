<?php

namespace Flamarkt\StockNotifications\Event;

use Flamarkt\Core\Product\Product;
use Flarum\User\User;

/**
 * Dispatched after a user's subscription is deleted due to the notification having been sent
 * The Unsubscribed event will not be sent
 */
class Notified
{
    public $product;
    public $user;
    public $actor;

    public function __construct(Product $product, User $user, User $actor = null)
    {
        $this->product = $product;
        $this->user = $user;
        $this->actor = $actor;
    }
}
