<template>
  <div class="stats">
    <div class="stat-item" v-for="stat in stats" :key="stat.label">
      <h3>{{ stat.valeur }}</h3>
      <p>{{ stat.label }}</p>
    </div>
  </div>
</template>

<script setup>
const stats = [
  { valeur: '+500', label: 'Vendeuses' },
  { valeur: '100%', label: 'Naturel & bio' },
  { valeur: '+3000', label: 'Clientes' }
]
</script>

<style scoped>
.stats {
    display: flex;
    justify-content: center;
    background: white;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.stat-item {
    text-align: center;
    padding: 25px 50px;
    border-right: 1px solid #f0e0e8;
}

.stat-item:last-child { border-right: none; }

.stat-item h3 {
    font-size: 32px;
    color: var(--rose-vif);
    font-weight: bold;
}

.stat-item p {
    font-size: 13px;
    color: #888;
    margin-top: 5px;
}
</style>
