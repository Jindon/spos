import { createRouter, createWebHistory } from "vue-router";
import store from '@/scripts/store'

const routes = [
    {
        name: 'login',
        path: '/auth/login',
        component: () => import('@/views/pages/Auth/Login.vue')
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: () => import('@/views/pages/Dashboard.vue')
    },
    {
        name: 'settings',
        path: '/settings',
        component: () => import('@/views/pages/Settings.vue')
    },
    {
        name: 'invoices.list',
        path: '/invoices',
        component: () => import('@/views/pages/Invoice/List.vue'),
        props: true
    },
    {
        name: 'invoices.create',
        path: '/invoices/add',
        component: () => import('@/views/pages/Invoice/Create.vue')
    },
    {
        name: 'invoices.edit',
        path: '/invoices/modify/:invoiceId',
        component: () => import('@/views/pages/Invoice/Edit.vue'),
        props: true
    },
    {
        name: 'customers.list',
        path: '/customers',
        component: () => import('@/views/pages/Customer/List.vue')
    },
    {
        name: 'products.list',
        path: '/products',
        component: () => import('@/views/pages/Product/List.vue')
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
