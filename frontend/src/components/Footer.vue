<template>
  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-brand">
        <router-link to="/" class="logo">
          <span class="logo-mark">B</span>
          <span class="logo-text">Beautée Shop</span>
        </router-link>
        <p class="footer-tagline">
          La marketplace des créatrices : produits naturels et artisanaux, faits avec passion.
        </p>
      </div>

      <div class="footer-col">
        <h4>Navigation</h4>
        <ul>
          <li><router-link to="/">Accueil</router-link></li>
          <li><router-link to="/produits">Produits</router-link></li>
          <li><router-link to="/vendre">Vendre</router-link></li>
          <li><router-link to="/contact">Contact</router-link></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Informations</h4>
        <ul>
          <li><router-link to="/livraison">Livraison</router-link></li>
          <li><router-link to="/retours">Retours &amp; remboursements</router-link></li>
          <li><router-link to="/cgv">Conditions générales</router-link></li>
          <li><router-link to="/confidentialite">Confidentialité</router-link></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Suivez-nous</h4>
        <ul class="social-list">
          <li><a href="#" aria-label="Instagram">Instagram</a></li>
          <li><a href="#" aria-label="Facebook">Facebook</a></li>
          <li><a href="#" aria-label="TikTok">TikTok</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; {{ anneeActuelle }} Beautée Shop. Tous droits réservés.</p>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue'

const anneeActuelle = computed(() => new Date().getFullYear())
</script>

<style scoped>
.site-footer {
    background-color: #F4C2C2;
    color: var(--rose-fonce, #8a3b5a);
    box-shadow: 0 -2px 10px rgba(0,0,0,0.12);
}

.footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 48px 40px 24px;
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    justify-content: space-between;
}

.footer-brand {
    flex: 1 1 260px;
    max-width: 320px;
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--rose-fonce, #8a3b5a);
    text-decoration: none;
    margin-bottom: 12px;
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
    font-size: 22px;
    font-weight: bold;
    letter-spacing: 1.5px;
}

.footer-tagline {
    font-size: 14px;
    line-height: 1.5;
    color: rgba(138, 59, 90, 0.85);
    margin: 0;
}

.footer-col {
    flex: 1 1 160px;
}

.footer-col h4 {
    font-size: 15px;
    letter-spacing: 0.5px;
    margin: 0 0 14px;
    color: var(--rose-fonce, #8a3b5a);
}

.footer-col ul {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.footer-col a {
    color: rgba(138, 59, 90, 0.8);
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s;
}

.footer-col a:hover {
    color: var(--rose-fonce, #8a3b5a);
    text-decoration: underline;
}

.footer-bottom {
    border-top: 1px solid rgba(138, 59, 90, 0.2);
    padding: 18px 40px;
    text-align: center;
}

.footer-bottom p {
    margin: 0;
    font-size: 13px;
    color: rgba(138, 59, 90, 0.75);
}

@media (max-width: 768px) {
    .footer-inner {
        padding: 36px 20px 20px;
        gap: 28px;
    }

    .footer-bottom {
        padding: 16px 20px;
    }
}
</style>