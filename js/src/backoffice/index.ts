import {extend} from 'flarum/common/extend';
import extractText from 'flarum/common/utils/extractText';
import Button from 'flarum/common/components/Button';
import ProductShowPage from 'flamarkt/core/backoffice/pages/ProductShowPage';

app.initializers.add('flamarkt-stock-notifications', () => {
    app.extensionData.for('flamarkt-stock-notifications')
        .registerPermission({
            icon: 'fas fa-bell',
            label: app.translator.trans('flamarkt-stock-notifications.backoffice.permissions.subscribe'),
            permission: 'flamarkt-stock-notifications.subscribe',
        }, 'view');

    extend(ProductShowPage.prototype, 'fields', function (fields) {
        fields.add('stock-notifications', m('.Form-group', Button.component({
            className: 'Button',
            icon: 'fas fa-bell',
            onclick: () => {
                if (!confirm(extractText(app.translator.trans('flamarkt-stock-notifications.backoffice.action.confirmNotify', {
                    count: this.product!.attribute('stockNotificationRequestCount'),
                })))) {
                    return;
                }

                app.request({
                    method: 'POST',
                    url: app.forum.attribute('apiUrl') + '/flamarkt/products/' + this.product!.id() + '/stock-notifications',
                }).then(() => {
                    // TODO: make a better UI + refresh request count after success
                    alert('Done');
                });
            },
            // TODO: disable/hide field if there's no one to notify
        }, app.translator.trans('flamarkt-stock-notifications.backoffice.action.notify'))), -10);
    });
});
