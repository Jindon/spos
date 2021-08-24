import { createRouter, createWebHistory } from "vue-router";
import store from '@/scripts/store'

import Login from '@/views/pages/Auth/Login.vue'
import Dashboard from '@/views/pages/Dashboard.vue'
import Settings from '@/views/pages/Settings.vue'
import InvoiceList from '@/views/pages/Invoice/List.vue'
import InvoiceCreate from '@/views/pages/Invoice/Create.vue'
import InvoiceEdit from '@/views/pages/Invoice/Edit.vue'
import InvoicePrint from '@/views/pages/Invoice/Print.vue'
import CustomerList from '@/views/pages/Customer/List.vue'
import ProductList from '@/views/pages/Product/List.vue'

const routes = [
    {
        name: 'login',
        path: '/auth/login',
        component: Login
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard
    },
    {
        name: 'settings',
        path: '/settings',
        component: Settings
    },
    {
        name: 'invoices.list',
        path: '/invoices',
        component: InvoiceList,
        props: true
    },
    {
        name: 'invoices.create',
        path: '/invoices/create',
        component: InvoiceCreate
    },
    {
        name: 'invoices.edit',
        path: '/invoices/update/:invoiceId',
        component: InvoiceEdit,
        props: true
    },
    {
        name: 'invoices.print',
        path: '/invoices/print/:invoiceId',
        component: InvoicePrint,
        props: true
    },
    {
        name: 'customers.list',
        path: '/customers',
        component: CustomerList
    },
    {
        name: 'products.list',
        path: '/products',
        component: ProductList
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    if (to.path === '/') {
        next({ name: 'login' })
    }
    if (to.name !== 'login' && !store.getters['user/authenticated']){
        next({ name: 'login' })
    } else if (to.name === 'login' && store.getters['user/authenticated']){
        next({ name: 'dashboard' })
    } else next()
})

export default router
