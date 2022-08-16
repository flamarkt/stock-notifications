import app from 'flarum/forum/app';
import {extend} from 'flarum/common/extend';
import Button from 'flarum/common/components/Button';
import NotificationGrid from 'flarum/forum/components/NotificationGrid';
import ProductShowLayout from 'flamarkt/core/forum/layouts/ProductShowLayout';
import BackInStockNotification from './components/BackInStockNotification';

app.initializers.add('flamarkt-stock-notifications', () => {
    app.notificationComponents.stockNotification = BackInStockNotification;

    extend(NotificationGrid.prototype, 'notificationTypes', function (items) {
        items.add('stock-notifications', {
            name: 'stockNotification',
            icon: 'fas fa-bell',
            label: app.translator.trans('flamarkt-stock-notifications.forum.settings.stockNotification'),
        });
    });

    extend(ProductShowLayout.prototype, 'priceSection', function (items) {
        if (!this.attrs.product!.attribute('canSubscribeToStockNotification')) {
            return;
        }

        const enabled = this.attrs.product!.attribute('stockNotification');

        items.add('stock-notifications', m('.Form-group', [
            Button.component({
                className: 'Button Button--block',
                icon: 'fa' + (enabled ? 's' : 'r') + ' fa-bell',
                onclick: () => {
                    this.attrs.product!.save({
                        stockNotification: !enabled,
                    });
                },
            }, app.translator.trans('flamarkt-stock-notifications.forum.product.' + (enabled ? 'disable' : 'enable') + 'Notification')),
            m('.helpText', app.translator.trans('flamarkt-stock-notifications.forum.product.notificationHelp')),
        ]));
    });
});
