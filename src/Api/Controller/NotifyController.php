<?php

namespace Flamarkt\StockNotifications\Api\Controller;

use Flamarkt\Core\Product\ProductRepository;
use Flamarkt\StockNotifications\Event\Notified;
use Flamarkt\StockNotifications\Notification\BackInStockBlueprint;
use Flarum\Http\RequestUtil;
use Flarum\Notification\NotificationSyncer;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NotifyController implements RequestHandlerInterface
{
    protected $repository;
    protected $events;
    protected $notifications;

    public function __construct(ProductRepository $repository, Dispatcher $events, NotificationSyncer $notifications)
    {
        $this->repository = $repository;
        $this->events = $events;
        $this->notifications = $notifications;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = Arr::get($request->getQueryParams(), 'id');
        $actor = RequestUtil::getActor($request);

        $product = $this->repository->findUidOrFail($id, $actor);

        $actor->assertCan('sendStockNotification', $product);

        /**
         * @var Collection $users
         */
        $users = $product->users()->wherePivotNotNull('stock_notification_requested_at')->get();

        foreach ($users as $user) {
            // TODO: this needs optimization. Maybe update all with a single SQL query
            $state = $product->stateForUser($user);

            $state->stock_notification_requested_at = null;
            $state->save();

            $this->events->dispatch(new Notified($product, $user, $actor));
        }

        $product->stock_notification_request_count = 0;
        $product->save();

        $this->notifications->sync(new BackInStockBlueprint($product), $users->all());

        return new EmptyResponse();
    }
}
