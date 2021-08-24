<?php

namespace Flamarkt\StockNotifications\Event;

use Flamarkt\Core\Product\Product;
use Flarum\User\User;

/**
 * When the user is subscribed manually by themselves or by an admin
 */
class Subscribed
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
