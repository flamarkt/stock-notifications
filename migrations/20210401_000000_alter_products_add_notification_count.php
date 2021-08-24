<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $schema->table('flamarkt_products', function (Blueprint $table) {
            $table->unsignedInteger('stock_notification_request_count')->default(0);
        });
    },
    'down' => function (Builder $schema) {
        $schema->table('flamarkt_products', function (Blueprint $table) {
            $table->dropColumn('stock_notification_request_count');
        });
    },
];
