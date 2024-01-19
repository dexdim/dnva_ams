export default [
    {
        path: '/profile',
        component: require('./components/Profile.vue').default
    },
    {
        path: '/developer',
        component: require('./components/Developer.vue').default
    },
    {
        path: '/users',
        component: require('./components/Users.vue').default
    },
    {
        path: '/dashboard',
        component: require('./components/Dashboard.vue').default
    },
    {
        path: '/inventory',
        component: require('./components/Inventory.vue').default
    },
    {
        path: '/category',
        component: require('./components/Category.vue').default
    },
    {
        path: '/employee',
        component: require('./components/Employee.vue').default
    },
    {
        path: '*',
        component: require('./components/NotFound.vue').default
    }
]
