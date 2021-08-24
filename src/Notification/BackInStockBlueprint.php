<?php

namespace Flamarkt\StockNotifications\Notification;

use Carbon\Carbon;
use Flamarkt\Core\Product\Product;
use Flarum\Notification\Blueprint\BlueprintInterface;
use Flarum\Notification\MailableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BackInStockBlueprint implements BlueprintInterface, MailableInterface
{
    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getSubject()
    {
        return $this->product;
    }

    public function getFromUser()
    {
        return null;
    }

    public function getData()
    {
        return [
            // Ensure each new notification will be dispatched and not treated as duplicate by Flarum
            'now' => Carbon::now()->timestamp,
        ];
    }

    public static function getType(): string
    {
        return 'stockNotification';
    }

    public static function getSubjectModel(): string
    {
        return Product::class;
    }

    public function getEmailView(): array
    {
        return ['html' => 'flamarkt-stock-notifications::emails.backInStock'];
    }

    public function getEmailSubject(TranslatorInterface $translator)
    {
        return $translator->trans('flamarkt-stock-notifications.email.backInStock.subject', [
            '{title}' => $this->product->title,
        ]);
    }
}
