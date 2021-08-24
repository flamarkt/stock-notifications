<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $schema->table('flamarkt_product_user', function (Blueprint $table) {
            $table->dateTime('stock_notification_requested_at')->nullable();
        });
    },
    'down' => function (Builder $schema) {
        $schema->table('flamarkt_product_user', function (Blueprint $table) {
            $table->dropColumn('stock_notification_requested_at');
        });
    },
];
