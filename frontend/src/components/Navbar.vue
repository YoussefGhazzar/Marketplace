<template>
  <nav :class="{ 'nav-scrolled': isScrolled }">
    <div class="nav-inner">
      <router-link to="/" class="logo" @click="fermerMenu">
        <span class="logo-mark">B</span>
        <span class="logo-text">Beautée Shop</span>
      </router-link>

      <button
        class="burger"
        :class="{ open: menuOuvert }"
        @click="menuOuvert = !menuOuvert"
        :aria-expanded="menuOuvert"
        aria-label="Ouvrir le menu"
      >
        <span></span><span></span><span></span>
      </button>

      <ul :class="{ 'menu-ouvert': menuOuvert }">
        <li><router-link to="/" @click="fermerMenu">Accueil</router-link></li>
        <li><router-link to="/produits" @click="fermerMenu">Produits</router-link></li>
        <li><router-link to="/vendre" @click="fermerMenu">Vendre</router-link></li>
        <li><router-link to="/contact" @click="fermerMenu">Contact</router-link></li>
        <li>
          <router-link to="/panier" class="panier-link" @click="fermerMenu">
            <span class="panier-icone" :class="{ pulse: animerBadge }">🛒</span>
            <span class="panier-texte">Panier</span>
            <span v-if="nbArticles > 0" class="nav-badge" :class="{ pulse: animerBadge }">{{ nbArticles }}</span>
          </router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue'

const nbArticles = ref(0)
const isScrolled = ref(false)
const menuOuvert = ref(false)
const animerBadge = ref(false)

function lireArticles() {
  return (JSON.parse(localStorage.getItem('panier')) || []).length
}

function majBadge() {
  const total = lireArticles()
  if (total !== nbArticles.value) {
    nbArticles.value = total
    animerBadge.value = true
    setTimeout(() => (animerBadge.value = false), 400)
  }
}

function fermerMenu() {
  menuOuvert.value = false
}

function onScroll() {
  isScrolled.value = window.scrollY > 10
}

onMounted(() => {
  nbArticles.value = lireArticles()
  window.addEventListener('scroll', onScroll, { passive: true })
  window.addEventListener('storage', majBadge)
  window.addEventListener('panier-mis-a-jour', majBadge)
})

onUnmounted(() => {
  window.removeEventListener('scroll', onScroll)
  window.removeEventListener('storage', majBadge)
  window.removeEventListener('panier-mis-a-jour', majBadge)
})
</script>

<style scoped>
nav {
    background-color: #F4C2C2;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 10px rgba(0,0,0,0.12);
    transition: box-shadow 0.3s;
}

nav.nav-scrolled {
    box-shadow: 0 4px 20px rgba(0,0,0,0.25);
}

.nav-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 16px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--rose-fonce, #8a3b5a);
    text-decoration: none;
}

.logo-mark {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--rose-clair), var(--rose-moyen));
    color: white;
    font-weight: bold;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.logo-text {
    font-size: 24px;
    font-weight: bold;
    letter-spacing: 1.5px;
    white-space: nowrap;
}

nav ul {
    list-style: none;
    display: flex;
    gap: 8px;
    align-items: center;
}

nav ul li a {
    color: rgba(138, 59, 90, 0.8);
    text-decoration: none;
    font-size: 15px;
    letter-spacing: 0.5px;
    padding: 8px 14px;
    border-radius: 20px;
    transition: color 0.25s, background-color 0.25s;
    position: relative;
    display: flex;
    align-items: center;
    gap: 6px;
}

nav ul li a:hover {
    color: var(--rose-fonce, #8a3b5a);
    background-color: rgba(255,255,255,0.4);
}

nav ul li a.router-link-active {
    color: var(--rose-fonce, #8a3b5a);
    background-color: rgba(255,255,255,0.55);
    font-weight: bold;
}

.panier-link {
    position: relative;
    background-color: rgba(255,255,255,0.3);
}

.panier-icone {
    display: inline-block;
    transition: transform 0.2s;
}

.nav-badge {
    background: var(--rose-fonce, #8a3b5a); color: white;
    font-size: 11px; font-weight: bold;
    border-radius: 50%; min-width: 18px; height: 18px;
    display: flex; align-items: center; justify-content: center;
    padding: 0 4px;
}

.pulse {
    animation: pulse 0.4s ease;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.35); }
    100% { transform: scale(1); }
}

.burger {
    display: none;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
    width: 34px;
    height: 34px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
}

.burger span {
    display: block;
    height: 2px;
    width: 100%;
    background: var(--rose-fonce, #8a3b5a);
    border-radius: 2px;
    transition: transform 0.25s, opacity 0.25s;
}

.burger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.burger.open span:nth-child(2) { opacity: 0; }
.burger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

@media (max-width: 768px) {
    .nav-inner { padding: 14px 20px; }

    .burger { display: flex; }

    nav ul {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        flex-direction: column;
        align-items: stretch;
        gap: 2px;
        background-color: #F4C2C2;
        padding: 10px 20px 18px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    nav ul.menu-ouvert {
        max-height: 400px;
    }

    nav ul li a {
        border-radius: 10px;
        padding: 12px 14px;
    }

    .panier-texte { display: inline; }
}

@media (prefers-reduced-motion: reduce) {
    .pulse, .burger span, nav ul { animation: none; transition: none; }
}
</style>