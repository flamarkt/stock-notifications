import Notification from 'flarum/forum/components/Notification';

export default class BackInStockNotification extends Notification {
    icon() {
        return 'fas fa-bell';
    }

    href() {
        const notification = this.attrs.notification;
        const product = notification.subject();

        return app.route.product(product);
    }

    content() {
        const notification = this.attrs.notification;
        const product = notification.subject();

        return app.translator.trans('flamarkt-stock-notifications.forum.notifications.backInStock', {
            title: product.title(),
        });
    }
}
