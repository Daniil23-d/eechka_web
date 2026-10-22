import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', name: 'Home', component: () => import('../views/Home.vue') },
  { path: '/menu', name: 'Menu', component: () => import('../views/Menu.vue') },
  { path: '/dish/:id', name: 'DishDetail', component: () => import('../views/DishDetail.vue') },
  { path: '/contacts', name: 'Contacts', component: () => import('../views/Contacts.vue') },
  {
    path: '/admin',
    name: 'Admin',
    component: () => import('../views/Admin.vue'),
    meta: { requiresAuth: true }
  }
  
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router