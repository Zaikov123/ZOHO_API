import { createMemoryHistory, createRouter } from 'vue-router'

const routes = [
    {
        path: '/account/create',
        name: 'account.create',
        component: () => import('./components/accounts/CreateComponent.vue')
    },
    {
        path: '/deal/index',
        name: 'deal.index',
        component: () => import('./components/deals/IndexComponent.vue')
    },{
        path: '/deal/create',
        name: 'deal.create',
        component: () => import('./components/deals/CreateComponent.vue')
    },
]

const router = createRouter({
    history: createMemoryHistory(import.meta.env.BASE_URL),
    routes
})

export default router
