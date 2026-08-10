<template>
  <div class="categories-section">
    <h2>Nos Catégories</h2>
    <div class="categories">
      <router-link
        to="/produits"
        class="categorie-card"
        v-for="cat in categories"
        :key="cat.nom"
      >
        <img class="cat-img" :src="cat.image" :alt="cat.nom">
        <p>{{ cat.nom }}</p>
      </router-link>
    </div>
  </div>
</template>

<script setup>
const categories = [
  { nom: 'Maquillage', image: 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=70&h=70&fit=crop' },
  { nom: 'Parfums', image: 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=70&h=70&fit=crop' },
  { nom: 'Soins Corps', image: 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=70&h=70&fit=crop' },
  { nom: 'Accessoires', image: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=70&h=70&fit=crop' }
]
</script>

<style scoped>
.categories-section {
    background: linear-gradient(180deg, #fff5f8, #ffe0ec);
    padding: 50px 30px;
    text-align: center;
}

.categories-section h2 {
    font-size: 30px;
    color: var(--rose-fonce);
    margin-bottom: 30px;
}

.categories {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.categorie-card {
    background: white;
    border-radius: 15px;
    padding: 30px 20px;
    text-align: center;
    width: 160px;
    box-shadow: 0 4px 20px rgba(139, 26, 74, 0.12);
    text-decoration: none;
    color: var(--rose-fonce);
    font-weight: bold;
    font-size: 14px;
    border-top: 4px solid var(--rose-vif);
    transition: transform 0.3s, box-shadow 0.3s;
}

.categorie-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 30px rgba(139, 26, 74, 0.22);
}

.categorie-card .cat-img {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
}
</style>
