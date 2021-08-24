<?php

namespace Flamarkt\StockNotifications\Listener;

use Carbon\Carbon;
use Flamarkt\Core\Product\Event\Saving;
use Flamarkt\Core\Product\Product;
use Flamarkt\StockNotifications\Event\Subscribed;
use Flamarkt\StockNotifications\Event\Unsubscribed;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class SaveProduct
{
    protected $events;

    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    public function handle(Saving $event)
    {
        $attributes = (array)Arr::get($event->data, 'data.attributes');

        if (Arr::exists($attributes, 'stockNotification')) {
            $event->actor->assertCan('subscribeToStockNotification', $event->product);

            $state = $event->product->stateForUser($event->actor);

            $updateMeta = false;

            if (Arr::get($attributes, 'stockNotification')) {
                if (is_null($state->stock_notification_requested_at)) {
                    $state->stock_notification_requested_at = Carbon::now();

                    $state->save();

                    $this->events->dispatch(new Subscribed($event->product, $event->actor, $event->actor));

                    $updateMeta = true;
                }
            } else {
                if (!is_null($state->stock_notification_requested_at)) {
                    $state->stock_notification_requested_at = null;

                    $state->save();

                    $this->events->dispatch(new Unsubscribed($event->product, $event->actor, $event->actor));

                    $updateMeta = true;
                }
            }

            if ($updateMeta) {
                $event->product->afterSave(function (Product $product) {
                    $product->stock_notification_request_count = $product->users()->wherePivotNotNull('stock_notification_requested_at')->count();
                    $product->save();
                });
            }
        }
    }
}
