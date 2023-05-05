import './style.scss'
import { createApp } from 'vue';
import { createRouter, createWebHashHistory } from 'vue-router'

import { Quasar, Notify } from 'quasar'
import quasarIconSet from 'quasar/icon-set/svg-bootstrap-icons'
//import '@quasar/extras/bootstrap-icons/bootstrap-icons.css'
import 'quasar/dist/quasar.css'

import axios from 'axios'
axios.defaults.withCredentials = true;
axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.baseURL = import.meta.env.VITE_API_URL;

import App from './App.vue'
import AuthLogin from './pages/AuthLogin.vue'
import AuthRegister from './pages/AuthRegister.vue'
import AuthForgot from './pages/AuthForgot.vue'
import AccountHome from './pages/AccountHome.vue'
import AccountCompanies from './pages/AccountCompanies.vue'
import AccountCompanyNew from './pages/AccountCompanyNew.vue'
import AccountCompanyEdit from './pages/AccountCompanyEdit.vue'


const routes = [
    { path: '/', redirect: '/auth/login' },
    { path: '/auth/login', component: AuthLogin, meta: { title: 'Вход' } },
    { path: '/auth/register', component: AuthRegister, meta: { title: 'Регистрация' } },
    { path: '/auth/forgot', component: AuthForgot, meta: { title: 'Забыли пароль?' } },

    { path: '/account', component: AccountHome, meta: { title: 'Личный кабинет' } },
    { path: '/account/companies', component: AccountCompanies, meta: { title: 'Организации' } },
    { path: '/account/company/new', component: AccountCompanyNew, meta: { title: 'Добавить организацию' } },
    { path: '/account/company/:id(\\d+)/:tab?', component: AccountCompanyEdit, meta: { title: 'Огранизация' } },
]

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    if (to.meta.title) {
        document.title = to.meta.title + ' | HR PLUS'
    }
    next()
})


const app = createApp(App);
app.use(router);
app.use(Quasar, {
    plugins: {
        Notify
    },
    config: {
        brand: {
            primary: '#3051d3',
        },
        notify: {}
    },
    iconSet: quasarIconSet,
})
app.mount('#app');


