<template>
  <div class="produits-container">
    <Navbar />

    <!-- Main Categories Selector Page -->
    <div v-if="currentRoute === 'produits'">
      <div class="page-header">
        <h1>Nos Produits</h1>
        <p>Choisissez votre catégorie</p>
      </div>

      <div class="categories-choix">
        <router-link
          v-for="cat in categories"
          :key="cat.title"
          :to="cat.link"
          class="categorie-principale"
        >
          <img :src="cat.image" :alt="cat.alt" />
          <div class="info">
            <h2>{{ cat.title }}</h2>
            <p>{{ cat.description }}</p>
            <span>Découvrir</span>
          </div>
        </router-link>
      </div>
    </div>

    <!-- Category Products Listing Page -->
    <div v-else class="listing-container">
      <div class="page-header">
        <h1>{{ pageTitle }}</h1>
        <p>{{ pageDescription }}</p>
      </div>

      <div class="recherche-bar">
        <input 
          type="text" 
          v-model="searchQuery" 
          placeholder="Rechercher par nom de vendeuse ou produit..."
          @keypress.enter="filtrer"
        />
        <button @click="filtrer">Rechercher</button>
        <button class="reset-btn" @click="resetFiltrer">Tout afficher</button>
      </div>
      
      <div class="resultat-info" id="resultatInfo">
        <span v-if="activeSearchQuery">
          {{ countResults }} produit(s) trouvé(s) pour "{{ activeSearchQuery }}"
        </span>
      </div>

      <div class="produits-grid">
        <!-- Render Products -->
        <div 
          v-for="item in filteredProducts" 
          :key="item.id" 
          class="produit-card"
        >
          <img 
            :src="item.isCustom ? item.photo : getImageUrl(item.image)" 
            :alt="item.nom"
            @error="(e) => e.target.src = 'https://via.placeholder.com/220x180?text=Produit'"
          />
          <div class="info">
            <h3>{{ item.nom }}</h3>
            <div class="vendeur">{{ item.vendeur }}</div>
            <div class="tag">{{ item.tag }}</div>
            <div class="prix">{{ item.prix }}</div>
          </div>
          <div class="card-buttons">
            <button 
              class="btn-acheter" 
              :class="{ 'added': articlesAjoutes[`${item.id}-acheter`] }"
              @click="ajouterAuPanier(item, 'acheter')"
            >
              {{ articlesAjoutes[`${item.id}-acheter`] ? '✓ Ajouté!' : 'Acheter' }}
            </button>
            <button 
              class="btn-echanger" 
              :class="{ 'added': articlesAjoutes[`${item.id}-echanger`] }"
              @click="ajouterAuPanier(item, 'echanger')"
            >
              {{ articlesAjoutes[`${item.id}-echanger`] ? '✓ Ajouté!' : 'Echanger' }}
            </button>
          </div>
        </div>

        <div v-if="filteredProducts.length === 0" class="no-products-msg">
          Aucun produit trouvé.
        </div>
      </div>
    </div>

    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from '../components/Navbar.vue'
import Footer from '../components/Footer.vue'

import soinsImg from '../images/soins.png.jpeg'
import artisanatImg from '../images/artisanats.png.jpeg'

const categories = [
  {
    title: 'Soins & Beauté',
    description: 'Maquillage, parfums, soins visage et corps — des produits naturels et bio',
    image: soinsImg,
    alt: 'Soins et Beauté',
    link: '/soins'
  },
  {
    title: 'Produits Artisanaux',
    description: 'Jellaba, accessoires et créations artisanales faites à la main',
    image: artisanatImg,
    alt: 'Produits Artisanaux',
    link: '/artisanat'
  }
]

// Predefined Skin & Beauty Catalog
const soinsProducts = [
  { id: 's1', nom: 'Gommage Naturel pour les Lèvres', vendeur: 'Nadia - Travail à domicile', dataVendeur: 'nadia', tag: 'Bio & Naturel', prix: '45 DH', image: 'gommage-levres.jpeg' },
  { id: 's2', nom: 'BB Crème Sovegan', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Maquillage', prix: '150 DH', image: 'bb-creme-sovegan.jpeg' },
  { id: 's3', nom: 'Huile de Rose Naturelle', vendeur: 'Nadia - Travail à domicile', dataVendeur: 'nadia', tag: 'Bio & Naturel', prix: '80 DH', image: 'huile-rose.jpeg' },
  { id: 's4', nom: 'Crème Solaire La Roche-Posay', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Soins', prix: '220 DH', image: 'creme-soleil-la-roche-posay.jpeg' },
  { id: 's5', nom: 'Huile aux Herbes Naturelles', vendeur: 'Nadia - Travail à domicile', dataVendeur: 'nadia', tag: 'Bio & Naturel', prix: '75 DH', image: 'huile-herbes.jpeg' },
  { id: 's6', nom: 'Dior Lip Glow', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Maquillage', prix: '380 DH', image: 'dior-lip-glow.jpeg' },
  { id: 's7', nom: 'Nila Bleue Originale', vendeur: 'Nadia - Travail à domicile', dataVendeur: 'nadia', tag: 'Bio & Naturel', prix: '35 DH', image: 'nila-bleue-original.jpeg' },
  { id: 's8', nom: 'Fino Premium Touch Conditioner', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Cheveux', prix: '180 DH', image: 'fino-premium-touch-conditioner.jpeg' },
  { id: 's9', nom: 'Crème Naturelle pour le Corps', vendeur: 'Nadia - Travail à domicile', dataVendeur: 'nadia', tag: 'Bio & Naturel', prix: '90 DH', image: 'creme-corps.jpeg' },
  { id: 's10', nom: 'Fond de Teint Dior', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Maquillage', prix: '450 DH', image: 'fond-de-teint-dior.jpeg' },
  { id: 's11', nom: 'Gel d\'Aloe Vera Naturel', vendeur: 'Nadia - Travail à domicile', dataVendeur: 'nadia', tag: 'Bio & Naturel', prix: '55 DH', image: 'gel-aloe.jpeg' },
  { id: 's12', nom: 'Gamme Kérastase Résistance', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Cheveux', prix: '350 DH', image: 'gamme-kerastase-resistance.jpeg' },
  { id: 's13', nom: 'Huile de Coco Naturelle', vendeur: 'Nadia - Travail à domicile', dataVendeur: 'nadia', tag: 'Bio & Naturel', prix: '65 DH', image: 'huile-coco.jpeg' },
  { id: 's14', nom: 'Gloss Dior', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Maquillage', prix: '320 DH', image: 'gloss-dior.jpeg' },
  { id: 's15', nom: 'Teinture Naturelle pour les Lèvres', vendeur: 'Nadia - Travail à domicile', dataVendeur: 'nadia', tag: 'Bio & Naturel', prix: '40 DH', image: 'soinrosé-levres.jpeg' },
  { id: 's16', nom: 'Joocyee LipGlow', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Maquillage', prix: '160 DH', image: 'joocyee-lip-glow.jpeg' },
  { id: 's17', nom: 'Extrait Naturel d\'Eau de Grenade', vendeur: 'Nadia - Travail à domicile', dataVendeur: 'nadia', tag: 'Bio & Naturel', prix: '70 DH', image: 'eau-grenade.jpeg' },
  { id: 's18', nom: 'Kérastase Paris Genesis', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Cheveux', prix: '300 DH', image: 'kerastase-genesis-blonde.jpeg' },
  { id: 's19', nom: 'Huile pour Renforcer les Follicules', vendeur: 'Nadia - Travail à domicile', dataVendeur: 'nadia', tag: 'Bio & Naturel', prix: '85 DH', image: 'huile-fortifiante-cheveux.jpeg' },
  { id: 's20', nom: 'Masque Visage Sephora', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Soins Visage', prix: '130 DH', image: 'masque-visage-sephora.jpeg' },
  { id: 's21', nom: 'Gommage Corporel Naturel', vendeur: 'Nadia - Travail à domicile', dataVendeur: 'nadia', tag: 'Bio & Naturel', prix: '60 DH', image: 'gommage-corporel.jpeg' },
  { id: 's22', nom: 'Rouge à Lèvres Chanel', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Maquillage', prix: '420 DH', image: 'rouge-a-levre-chanel.jpeg' },
  { id: 's23', nom: 'Sol de Janeiro', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Soins Corps', prix: '250 DH', image: 'sol-de-janeiro.jpeg' },
  { id: 's24', nom: 'Sérum The Ordinary', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Soins Visage', prix: '120 DH', image: 'the-ordinary-serum.jpeg' },
  { id: 's25', nom: 'Brume Parfumée Victoria\'s Secret', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Parfums', prix: '190 DH', image: 'victorias-secret-brume.jpeg' },
  { id: 's26', nom: 'Crème Hydratante La Roche-Posay', vendeur: 'Sara - Petite Boutique', dataVendeur: 'sara', tag: 'Soins', prix: '240 DH', image: 'la-roche-posay-creme-solaire.jpeg' }
]

// Predefined Artisanal Catalog
const artisanatProducts = [
  { id: 'a1', nom: 'Kaftan Beldi', vendeur: 'Ibtissam - Travail à domicile', dataVendeur: 'ibtissam', tag: 'Kaftan', prix: '350 DH', image: 'kaftan-beldi.jpeg' },
  { id: 'a2', nom: 'Kaftan Blanc Royal', vendeur: 'Hajar - Petite Boutique', dataVendeur: 'hajar', tag: 'Kaftan', prix: '550 DH', image: 'kaftan-blanc-royal.jpeg' },
  { id: 'a3', nom: 'Kaftan Deux Pièces Turquoise', vendeur: 'Ibtissam - Travail à domicile', dataVendeur: 'ibtissam', tag: 'Kaftan', prix: '480 DH', image: 'kaftan-deux-pieces-turquois.jpeg' },
  { id: 'a4', nom: 'Kaftan Vert Royal avec Strass', vendeur: 'Hajar - Petite Boutique', dataVendeur: 'hajar', tag: 'Kaftan', prix: '750 DH', image: 'kaftan-deux-pieces-vert-royal-orné-avec-pierres-en-strass.jpeg' },
  { id: 'a5', nom: 'Kaftan Rose avec Seffa', vendeur: 'Ibtissam - Travail à domicile', dataVendeur: 'ibtissam', tag: 'Kaftan', prix: '420 DH', image: 'Kaftan-rose-avec-seffa.jpeg' },
  { id: 'a6', nom: 'Kaftan Rose BB', vendeur: 'Hajar - Petite Boutique', dataVendeur: 'hajar', tag: 'Kaftan', prix: '390 DH', image: 'kaftan-rose-BB.jpeg' },
  { id: 'a7', nom: 'Qmiss avec Seffa', vendeur: 'Ibtissam - Travail à domicile', dataVendeur: 'ibtissam', tag: 'Qmiss', prix: '280 DH', image: 'Qmiss-avec-seffa.jpeg' },
  { id: 'a8', nom: 'Gendora Bleu Marine', vendeur: 'Hajar - Petite Boutique', dataVendeur: 'hajar', tag: 'Gendora', prix: '320 DH', image: 'gendora-bleu-marine.jpeg' },
  { id: 'a9', nom: 'Gendora Coloré', vendeur: 'Ibtissam - Travail à domicile', dataVendeur: 'ibtissam', tag: 'Gendora', prix: '300 DH', image: 'Gendora-coloré.jpeg' },
  { id: 'a10', nom: 'Hezam Kaftan Rose', vendeur: 'Hajar - Petite Boutique', dataVendeur: 'hajar', tag: 'Accessoire', prix: '120 DH', image: 'Hezam-kaftan-rose.jpeg' },
  { id: 'a11', nom: 'Hezam Kaftan Violet', vendeur: 'Ibtissam - Travail à domicile', dataVendeur: 'ibtissam', tag: 'Accessoire', prix: '110 DH', image: 'hezam-kaftan-violet.jpeg' },
  { id: 'a12', nom: 'Jellaba Bleu Deux Pièces', vendeur: 'Hajar - Petite Boutique', dataVendeur: 'hajar', tag: 'Jellaba', prix: '450 DH', image: 'jellaba-bleu-deux-pieces.jpeg' },
  { id: 'a13', nom: 'Jellaba Crêpe', vendeur: 'Ibtissam - Travail à domicile', dataVendeur: 'ibtissam', tag: 'Jellaba', prix: '380 DH', image: 'jellaba-crepe.jpeg' },
  { id: 'a14', nom: 'Jellaba Gruna Simple', vendeur: 'Hajar - Petite Boutique', dataVendeur: 'hajar', tag: 'Jellaba', prix: '290 DH', image: 'jellaba-gruna-simple.jpeg' },
  { id: 'a15', nom: 'Jellaba Jaune avec Seffa', vendeur: 'Ibtissam - Travail à domicile', dataVendeur: 'ibtissam', tag: 'Jellaba', prix: '420 DH', image: 'jellaba-jaune-avec-seffa.jpeg' },
  { id: 'a16', nom: 'Jellaba Randa', vendeur: 'Hajar - Petite Boutique', dataVendeur: 'hajar', tag: 'Jellaba', prix: '500 DH', image: 'jellaba-randa.jpeg' },
  { id: 'a17', nom: 'Jellaba Turquoise avec Seffa', vendeur: 'Ibtissam - Travail à domicile', dataVendeur: 'ibtissam', tag: 'Jellaba', prix: '460 DH', image: 'jellaba-turquoise-avec-seffa.jpeg' },
  { id: 'a18', nom: 'Jellaba Turquoise Crêpe', vendeur: 'Hajar - Petite Boutique', dataVendeur: 'hajar', tag: 'Jellaba', prix: '410 DH', image: 'jellaba-turquoise-crepe.jpeg' },
  { id: 'a19', nom: 'Jellaba Violet avec Seffa', vendeur: 'Ibtissam - Travail à domicile', dataVendeur: 'ibtissam', tag: 'Jellaba', prix: '440 DH', image: 'jellaba-violet-avec-seffa.jpeg' },
  { id: 'a20', nom: 'Jabador avec des Roses', vendeur: 'Hajar - Petite Boutique', dataVendeur: 'hajar', tag: 'Jabador', prix: '520 DH', image: 'jabador-avec-des-roses.jpeg' }
]

const route = useRoute()
const customProducts = ref([])
const articlesAjoutes = ref({})
const searchQuery = ref('')
const activeSearchQuery = ref('')

const currentRoute = computed(() => {
  if (route.path === '/soins') return 'soins'
  if (route.path === '/artisanat') return 'artisanat'
  return 'produits'
})

const pageTitle = computed(() => {
  return currentRoute.value === 'soins' ? 'Soins & Beauté' : 'Produits Artisanaux'
})

const pageDescription = computed(() => {
  return currentRoute.value === 'soins' 
    ? 'Produits naturels, bio et de marques proposés par nos vendeuses' 
    : 'Créations faites à la main par nos artisanes talentueuses'
})

const getImageUrl = (name) => {
  return new URL(`../images/${name}`, import.meta.url).href
}

const chargerCustomProducts = () => {
  customProducts.value = JSON.parse(localStorage.getItem('produits_publies')) || []
}

const allProducts = computed(() => {
  let list = []
  if (currentRoute.value === 'soins') {
    list = [...soinsProducts]
    // Filter and add custom products
    const customSoins = customProducts.value.filter(p => {
      if (!p.categorie) return true
      const cat = p.categorie.toLowerCase()
      return ['maquillage', 'parfums', 'soins visage', 'soins corps', 'autre'].includes(cat) || 
             !['jellaba', 'kaftan', 'accessoires'].includes(cat)
    }).map(p => ({
      id: p.id,
      nom: p.nom,
      vendeur: `${p.nomVendeuse} - ${p.typeVendeur}`,
      dataVendeur: p.nomVendeuse.toLowerCase(),
      tag: p.categorie,
      prix: `${p.prix} DH`,
      photo: p.photo,
      isCustom: true
    }))
    list = [...customSoins, ...list]
  } else if (currentRoute.value === 'artisanat') {
    list = [...artisanatProducts]
    // Filter and add custom products
    const customArtisanat = customProducts.value.filter(p => {
      if (!p.categorie) return false
      const cat = p.categorie.toLowerCase()
      return ['jellaba', 'kaftan', 'accessoires'].includes(cat)
    }).map(p => ({
      id: p.id,
      nom: p.nom,
      vendeur: `${p.nomVendeuse} - ${p.typeVendeur}`,
      dataVendeur: p.nomVendeuse.toLowerCase(),
      tag: p.categorie,
      prix: `${p.prix} DH`,
      photo: p.photo,
      isCustom: true
    }))
    list = [...customArtisanat, ...list]
  }
  return list
})

const filteredProducts = computed(() => {
  const query = activeSearchQuery.value
  if (!query) return allProducts.value
  
  return allProducts.value.filter(p => {
    const sellerMatch = p.dataVendeur.includes(query) || p.vendeur.toLowerCase().includes(query)
    const nameMatch = p.nom.toLowerCase().includes(query)
    const tagMatch = p.tag.toLowerCase().includes(query)
    return sellerMatch || nameMatch || tagMatch
  })
})

const countResults = computed(() => filteredProducts.value.length)

const filtrer = () => {
  activeSearchQuery.value = searchQuery.value.toLowerCase().trim()
}

const resetFiltrer = () => {
  searchQuery.value = ''
  activeSearchQuery.value = ''
}

const ajouterAuPanier = (item, type) => {
  const key = `${item.id}-${type}`
  articlesAjoutes.value[key] = true
  
  const produitPanier = {
    id: Date.now() + Math.random(),
    nom: item.nom,
    vendeur: item.vendeur,
    prix: item.prix,
    tag: item.tag,
    image: item.isCustom ? item.photo : getImageUrl(item.image),
    type: type
  }
  
  const panier = JSON.parse(localStorage.getItem('panier')) || []
  panier.push(produitPanier)
  localStorage.setItem('panier', JSON.stringify(panier))
  
  window.dispatchEvent(new Event('panier-mis-a-jour'))
  
  setTimeout(() => {
    articlesAjoutes.value[key] = false
  }, 2000)
}

onMounted(() => {
  chargerCustomProducts()
  window.addEventListener('produits-mis-a-jour', chargerCustomProducts)
})

watch(() => route.path, () => {
  resetFiltrer()
  chargerCustomProducts()
})
</script>

<style scoped>
.produits-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background-color: #fff5f8;
}

.page-header {
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  color: white;
  text-align: center;
  padding: 60px 20px;
}

.page-header h1 {
  font-size: 40px;
  margin-bottom: 10px;
  letter-spacing: 2px;
  font-family: 'Georgia', serif;
}

.page-header p {
  font-size: 17px;
  opacity: 0.9;
  font-family: 'Georgia', serif;
}

.categories-choix {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 50px;
  padding: 100px 30px;
  flex-wrap: wrap;
  flex-grow: 1;
}

.categorie-principale {
  background: white;
  border-radius: 20px;
  width: 320px;
  overflow: hidden;
  box-shadow: 0 6px 30px rgba(139, 26, 74, 0.15);
  text-decoration: none;
  color: #333;
  transition: transform 0.3s, box-shadow 0.3s;
}

.categorie-principale:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 40px rgba(139, 26, 74, 0.25);
}

.categorie-principale img {
  width: 100%;
  height: 220px;
  object-fit: cover;
  display: block;
}

.categorie-principale .info {
  padding: 25px;
  text-align: center;
  border-top: 4px solid #c2185b;
}

.categorie-principale .info h2 {
  font-size: 22px;
  color: #8b1a4a;
  margin-bottom: 10px;
  font-family: 'Georgia', serif;
}

.categorie-principale .info p {
  font-size: 14px;
  color: #888;
  line-height: 1.6;
  margin-bottom: 20px;
  font-family: 'Georgia', serif;
}

.categorie-principale .info span {
  display: inline-block;
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  color: white;
  padding: 10px 30px;
  border-radius: 25px;
  font-size: 14px;
  font-weight: bold;
  letter-spacing: 1px;
  transition: opacity 0.3s;
}

.categorie-principale:hover .info span {
  opacity: 0.9;
}

/* Listings Styles */
.listing-container {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.recherche-bar {
  display: flex;
  justify-content: center;
  padding: 35px 20px 10px;
  gap: 10px;
  flex-wrap: wrap;
}

.recherche-bar input {
  padding: 12px 20px;
  border: 2px solid #c2185b;
  border-radius: 25px;
  font-size: 15px;
  font-family: 'Georgia', serif;
  width: 300px;
  outline: none;
  color: #333;
  background-color: white;
  transition: border-color 0.3s;
}

.recherche-bar input:focus {
  border-color: #8b1a4a;
}

.recherche-bar button {
  padding: 12px 25px;
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  color: white;
  border: none;
  border-radius: 25px;
  font-size: 15px;
  cursor: pointer;
  font-family: 'Georgia', serif;
  font-weight: bold;
  transition: opacity 0.3s;
}

.recherche-bar button:hover {
  opacity: 0.85;
}

.recherche-bar .reset-btn {
  padding: 12px 20px;
  background: white;
  color: #8b1a4a;
  border: 2px solid #8b1a4a;
  border-radius: 25px;
  font-size: 15px;
  cursor: pointer;
  font-family: 'Georgia', serif;
  font-weight: bold;
  transition: background-color 0.3s;
}

.recherche-bar .reset-btn:hover {
  background: #fff0f5;
}

.resultat-info {
  text-align: center;
  color: #8b1a4a;
  font-size: 15px;
  margin: 10px 0;
  min-height: 25px;
  font-family: 'Georgia', serif;
}

.produits-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 25px;
  justify-content: center;
  padding: 20px 30px 60px;
  flex-grow: 1;
}

.produit-card {
  background: white;
  border-radius: 15px;
  width: 220px;
  box-shadow: 0 4px 20px rgba(139, 26, 74, 0.12);
  overflow: hidden;
  transition: transform 0.3s, box-shadow 0.3s;
  display: flex;
  flex-direction: column;
}

.produit-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 10px 25px rgba(139, 26, 74, 0.2);
}

.produit-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  background-color: #fffafb;
}

.produit-card .info {
  padding: 15px;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

.produit-card .info h3 {
  font-size: 14px;
  color: #333;
  margin-bottom: 6px;
  font-family: 'Georgia', serif;
  line-height: 1.4;
}

.produit-card .info .vendeur {
  font-size: 12px;
  color: #999;
  margin-bottom: 8px;
  font-family: 'Georgia', serif;
}

.produit-card .info .prix {
  color: #c2185b;
  font-weight: bold;
  font-size: 18px;
  margin-top: auto;
  font-family: 'Georgia', serif;
}

.produit-card .info .tag {
  align-self: flex-start;
  display: inline-block;
  background: #fff0f5;
  color: #8b1a4a;
  font-size: 11px;
  padding: 3px 10px;
  border-radius: 10px;
  margin-bottom: 8px;
  font-family: 'Georgia', serif;
}

.card-buttons {
  display: flex;
  gap: 8px;
  padding: 0 15px 15px;
}

.btn-acheter, .btn-echanger {
  flex: 1;
  padding: 10px;
  font-weight: bold;
  font-size: 12px;
  border-radius: 10px;
  cursor: pointer;
  font-family: 'Georgia', serif;
  transition: all 0.3s;
}

.btn-acheter {
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  color: white;
  border: none;
}

.btn-acheter:hover {
  opacity: 0.85;
}

.btn-echanger {
  background: white;
  color: #8b1a4a;
  border: 2px solid #8b1a4a;
}

.btn-echanger:hover {
  background: #fff0f5;
}

.btn-acheter.added,
.btn-echanger.added {
  background: #4caf50 !important;
  color: white !important;
  border-color: #4caf50 !important;
  box-shadow: none !important;
  opacity: 1 !important;
  cursor: default;
}

.no-products-msg {
  text-align: center;
  color: #8b1a4a;
  font-family: 'Georgia', serif;
  font-size: 18px;
  padding: 40px;
  width: 100%;
}

@media (max-width: 768px) {
  .page-header {
    padding: 40px 15px;
  }

  .page-header h1 {
    font-size: 30px;
  }

  .categories-choix {
    padding: 50px 20px;
    gap: 30px;
  }

  .categorie-principale {
    width: 100%;
    max-width: 340px;
  }

  .recherche-bar input {
    width: 100%;
  }

  .produits-grid {
    padding: 20px 15px 40px;
    gap: 15px;
  }

  .produit-card {
    width: calc(50% - 8px);
  }
}

@media (max-width: 480px) {
  .produit-card {
    width: 100%;
    max-width: 280px;
  }
}
</style>
