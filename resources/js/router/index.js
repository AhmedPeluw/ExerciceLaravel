import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '@/views/Dashboard.vue';
import Login from '@/views/Login.vue';
import Register from '@/views/Register.vue';
import PaymentLinks from '@/views/PaymentLinks.vue';
import CreatePayment from '@/views/CreatePayment.vue';
import PaymentDetails from '@/views/PaymentDetails.vue';
import { useAuthStore } from '@/stores/authStore';
import ForgotPassword from '@/views/ForgotPassword.vue';
import ResetPassword from '@/views/ResetPassword.vue';
import Users from '@/views/Users.vue';



const routes = [
    { path: '/', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/users', component: Users, meta: { requiresAuth: true, requiresAdmin: true } },
    { path: '/payment-links', component: PaymentLinks, meta: { requiresAuth: true } },
    { path: '/payment-links/:id', component: PaymentDetails, props: true },
    { path: '/payment-links-create', component: CreatePayment, meta: { requiresAuth: true } },
    { path: '/forgot-password', component: ForgotPassword },
    { path: '/reset-password/:token', component: ResetPassword, props: true },
    { path: '/:catchAll(.*)', redirect: '/' }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    const user = JSON.parse(localStorage.getItem('user'))

    // if (to.meta.requiresAdmin && authStore.user?.role !== 'admin') {
    //     next('/forbidden');
    // } else {
    //     next();
    // }

    if (to.meta.requiresAuth && !authStore.token) {
        next('/login'); // Redirect if not logged in
    } 
    
    if (to.meta.requiresAdmin && (!user || user.role.name !== 'admin')) {
        return next('/'); // Redirect non-admin users
    }

    next();
});

export default router;
