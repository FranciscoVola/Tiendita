import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import DetallesProducto from '../views/DetallesProducto.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Admin from '../views/Admin.vue';
import Carrito from '../views/Carrito.vue';
import Error404 from '../views/Error404.vue';

const routes = [
  { path: '/', component: Home, name: 'home' },
  { path: '/producto/:id', component: DetallesProducto, name: 'detalles' },
  { path: '/admin', component: Admin, name: 'admin' },
  { path: '/login', component: Login, name: 'login' },  
  { path: '/register', component: Register, name: 'register' },
  { path: '/carrito', component: Carrito, name: 'carrito' },  
  { path: '/:pathMatch(.*)*', component: Error404, name: 'error404' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Ruta protegida para admin
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !localStorage.getItem('usuario')) {
    next('/login');
  } else {
    next();
  }
});


export default router;
