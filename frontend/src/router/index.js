import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import ProduitsView from '../views/ProduitsView.vue'
import VendreView from '../views/VendreView.vue'
import ContactView from '../views/ContactView.vue'
import PanierView from '../views/PanierView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'accueil', component: HomeView },
    { path: '/produits', name: 'produits', component: ProduitsView },
    { path: '/vendre', name: 'vendre', component: VendreView },
    { path: '/contact', name: 'contact', component: ContactView },
    { path: '/panier', name: 'panier', component: PanierView },
    { path: '/soins', name: 'soins', component: ProduitsView },
    { path: '/artisanat', name: 'artisanat', component: ProduitsView }
  ]
})

export default router
