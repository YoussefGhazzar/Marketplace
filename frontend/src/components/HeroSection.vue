<template>
  <div class="hero">
    <h1>La Marketplace 100% Féminine</h1>
    <p>Achetez, vendez et échangez des produits naturels et bio entre femmes</p>
    <div class="hero-buttons">
      <router-link to="/produits" class="btn-primary">Découvrir les produits</router-link>
      <router-link to="/vendre" class="btn-secondary">Commencer à vendre</router-link>
    </div>
  </div>
</template>

<style scoped>
.hero {
    background: linear-gradient(135deg, var(--rose-fonce), var(--rose-vif), var(--rose-moyen));
    color: white;
    text-align: center;
    padding: 55px 20px;
}

.hero h1 {
    font-size: 48px;
    margin-bottom: 15px;
    letter-spacing: 2px;
}

.hero p {
    font-size: 18px;
    margin-bottom: 30px;
    opacity: 0.9;
    max-width: 550px;
    margin-left: auto;
    margin-right: auto;
}

.hero-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.hero-buttons a {
    padding: 14px 40px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
    letter-spacing: 1px;
    transition: transform 0.3s;
}

.hero-buttons a:hover { transform: scale(1.05); }

.btn-primary { background-color: white; color: var(--rose-fonce); }

.btn-secondary {
    background-color: transparent;
    color: white;
    border: 2px solid white;
}
</style>
