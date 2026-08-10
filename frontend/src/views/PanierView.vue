<template>
  <div class="panier-page-container">
    <Navbar />

    <div class="page-header">
      <h1>🛒 Mon Panier</h1>
      <p>Vos articles sélectionnés pour acheter ou échanger</p>
    </div>

    <!-- Tabs selector -->
    <div class="tabs">
      <button 
        class="tab-btn" 
        :class="{ active: activeTab === 'tous' }"
        @click="setTab('tous')"
      >
        🛒 Tous
      </button>
      <button 
        class="tab-btn" 
        :class="{ active: activeTab === 'acheter' }"
        @click="setTab('acheter')"
      >
        🛍️ Acheter
      </button>
      <button 
        class="tab-btn" 
        :class="{ active: activeTab === 'echanger' }"
        @click="setTab('echanger')"
      >
        🔄 Échanger
      </button>
    </div>

    <!-- Basket Content -->
    <div class="basket-content-wrapper">
      <div v-if="articlesFiltres.length === 0" class="panier-vide">
        <div class="icon">
          <span v-if="activeTab === 'acheter'">🛍️</span>
          <span v-else-if="activeTab === 'echanger'">🔄</span>
          <span v-else>🛒</span>
        </div>
        <p v-if="activeTab === 'acheter'">Aucun article à acheter</p>
        <p v-else-if="activeTab === 'echanger'">Aucun article à échanger</p>
        <p v-else>Votre panier est vide</p>
        
        <router-link 
          v-if="activeTab === 'echanger'" 
          to="/artisanat" 
          class="btn-shop"
        >
          Voir l'artisanat
        </router-link>
        <router-link 
          v-else 
          to="/soins" 
          class="btn-shop"
        >
          Découvrir les produits
        </router-link>
      </div>

      <div v-else class="panier-rempli">
        <div class="articles-grid">
          <div 
            v-for="item in articlesFiltres" 
            :key="item.id" 
            class="article-card"
          >
            <span class="type-badge" :class="item.type === 'acheter' ? 'badge-acheter' : 'badge-echanger'">
              {{ item.type === 'acheter' ? '🛍️ Achat' : '🔄 Échange' }}
            </span>
            <img 
              :src="item.image" 
              :alt="item.nom"
              @error="(e) => e.target.src = 'https://via.placeholder.com/220x175?text=Image'"
            />
            <div class="info">
              <h3>{{ item.nom }}</h3>
              <div class="vendeur">{{ item.vendeur }}</div>
              <span class="tag">{{ item.tag }}</span>
              <div class="prix">{{ item.prix }}</div>
            </div>
            <button class="btn-supprimer" @click="supprimerArticle(item.id)">
              🗑️ Retirer
            </button>
          </div>
        </div>

        <div class="total-section">
          <div class="total-bar">
            <span class="total-text">
              <span v-if="activeTab === 'acheter'">Total achats :</span>
              <span v-else-if="activeTab === 'echanger'">Total échanges :</span>
              <span v-else>Total :</span>
            </span>
            <span class="total-prix">{{ totalPrix }} DH</span>
          </div>
          
          <div v-if="activeTab === 'tous'" class="actions-wrapper">
            <button class="btn-confirmer" @click="confirmerCommande">
              ✅ Confirmer la commande
            </button>
            <button class="btn-vider" @click="viderPanier">
              🗑️ Vider le panier
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Success message toast -->
    <Transition name="toast">
      <div v-if="afficherSucces" class="success-toast">
        ✅ Commande confirmée !
      </div>
    </Transition>

    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Navbar from '../components/Navbar.vue'
import Footer from '../components/Footer.vue'

const articles = ref([])
const activeTab = ref('tous')
const afficherSucces = ref(false)

const chargerPanier = () => {
  articles.value = JSON.parse(localStorage.getItem('panier')) || []
  window.dispatchEvent(new Event('panier-mis-a-jour'))
}

const articlesFiltres = computed(() => {
  if (activeTab.value === 'acheter') {
    return articles.value.filter(art => art.type === 'acheter')
  }
  if (activeTab.value === 'echanger') {
    return articles.value.filter(art => art.type === 'echanger')
  }
  return articles.value
})

const totalPrix = computed(() => {
  let total = 0
  articlesFiltres.value.forEach(art => {
    const matched = art.prix.match(/\d+/)
    if (matched) {
      total += parseInt(matched[0], 10)
    }
  })
  return total
})

const setTab = (tab) => {
  activeTab.value = tab
}

const supprimerArticle = (id) => {
  articles.value = articles.value.filter(art => art.id !== id)
  localStorage.setItem('panier', JSON.stringify(articles.value))
  chargerPanier()
}

const viderPanier = () => {
  if (confirm('Vider tout le panier ?')) {
    articles.value = []
    localStorage.removeItem('panier')
    chargerPanier()
  }
}

const confirmerCommande = () => {
  localStorage.removeItem('panier')
  articles.value = []
  afficherSucces.value = true
  
  window.dispatchEvent(new Event('panier-mis-a-jour'))
  
  setTimeout(() => {
    afficherSucces.value = false
  }, 3000)
}

onMounted(chargerPanier)
</script>

<style scoped>
.panier-page-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background-color: #fff5f8;
}

.page-header {
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  color: white;
  text-align: center;
  padding: 50px 20px;
}

.page-header h1 {
  font-size: 38px;
  margin-bottom: 8px;
  letter-spacing: 2px;
  font-family: 'Georgia', serif;
}

.page-header p {
  font-size: 16px;
  opacity: 0.9;
  font-family: 'Georgia', serif;
}

.tabs {
  display: flex;
  justify-content: center;
  gap: 15px;
  padding: 30px 20px 15px;
  flex-wrap: wrap;
}

.tab-btn {
  padding: 12px 35px;
  border-radius: 25px;
  border: 2px solid #8b1a4a;
  font-size: 15px;
  font-family: 'Georgia', serif;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s;
  background: white;
  color: #8b1a4a;
  outline: none;
}

.tab-btn.active {
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  color: white;
  border-color: transparent;
}

.tab-btn:hover:not(.active) {
  background: #fff0f5;
}

.basket-content-wrapper {
  padding: 10px 30px 60px;
  flex-grow: 1;
}

.panier-vide {
  text-align: center;
  padding: 70px 20px;
  color: #bbb;
}

.panier-vide .icon {
  font-size: 60px;
  margin-bottom: 18px;
}

.panier-vide p {
  font-size: 17px;
  margin-bottom: 22px;
  color: #999;
  font-family: 'Georgia', serif;
}

.btn-shop {
  display: inline-block;
  padding: 12px 30px;
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  color: white;
  border-radius: 25px;
  text-decoration: none;
  font-weight: bold;
  font-size: 15px;
  font-family: 'Georgia', serif;
  transition: opacity 0.3s;
}

.btn-shop:hover {
  opacity: 0.85;
}

.articles-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
  max-width: 1100px;
  margin: 0 auto 25px;
}

.article-card {
  background: white;
  border-radius: 15px;
  width: 220px;
  box-shadow: 0 4px 20px rgba(139,26,74,0.12);
  overflow: hidden;
  transition: transform 0.3s;
  position: relative;
  display: flex;
  flex-direction: column;
}

.article-card:hover {
  transform: translateY(-5px);
}

.article-card img {
  width: 100%;
  height: 175px;
  object-fit: cover;
  background-color: #fffafb;
}

.article-card .info {
  padding: 12px 14px 8px;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

.article-card h3 {
  font-size: 13px;
  color: #333;
  margin-bottom: 4px;
  line-height: 1.4;
  font-family: 'Georgia', serif;
}

.article-card .vendeur {
  font-size: 11px;
  color: #aaa;
  margin-bottom: 5px;
  font-family: 'Georgia', serif;
}

.article-card .prix {
  color: #c2185b;
  font-weight: bold;
  font-size: 17px;
  margin-top: auto;
  font-family: 'Georgia', serif;
}

.article-card .tag {
  align-self: flex-start;
  display: inline-block;
  background: #fff0f5;
  color: #8b1a4a;
  font-size: 10px;
  padding: 2px 9px;
  border-radius: 8px;
  margin-bottom: 6px;
  font-family: 'Georgia', serif;
}

.type-badge {
  position: absolute;
  top: 10px;
  left: 10px;
  padding: 4px 11px;
  border-radius: 10px;
  font-size: 11px;
  font-weight: bold;
  z-index: 2;
  box-shadow: 0 2px 5px rgba(0,0,0,0.15);
}

.badge-acheter {
  background: #8b1a4a;
  color: white;
}

.badge-echanger {
  background: white;
  color: #8b1a4a;
  border: 1.5px solid #8b1a4a;
}

.btn-supprimer {
  width: calc(100% - 28px);
  margin: 0 14px 12px;
  padding: 8px;
  background: #fff0f5;
  color: #c2185b;
  border: none;
  border-radius: 10px;
  font-size: 12px;
  font-family: 'Georgia', serif;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-supprimer:hover {
  background: #ffd6e7;
}

.total-section {
  max-width: 480px;
  margin: 0 auto;
  width: 100%;
}

.total-bar {
  background: white;
  border-radius: 15px;
  padding: 20px 25px;
  box-shadow: 0 4px 20px rgba(139,26,74,0.12);
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.total-bar .total-text {
  font-size: 17px;
  color: #333;
  font-family: 'Georgia', serif;
}

.total-bar .total-prix {
  font-size: 26px;
  color: #c2185b;
  font-weight: bold;
  font-family: 'Georgia', serif;
}

.actions-wrapper {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.btn-confirmer {
  width: 100%;
  padding: 14px;
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 16px;
  font-family: 'Georgia', serif;
  font-weight: bold;
  cursor: pointer;
  transition: opacity 0.3s;
}

.btn-confirmer:hover {
  opacity: 0.85;
}

.btn-vider {
  display: block;
  width: 100%;
  padding: 11px;
  background: white;
  color: #c2185b;
  border: 2px solid #c2185b;
  border-radius: 12px;
  font-size: 14px;
  font-family: 'Georgia', serif;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-vider:hover {
  background: #fff0f5;
}

/* Success Toast */
.success-toast {
  position: fixed;
  top: 25px;
  left: 50%;
  transform: translateX(-50%);
  background: #4caf50;
  color: white;
  padding: 14px 35px;
  border-radius: 30px;
  font-size: 16px;
  font-weight: bold;
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);
  z-index: 999;
  font-family: 'Georgia', serif;
}

/* Toast Transitions */
.toast-enter-active {
  animation: slide-down 0.4s ease-out;
}

.toast-leave-active {
  animation: fade-out 0.4s ease-in forwards;
}

@keyframes slide-down {
  from {
    transform: translate(-50%, -50px);
    opacity: 0;
  }
  to {
    transform: translate(-50%, 0);
    opacity: 1;
  }
}

@keyframes fade-out {
  from {
    opacity: 1;
    transform: translate(-50%, 0);
  }
  to {
    opacity: 0;
    transform: translate(-50%, -20px);
  }
}

@media (max-width: 768px) {
  .page-header {
    padding: 40px 15px;
  }

  .page-header h1 {
    font-size: 30px;
  }

  .basket-content-wrapper {
    padding: 10px 15px 40px;
  }

  .tab-btn {
    padding: 10px 20px;
    font-size: 14px;
  }

  .articles-grid {
    gap: 15px;
  }

  .article-card {
    width: calc(50% - 8px);
  }
}

@media (max-width: 480px) {
  .article-card {
    width: 100%;
    max-width: 280px;
  }
}
</style>

