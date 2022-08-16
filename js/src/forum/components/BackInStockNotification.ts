import app from 'flarum/forum/app';
import Notification from 'flarum/forum/components/Notification';
import Product from 'flamarkt/core/common/models/Product';

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
        const product = notification.subject() as Product;

        return app.translator.trans('flamarkt-stock-notifications.forum.notifications.backInStock', {
            title: product.title(),
        });
    }

    excerpt() {
        return this.content();
    }
}
