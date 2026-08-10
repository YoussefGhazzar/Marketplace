<template>
  <div class="apropos">
    <img src="../images/makeup.jpeg" alt="Produits beauté naturels">
    <div class="apropos-text">
      <h2>Notre Mission</h2>
      <p>Une plateforme pour femmes entrepreneures, avec des produits 100% naturels et bio.</p>
      <router-link to="/produits">Explorer la boutique</router-link>
    </div>
  </div>
</template>

<style scoped>
.apropos {
    display: flex;
    align-items: center;
    gap: 50px;
    max-width: 1100px;
    margin: 60px auto;
    padding: 0 40px;
}

.apropos img {
    width: 400px;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(139,26,74,0.2);
}

.apropos-text h2 {
    font-size: 30px;
    color: var(--rose-fonce);
    margin-bottom: 15px;
}

.apropos-text p {
    font-size: 16px;
    color: #555;
    line-height: 1.7;
    margin-bottom: 15px;
}

.apropos-text a {
    display: inline-block;
    margin-top: 5px;
    background-color: var(--rose-fonce);
    color: white;
    padding: 12px 30px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: bold;
}
</style>
