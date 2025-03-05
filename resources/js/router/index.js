import Dashboard from '../views/Dashboard.vue';
import SubscribersList from '../views/subscribers/Index.vue';
import CreateSubscriber from '../views/subscribers/Create.vue';
import ShowSubscriber from '../views/subscribers/Show.vue';
import FieldsList from '../views/fields/Index.vue';
import CreateField from '../views/fields/Create.vue';
import EditField from '../views/fields/Edit.vue';
import EditSubscriber from '../views/subscribers/Edit.vue';

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/subscribers',
        name: 'subscribers.index',
        component: SubscribersList
    },
    {
        path: '/subscribers/create',
        name: 'subscribers.create',
        component: CreateSubscriber
    },
    {
        path: '/subscribers/:id',
        name: 'subscribers.show',
        component: ShowSubscriber,
        props: true
    },
    {
        path: '/subscribers/:id/edit',
        name: 'subscribers.edit',
        component: EditSubscriber,
        props: true
    },
    {
        path: '/fields',
        name: 'fields.index',
        component: FieldsList
    },
    {
        path: '/fields/create',
        name: 'fields.create',
        component: CreateField
    },
    {
        path: '/fields/:id/edit',
        name: 'fields.edit',
        component: EditField,
        props: true
    }
];

export default routes;
