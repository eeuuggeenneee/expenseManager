/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */


axios.defaults.withCredentials = true;

import Sidebar from './components/Sidebar.vue';
import Dashboard from './components/Dashboard.vue';
import App from './components/App.vue';
import RolesList from './components/roles/RolesList.vue';
import UserComponent from './components/user/UserComponent.vue';
import ExpenseCategory from './components/expenses/ExpenseCategory.vue';
import Expenses from './components/expenses/Expenses.vue';
import '@fortawesome/fontawesome-free/css/all.css';
// app.component('example-component', ExampleComponent);

// app.component('dashboard-component', Dashboard);
// app.component('rolesList-component', RolesList);
// app.component('user-component', UserComponent);
// app.component('expensecategory-component', ExpenseCategory);
// app.component('expenses-component', Expenses);

const app = createApp(App);

const routes = [
    { path: '/', component: Dashboard },
    { path: '/roles' , component: RolesList },
    { path: '/users', component: UserComponent },
    { path: '/expense_category', component: ExpenseCategory },
    { path: '/expenses', component: Expenses },


];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

app.component('sidebar-component', Sidebar);
app.use(router);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
